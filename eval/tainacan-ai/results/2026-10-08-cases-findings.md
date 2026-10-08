# Case-driven eval: scores, single vs multi-photo, failures (2026-10-08)

Runs (Claude Sonnet 5.5, default effort, site max_tokens 4,000, `museu_ai_image_max_edge` unset = 0, so the
original images were sent, up to 2560 px; Anthropic downsizes anything over ~1568 px on its side):

| File | What | Score |
|---|---|---|
| `2026-10-08-cases-single-sonnet.md` | All 28 cases, first view only, real `/tainacan-ai/v1/analyze` endpoint | **24/28** (Denominação 24, Classificação 26) |
| `2026-10-08-cases-multi-sonnet.md` | m1–m4, all views in one request (prototype) | **4/4** |
| `2026-10-08-cases-multi-first-control.md` | m1–m4, prototype with the first view only | 4/4 |

Pass = Denominação exact or an accepted alternative (`denominacao_alternativas`) **and** Classificação right; for
documents (e2, e8) null is right. Data de Produção is scored for information only.

## Prototype check

The multi-photo prototype composes the plugin's own system prompt (`AnalysisPromptComposer::get_context`, through
`DocumentAnalyzer::resolve_analysis_prompt_context`) and sends it via the same mu-plugin workaround as production.
Every request in all three runs logged a 9,117-character system instruction on the model config, and the
first-view control used the same input tokens as the production endpoint (+12 tokens for the extra
"Imagem 1 de 1" text). So the prototype matches production and the system prompt is sent:

| Case | Production endpoint, view 1 (in/out) | Prototype, view 1 (in/out) | Prototype, all views (in/out) |
|---|---|---|---|
| m1 (3 views) | 6,201 / 1,580 | 6,213 / 1,573 | 10,720 / 2,369 |
| m2 (2 views) | 7,361 / 1,482 | 7,373 / 1,530 | 10,821 / 2,008 |
| m3 (3 views) | 6,201 / 1,129 | 6,213 / 653 | 10,720 / 2,916 |
| m4 (2 views) | 6,665 / 654 | 6,677 / 680 | 9,255 / 2,574 |
| mean | 6,607 / 1,211 | 6,619 / 1,109 | 10,379 / 2,467 |

## Single vs multi-photo (m1–m4)

Denominação and Classificação are right with one view, so the thesaurus fields gain nothing. The gain is in the
fields only a later view can support:

| Case | First view only | All views |
|---|---|---|
| m1 sextant | Instrument and materials. Inscription only "[ilegível] Southampton[?]" on the arc. | Transcribes the lid plate exactly: "Sextante / que depois de usado por / Santos=Dumont / foi por êle dado a Gago Coutinho / em 1922". **Data de Produção stays null** (1922 is the gift, not the manufacture) and Autor stays null (Santos Dumont is a user, not a maker). Adds the case to Título and Madeira/Vidro/Gravação to Material/Técnica. |
| m2 coin | Data null: it did **not** take the Republic date 15-11-1889 on the value side as production date (trap avoided). Inscription mostly illegible. | **Data de Produção "1913"** with evidence "Imagem 2". Keeps "1889"[?] as an inscription only. Adds "REPUBLICA DOS ESTADOS UNIDOS DO BRASIL". |
| m3 stamp | A handled stamp, no text (nothing to read in view 1). | Reads the mirrored face correctly as "CABANGU / S=D / MANTIQUEIRA" (says it is mirrored, does not spell it backwards), puts it in the Título, and transcribes the maker's mark "J. XAVIER / RUA SACHET 18" (cases.json says "10"; worth a look at the photo) and puts **"J. XAVIER" in Autor**. Also lists the museum number "H-1790" as an inscription. |
| m4 plate | Plate with floral wreath, no text. | Reads the base mark "Z.S.&Cº / BAVARIA[?]" and puts it in **Autor** (manufacturer). Local de Produção stays null although the mark says Bavaria. Also lists the museum label "72.1.40." as an inscription. |

Evidence cites the view ("Imagem 2: …") throughout, which makes it easy to check.

**Cost:** all views costs about 1.6× the input tokens (+3,760 per case; ~2,250–3,450 per extra view of ~1600 px)
and about 2.2× the output tokens (it thinks more: 1,190–2,040 thinking tokens vs 0–870 with one view), and takes
~25 s instead of ~13 s per object.
Downscaling (the `museu_ai_image_max_edge` work) would cut the per-view part.

**Verdict:** worth it whenever the extra views carry text or marks (backs, bases, lids, reverses): it fills Data de
Produção, inscriptions and maker marks that a single view cannot, and it handled both date traps correctly. It
does not change the thesaurus terms.

## Failures (single-photo run)

| Case | Got | Expected | Likely cause |
|---|---|---|---|
| e3 | Placa (condecoração) → Insígnias | Placa comemorativa → Objetos cerimoniais e/ou comemorativos | **Matcher + prompt.** The AI named it just "Placa" this time (the earlier tesauro-v2 run said "Placa comemorativa"). "Placa" hits two homonyms at the same score, Placa (condecoração) and Placa (relevo), and the tie is broken alphabetically. The title ("Placa gravada com saudação a Santos Dumont") is only used when the name finds nothing. |
| c12 | Grilhão | Gargalheira | **Prompt/vision.** Same class (Instrumentos de punição), so Classificação is right; the AI read the collar as a generic shackle. |
| c13 | Relevo → Objetos associados às artes plásticas | Fragmento → Amostras e fragmentos | **Prompt**, partly **debatable label**. The title says "Fragmento de relevo…", but the name field got "Relevo". The museum record is "Vaso (fragmento de)"; whether Ferrez wants "Fragmento" for a broken piece of a known object type is a cataloguing policy question. |
| c16 | Espelho de fechadura | Aldrava | **Prompt/vision**, partly **debatable**: the escutcheon plate is the most visible part; the knocker ring was read as a ring on the plate. Same class (Elementos de construção). |

Other things the scores don't capture:
- c09: Autor = "American Bank Note Co. New York" (the printer). Defensible for a banknote; decide whether Autor
  should hold printers/manufacturers. Same question for m3 (J. Xavier is a maker or seller) and m4 (Z.S. & Co.).
- m1: cases.json has `data_producao: "1922"` but its notes say null is right (1922 is the gift). The scorer
  follows the field, so m1 shows ✗ for Data even though the answer is the good one. **Debatable expected label:**
  set it to null.
- m1/m2 single-view Data ✗ are expected: the dates are only on later views.
- m3/m4: museum numbers ("H-1790", "72.1.40.") listed under Inscrições.

## Suggestions (not applied; the prompt and blueprint are unchanged)

1. **Matcher, homonyms:** when the best hits tie (Placa (condecoração) / Placa (relevo)), use the title and
   description words to break the tie (e.g. "homenagem", "saudação", "comemorativa"), or try the title's
   longer prefixes first ("Placa gravada…" doesn't help here, but "Placa comemorativa" would be found if the
   AI had used it), and otherwise leave the choice to the cataloguer instead of picking alphabetically.
2. **Denominação guidance:** ask for the most specific name, including the function when the inscription gives it
   ("Placa comemorativa", not "Placa"); for a broken piece, name it "Fragmento" (or say how fragments are to be
   named). The title already does this; the name field doesn't.
3. **Autor guidance:** say whether a manufacturer's/printer's mark (Z.S. & Co., American Bank Note Co.,
   J. Xavier) goes in Autor, and that a place in a maker's mark (Bavaria) may support Local de Produção only
   if the field guidance allows it.
4. **Inscrições guidance:** museum inventory numbers and labels (painted numbers, tags, colour cards) are not
   inscriptions; mention them, if at all, as labels.
5. **Multi-photo upstream:** worth proposing to Tainacan AI: send the item's attachments (or document + selected
   attachments) in one request, labelled "Imagem k de N", with evidence citing the image. The prototype in
   `php/run.php` (`multi_analyze()`) shows the change is small.
6. cases.json: m1 `data_producao` → null; check m3's "Rua Sachet 10" vs "18" against the photo.
