# Tainacan AI: upstream notes

Bugs we found in [Tainacan AI](https://github.com/tainacan/tainacan-ai) while cataloguing on museulab.com, how we
work around them here, and what to keep in mind before proposing fixes upstream. **No PRs opened yet (on hold).**

## Issues filed

| Issue | Problem | Our workaround |
|---|---|---|
| [#44](https://github.com/tainacan/tainacan-ai/issues/44) | Images sent as a URL when the attachment URL answers a HEAD request; the Anthropic provider (1.0.5) only accepts inline images, so analysis fails on any public site. | `wp-content/mu-plugins/museu-ai.php` fails the HEAD self-check for uploads, so the plugin falls back to base64. |
| [#45](https://github.com/tainacan/tainacan-ai/issues/45) | System instruction (preamble, rules, field guidance), temperature, max tokens and timeout are silently dropped on WP 7: `builderHas()` uses `method_exists()`, but the WP 7 prompt builder exposes those methods via `__call`. | `museu-ai.php` captures the composed prompt (`tainacan_ai_analysis_prompt`) and sets it on the model config in `wp_ai_client_before_generate_result`, plus max tokens. |

## Rules for any PR

- **No regressions on older supported versions.** The plugin declares `Requires at least: 7.0`, `Requires PHP: 8.0`
  (tested up to 7.0), and its `CoreAI` helpers try both snake_case (WP core builder) and camelCase
  (php-ai-client `PromptBuilder`) methods, so it was written to work across client versions. A fix for WP 7.1 must:
  - keep working on WP 7.0.x and its bundled php-ai-client, and with the camelCase builder;
  - stay PHP 8.0-compatible (no enums, readonly properties, first-class callable syntax, `never`, etc.);
  - be checked on the oldest and newest supported WP (e.g. 7.0.0 and 7.1.x) before opening the PR.
  For #45 that means `is_callable([$builder, $method])` (works for real methods and `__call` alike) rather than
  switching to snake_case-only calls.
- Follow [Tainacan's CONTRIBUTING](https://github.com/tainacan/tainacan/blob/main/CONTRIBUTING.md): `feature/<desc>`
  branch, Conventional Commits (`fix(core-ai): …`), WordPress coding standards (PHPCS), reference the issue
  (`Closes #45`). tainacan-ai has only `main`, so PRs target `main`. Discuss larger changes in the issue first.
- Every PR so far is from the maintainer; keep ours small and easy to review.

## #44: what a fix should consider (research 2026-10-08)

The plugin's "send the URL if public" logic dates from the ~April 2026 connectors; providers differ and keep changing:

| Connector | Remote image | Inline image | Remote PDF | Inline PDF |
|---|---|---|---|---|
| Anthropic 1.0.5 | **throws** | base64 | URL | base64 |
| OpenAI 1.2.0 | passes the URL | data URI | URL | base64 |
| Google 1.2.0 | passes the URL (`fileUri`) | inline | URL | inline |

- php-ai-client has no way for a model to declare URL support, and nothing auto-converts remote files to inline; no
  open work on either.
- The official WordPress AI plugin's own image feature (alt text, 1.4.0) always sends images inline, downloading only
  as a fallback (hardened after advisory GHSA-v2wx-9j88-4rqq).
- Vendor fetchers can't reach private or bot-blocking sites (ours 403s AI crawlers), so URLs fail even where the
  connector accepts them.

Likely shape of a fix: inline by default (ideally a resized intermediate size to stay under Anthropic's 10 MB/image),
drop the HEAD heuristic, and offer a filter to opt into URLs. Separately, the Anthropic connector could accept image
URLs (its API does) — a small issue for that repo, not a fix for #44.

## Context the model gets (audit 2026-10-08, 0.2.0 and `main` @ d798413)

| | Today |
|---|---|
| Images | **One**: the item's document (else thumbnail, else first attachment). Other photos of the object (back, base, marks) are never sent, though `CoreAI::generate_json_from_text_and_files` already accepts several files and the PDF path already sends up to 3 page images. No resizing; no image picker. |
| Existing values | **Not sent.** The prompt has field definitions, not the item's current values. |
| "Fill all" | **Overwrites** any field the AI returned a value for, without comparing to the current value or confirming. No "verified/locked" notion. The per-field fill button is the safe path. |
| Other | No collection name/description; EXIF shown in the UI but not prompted; cache keyed per attachment only. |

Improvements, in priority order:
1. **Known values as context, never overwritten:** send the filled fields as "KNOWN VALUES (verified, do not contradict)" and skip them in extraction/Fill all. Doable as a site add-on now (capture `item_id` in `rest_request_before_callbacks`, add a section via `tainacan_ai_analysis_prompt_sections`, drop filled fields via `tainacan_ai_supports_extraction_for_prompt`, force refresh). Upstream: pass `item_id` to the prompt filters; "fill empty only" by default; per-field diff in the UI.
2. **Several images per item:** document plus other image attachments, labelled ("Image 2 of 4"), capped (~4). Needs upstream: `attachment_ids[]` on `/analyze`, N files in `analyze_image`, cache key over the set. Not doable via `wp_ai_client_before_generate_result` (messages are immutable there).
3. **Downscale before sending** (an intermediate size, ~1568 px): cheaper, fits provider limits, makes multi-image affordable; helps #44.
4. **Image picker** in the item form (thumbnails with checkboxes, optional caption per image).
5. Small: collection name/description as context; keep EXIF out of the prompt.

All of these must follow the rules above: optional new REST params with today's single-image behaviour as default, WP 7.0+ and PHP 8.0 compatible.

## Prompt caching (2026-10-08)

The Anthropic connector (1.0.5) sends `system` as a bare string, so nothing is ever cached
([WordPress/ai-provider-for-anthropic#33](https://github.com/WordPress/ai-provider-for-anthropic/issues/33); that
issue says it can't be done downstream, but it can). `museu-ai.php` clears the system instruction and passes `system`
as a custom option, a text block with `cache_control: ephemeral` (5-min TTL). Measured on the INBCM collection: the
first item writes 3,801 tokens, later items (any image, same collection) read them; uncached input drops to ~2.5k.
Input cost per item ~$0.0127 → ~$0.0055 (−57%). The connector sums cache reads/writes into input tokens, so
`getPromptTokens()` overstates cost; read `usage` from the raw response (`http_response` filter) to see the split.
