# Image size sweep (2026-10-08)

How small can the image sent to Claude be before cataloguing suffers? `wp-content/mu-plugins/museu-ai.php`
downscales the attachment during `/tainacan-ai/v1/analyze` to a long edge of `museu_ai_image_max_edge` px
(0 = original). This sweep set that edge per run with the `museu_ai_image_max_edge` filter, uploading
temporary copies of the eval images and calling the endpoint with `rest_do_request` (same pipeline as `run.sh`:
live preamble, "Acervo Museológico" blueprint, Claude Sonnet 5.5, default effort). The uploads were deleted afterwards.

Cases: e1 (toucan, 2560 px, the same size WordPress caps uploads at with `-scaled`), e2 (handwritten note with faint
print), e3 (engraved plaque), e6 (ex-voto with an abbreviated 18th-century caption), m3-2 (mirror-image stamp),
m4-2 (porcelain maker's mark). e2 and e6 were run 3× at orig/1568/1024 (averages below); the rest once.

Pricing: US$2/M input, US$10/M output.

## Tokens, time, cost

Input tokens are deterministic for a given size; output (mostly adaptive thinking) varies by ±400 between runs,
so compare the **input** column for savings. About 4,000 input tokens are the prompt; the rest is the image.

| Case | Long edge | Sent (px, KB) | Input tok | Output tok (thinking) | Time s | Cost US$ |
|---|---|---|---|---|---|---|
| e1 | orig | 2560x1920, 326 | 8737 | 691 (0) | 7.7 | 0.0244 |
| e1 | **1568** | 1568x1176, 150 | 6349 | 701 (0) | 6.5 | 0.0197 |
| e1 | 1024 | 1024x768, 79 | 5033 | 681 (0) | 6.1 | 0.0169 |
| e1 | 768 | 768x576, 52 | 4585 | 709 (0) | 7.4 | 0.0163 |
| e1 | 512 | 512x384, 30 | 4263 | 691 (0) | 5.9 | 0.0154 |
| e2 (n=3) | orig | 1920x1276, 332 | 7171 | 2606 (1701) | 18.4 | 0.0404 |
| e2 (n=3) | **1568** | 1568x1042, 186 | 6125 | 2509 (1635) | 18.3 | 0.0373 |
| e2 (n=3) | 1024 | 1024x681, 89 | 4922 | 2526 (1606) | 18.6 | 0.0351 |
| e2 | 768 | 768x510, 53 | 4529 | 2372 (1458) | 16.5 | 0.0328 |
| e2 | 512 | 512x340, 25 | 4244 | 2128 (1251) | 15.8 | 0.0298 |
| e3 | orig | 1920x1257, 658 | 7102 | 1577 (845) | 12.2 | 0.0300 |
| e3 | **1568** | 1568x1027, 289 | 6069 | 1484 (789) | 12.2 | 0.0270 |
| e3 | 1024 | 1024x670, 110 | 4885 | 1872 (1086) | 14.5 | 0.0285 |
| e3 | 768 | 768x503, 58 | 4501 | 1631 (887) | 12.0 | 0.0253 |
| e3 | 512 | 512x335, 26 | 4225 | 2090 (1407) | 16.0 | 0.0294 |
| e6 (n=3) | orig | 1920x1417, 728 | 7516 | 2126 (1206) | 15.5 | 0.0363 |
| e6 (n=3) | **1568** | 1568x1157, 340 | 6349 | 2350 (1414) | 18.2 | 0.0362 |
| e6 (n=3) | 1024 | 1024x756, 141 | 4996 | 2200 (1265) | 16.2 | 0.0320 |
| e6 | 768 | 768x567, 78 | 4585 | 1905 (952) | 14.2 | 0.0282 |
| e6 | 512 | 512x378, 35 | 4263 | 2038 (1250) | 16.9 | 0.0289 |
| m3-2 | orig | 1600x1064, 478 | 6201 | 2020 (1320) | 16.9 | 0.0326 |
| m3-2 | **1568** | 1568x1043, 346 | 6125 | 1428 (751) | 11.8 | 0.0265 |
| m3-2 | 1024 | 1024x681, 139 | 4922 | 1722 (1043) | 14.7 | 0.0271 |
| m3-2 | 768 | 768x511, 74 | 4529 | 1803 (1119) | 14.6 | 0.0271 |
| m3-2 | 512 | 512x340, 32 | 4244 | 1789 (1128) | 15.0 | 0.0264 |
| m4-2 | orig | 1600x1200, 82 | 6491 | 1734 (1073) | 14.3 | 0.0303 |
| m4-2 | **1568** | 1568x1176, 69 | 6349 | 2333 (1606) | 18.7 | 0.0360 |
| m4-2 | 1024 | 1024x768, 35 | 5033 | 1865 (1106) | 14.2 | 0.0287 |
| m4-2 | 768 | 768x576, 22 | 4585 | 1832 (1119) | 14.3 | 0.0275 |
| m4-2 | 512 | 512x384, 12 | 4263 | 1873 (1168) | 15.4 | 0.0273 |

Latency is set by output length (thinking), not by image size: no consistent difference between sizes.

## Quality

Titles, descriptions and Material/Técnica were equivalent at every size, down to 512 (e1's toucan description is as
good at 512 as at 2560). What breaks first is **reading faint or small text**:

| Edge | e2 (note + faint print) | e3 (plaque) | e6 (1766 caption) | m3-2 / m4-2 (stamp, mark) | Verdict |
|---|---|---|---|---|---|
| orig | Full; faint print "Carl[?] J[?] Avancini[?]" (3/3) | Full, date 26/9/03 | Good; "gravem.te", "Senr.a", "DEOS" kept; misreads "matina", "Ano", "Não sei hejulgando" | CABANGU / S=D / MANTIQUEIRA; "Z.S.&C9 / BAVAR[ilegível]" | reference |
| **1568** | Full; faint print "Carl[?] Avancini[?]" (3/3: loses the "J.") | Full | **Best of all sizes** (3/3): "dehũa malina", "Anno", "Não se julgando", abbreviations kept | CABANGU[?]; "Z.S.&C[?] / BAVAR[?]" | **same as original** |
| 1024 | Faint print lost: "Car[ilegível]ancini" (3/3) | Full | "Merce" garbled (3/3: "Mece", "Mrece", "[?]erece"); "DEOS" → "Deos" (2/3) | "CABANCU[?]"; reads BAVARIA | loses faint / 18th-c. detail |
| 768 | Faint print lost; "R. R. Santo" | Full | "Desp.º", "mazina", "Senr.a" expanded to "Senhora" | "CABANCU[?]"; reads BAVARIA | worse |
| 512 | Date "24-12-191[?]" (Data de Produção too); "B. R. Santos" | Date marked doubtful, Data de Produção null | Caption almost all "[ilegível]" | "RABANCU[?]"; no Denominação for m3-2 | fails |

m4-2's mark read *better* when smaller (BAVARIA at ≤1024): the downscaled image is sharper/denser, but that is one case
and doesn't outweigh e2/e6.

## Decision

Default **`museu_ai_image_max_edge` = 1568** (set on the server with `wp option update`). It is the smallest size that
keeps transcription quality; 1024 saves another ~1,250 input tokens (US$0.0025) per item but loses faint print and
old handwriting consistently.

Savings per item (input only, deterministic):

| Original long edge | Input tokens saved | US$ saved | Share of item cost |
|---|---|---|---|
| 2560 (phone photos; WordPress `-scaled` cap) | 2,388 | 0.0048 | ~20% on e1 (short output), ~12% on a text-heavy item |
| 1920 | 1,050–1,170 | 0.0021–0.0023 | ~5–8% |
| ≤ 1568 | 0 | 0 | — |

Output (~0.7–2.6k tokens at US$10/M) remains the larger share of each item's cost.

## Downscale hang (fixed)

The first deployed version "hung" in `wp_get_image_editor()` on attachment 66 under wp-cli. Not Imagick: the filter
called `wp_attachment_is_image()`, which calls `get_attached_file()`, which re-entered the filter — infinite
recursion. wp-cli runs with `memory_limit=-1`, so it grew slowly instead of failing (in a web request at 512M it
would have been a fatal error). Imagick itself resizes the 2560 px file in 0.4 s. Fix: check the MIME type with
`get_post_mime_type()` instead, and clear the "analyzing" flag after each REST request.
