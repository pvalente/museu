# Tainacan AI prompt eval

A small eval for the AI cataloguing suggestions (Tainacan AI plugin + Claude). Use it to check a prompt change
before relying on it: run all cases, read the results, compare against the expectations below.

## What's here

| Path | What |
|---|---|
| `cases.json` | The 28 cases: images, what each tests, expected Denominação/Classificação (+ accepted alternatives), Data de Produção, inscriptions, notes, and the image sources. **The runner scores against it.** |
| `images/` | The test photos, resized to ~1600–1920 px (e1: 2560 px): `e1`–`e8`, `c01`–`c16` (one per thesaurus class) and the multi-view `m1`–`m4` (`m1-1`, `m1-2`, …). Credits below. |
| `prompt/preamble.txt` | The site-wide preamble (Tainacan → AI Tools → Default prompt preamble). **This is what's live.** |
| `../../scripts/tainacan/inbcm-museologico.json` | The collection the eval runs against ("Acervo Museológico", INBCM) **and its per-field AI guidance** (each field's `description`/`placeholder`). One source of truth: the eval applies this same blueprint. |
| `prompt/history/` | Earlier prompt versions (v1–v2 field guidance was for the old 2-field test collection). |
| `results/` | Output of each run, named `<date>-<label>.md`. |
| `php/` | WP-CLI scripts the runner uses: `apply.php` sets the prompt, `run.php` uploads, analyzes, scores and cleans up. |
| `run.sh` | Runs the whole eval on the server. |

## Running it

```bash
AWS_PROFILE=museu eval/tainacan-ai/run.sh my-change
# or try another model for this run only (the site keeps its own):
AWS_PROFILE=museu eval/tainacan-ai/run.sh my-change-haiku claude-haiku-5-5
# or set the reasoning effort (low|medium|high|xhigh|max) and/or max_tokens for this run only ("" keeps the site's model):
AWS_PROFILE=museu eval/tainacan-ai/run.sh my-change-low "" low
AWS_PROFILE=museu eval/tainacan-ai/run.sh my-change-xhigh "" xhigh 16000
# only some cases:
AWS_PROFILE=museu CASES=e2,e3,c13 eval/tainacan-ai/run.sh my-change-subset
# multi-photo prototype: every view of a case in one request (VIEWS=first sends only the first, as a control):
AWS_PROFILE=museu MODE=multi CASES=m1,m2,m3,m4 eval/tainacan-ai/run.sh my-change-multi
```

This **applies `prompt/preamble.txt` and the collection blueprint to the live site** (it tests the real pipeline:
WordPress AI → Tainacan AI → Tesauro Museus → Anthropic). For each case in `cases.json` it uploads the image(s) as
temporary media named after the case id (neutral names so filenames don't hint the answer), analyzes them against
the blueprint's collection, scores the answer and deletes the uploads; it writes `results/<date>-my-change.md`.

The results file starts with the settings (mode, model, effort, max_tokens and the `museu_ai_image_max_edge` in
effect), the **pass count** and a summary table, then each case in full. A case passes when Denominação is the
expected thesaurus term or one of its `denominacao_alternativas` (null for documents) **and** Classificação is
right. Data de Produção is compared too, for information. Each case header shows the model, input/output/thinking
tokens, the stop reason, the size of the system prompt actually sent (0 would mean the mu-plugin workaround
broke), the pixel size of the image(s) sent and the request time.

A full single-photo run (28 cases) takes about 7 minutes and ~190k input + 30k output tokens on Claude Sonnet 5.5
(~6.7k in / 1.1k out per image with the 12 INBCM fields).
Images go out downscaled to a 1568 px long edge (site option `museu_ai_image_max_edge`, chosen in
`results/2026-10-08-image-size.md`); earlier results sent the full ~1,920 px images.

**Single vs multi-photo.** Tainacan AI sends one image per analysis, so `MODE=single` (default) sends each case's
first view through the real `/tainacan-ai/v1/analyze` endpoint. `MODE=multi` is a prototype (`multi_analyze()` in
`php/run.php`): it builds the plugin's own system prompt (`AnalysisPromptComposer::get_context`), sends all views in
one request, each preceded by "Imagem k de N", asks for evidence to cite the image, then normalizes the answer and
runs the Tesauro Museus resolution the same way the endpoint does. Only the user message differs from production.

To try a change: edit `prompt/preamble.txt` or a field's `description` in the blueprint, run, read the results
against the checklist, and commit the change together with its results file. To roll back, check out the previous
version and run again.

## Cases and what a good answer looks like

`cases.json` has every case's expectations; the tables below detail e1–e8. `c01`–`c16` cover the 16 classes of the
Ferrez thesaurus (with traps: homonyms, alternative labels, inventory tags that are not inscriptions, objects of
one class that look like another). `m1`–`m4` have 2–3 views where a later view carries the text: a gift plate in a
sextant's lid, a coin's reverse with the minting year (the obverse has the 1889 Republic date as a trap), a mirrored
rubber stamp and its maker's mark, a plate's base mark (`multi_view_gain` says what each later view adds).

| # | Object | Source | Tests | Expect |
|---|---|---|---|---|
| e1 | Wooden toucan, phone photo on a shelf next to a vase | Family photo | Phone photo with clutter | Describes the carving only; never the shelf, the vase or the wall |
| e2 | Handwritten New Year's note, 1914, on printed card | Museu do Colono 2022.370 (front) | Handwriting, faint print under the signature | Transcribes the note with ` / ` line breaks; marks doubtful words with `[?]`; transcribes the faint printed name (museum: "Carlos J. Avancini"), "Villa de Santa Thereza", "E. E. Santo" and the date "24-12-1914" |
| e3 | Engraved plaque to Santos Dumont, 1903 | Museu Paulista/USP | Engraved cursive | Full transcription incl. "26/9/03"; date not in the title |
| e4 | Silver-plated teapot with monogram | Museu Paulista/USP 1-07-03-000-05800 | Plain object, ambiguous materials | Hedges materials ("aparentemente"); doesn't invent the monogram letters |
| e5 | Lidded basket, Karajá, before 1939, in a display case with another basket | Museu Nacional/UFRJ | Museum display, background object, Indigenous object | Ignores the display and the other basket; **no** people/culture named |
| e6 | Ex-voto painting with long abbreviated caption, 1766 | Museu Histórico Nacional | Dense scene, 18th-c. abbreviations, religious | Literal transcription keeping "gravem.te", "Senr.a", "DEOS"; no "estilo colonial"; dates only from the caption |
| e7 | Painted ceramic bowl | Museu Nacional/UFRJ (Marajoara collection) | Strong style cues | **No** cultural attribution (it once guessed "Shipibo-Konibo") |
| e8 | Back of the e2 note: ink showing through, mirrored | Museu do Colono 2022.370 (back) | Mirrored text | Says the writing is mirrored show-through and does **not** transcribe or invent it |

Expected values for the INBCM fields (✓ = what a good run gives):

| # | Data de Produção | Autor / Local / Dimensões | Material/Técnica | Estado |
|---|---|---|---|---|
| e1 | null | null | Madeira, Entalhe, Pintura | Bom or Regular |
| e2 | 24-12-1914 | null (the printed "Villa de Santa Thereza" is the card's printer, not a production place; null is fine) | Papel, Escrita manual, Impressão | Regular |
| e3 | 26/9/03 | null | Metal, Gravação | Bom |
| e4 | null | null | Metal, Gravação (not Marfim: only "aparentemente") | any |
| e5 | null | null | Fibra vegetal (+ Trançado) | Regular |
| e6 | 1766 | null | Madeira, Pintura, Escrita manual | Regular |
| e7 | null | null | Cerâmica, Pintura | Regular |
| e8 | null (the date is mirrored, not transcribed) | null | Papel, Escrita manual | Regular |

Administrative fields (Nº de Registro, Outros Números, Situação, Condições de Reprodução) are excluded from AI.
Denominação and Classificação come from the Ferrez thesaurus via the Tesauro Museus plugin (expected terms in `cases.json`).

Checks that apply to every case:
- pt-BR spelling ("umidade", "marrom"; not "humidade", "castanho").
- Never starts with "Fotografia de"/"Imagem de"; no background, lighting or support surface.
- Título: up to ~70 characters, no dates, places, styles or cultures; uses the inscription when it names the
  function or honoree ("Placa gravada de homenagem a Santos Dumont").
- Descrição: at most ~5 sentences / 90 words, then an `Inscrições:` paragraph when there is text.
- No authorship, period, origin or culture unless written on the object.

## Findings so far (2026-10-08)

| Run | Result |
|---|---|
| `r0-baseline` | Prompt had **no effect**: photo/background described, "Shipibo-Konibo" guessed for e7, invented content on e8. |
| — | Cause: Tainacan AI 0.2.0 drops the system instruction on WordPress 7 (it checks `method_exists()` on a builder that proxies through `__call`), so the model only saw "Analyze the attached image". Worked around in `wp-content/mu-plugins/museu-ai.php`. Images were also sent by URL, which the Anthropic provider rejects: [tainacan/tainacan-ai#44](https://github.com/tainacan/tainacan-ai/issues/44). |
| `v1` | With the fix, rules work. Left: e6 hit the 2,000-token limit (now enforced), the faint printed name on e2 was mistaken for show-through, titles too generic. |
| `v2` | All 8 pass. Clarified mirror vs. faint print, titles may use the inscription, `max_tokens` raised to 4,000. |
| `v2-rerun` | Same results on a second run. e6's description runs ~105 words (dense scene); acceptable. |
| `inbcm-v2` | First run on the INBCM collection (same preamble). Título/Descrição as good as before. Data de Produção taken only from inscriptions (1914, 03, 1766) and null otherwise; Autor, Local, Dimensões correctly null everywhere; Material/Técnica sensible. One miss: e4 tags "Marfim" although the description only says "aparentemente de marfim" — tags can't hedge. Denominação/Classificação null (taxonomies still empty). |
| `inbcm-v2-haiku` | Same prompt on **Claude Haiku 5.5** (`run.sh <label> claude-haiku-5-5`). Fine on plain objects (e1, e4, e5, e7, e8; e4 even avoids the "Marfim" tag). Worse where it matters most: e2 merges the signature and the faint printed name into "Carlo Carlos Ancini" without `[?]`, puts that in Autor, and normalizes "bôas" to "boas"; e3 misses Data de Produção "26/9/03"; e6 transcription has more confident misreadings ("Desp.to", "das Anjos") with fewer `[?]`. Verdict: keep Sonnet 5.5 for cataloguing. |
| `inbcm-effort-*` | Effort sweep on Sonnet 5.5 (`output_config.effort`; API default is `high`). `low` and `medium` never think (0 thinking tokens on all 8) and cost ~US$0.022/image vs ~0.028 at `high` (−21%), 6 s vs 10 s; but in 5/5 runs they put the printed "E. E. Santo" (the state, Espírito Santo) in e2's Autor, and `medium` missed e3's Data de Produção "26/9/03" twice. `high` thinks only on the text-heavy e2, e3, e6 and passes (2/2). `xhigh` hits the 4,000 max_tokens on e2 and e6. Verdict: keep the default (`high`). |
| `cases-single-sonnet` | First case-driven run, all 28 cases, one view: **24/28**. Misses: e3 named just "Placa", which the matcher resolves alphabetically to the homonym Placa (condecoração); c12 Grilhão for Gargalheira and c16 Espelho de fechadura for Aldrava (right class both times); c13 Relevo for Fragmento. Details and suggestions: `results/2026-10-08-cases-findings.md`. |
| `cases-multi-sonnet` | m1–m4 with all views in one request (prototype): 4/4, same terms as with one view, but it transcribes the sextant's gift plate (and keeps 1922 out of Data de Produção), gets the coin's 1913, reads the mirrored stamp and the maker's marks. ~1.6× input and ~2.2× output tokens. `cases-multi-first-control` (prototype, first view only) matches the endpoint's tokens, so the prototype sends the same system prompt. |
| `image-size` | Long-edge sweep (orig/1568/1024/768/512) on e1, e2, e3, e6, m3-2, m4-2. Titles and descriptions hold down to 512; transcription doesn't: at 1024 the faint print on e2 and the 1766 caption on e6 degrade (3/3), at 512 the caption is illegible. 1568 matches the original. Default set to **1568** (option `museu_ai_image_max_edge`), saving ~2,400 input tokens (US$0.005) on a 2,560 px photo, ~1,100 on 1,920 px. |

Known limits: e4's monogram isn't read (fine; it's honest about it), and the ` / ` line breaks follow the AI's
reading of the layout, so check them on long texts.

## Image credits

Credits and licenses are also in `cases.json` (`source`). Images other than e1 come from Wikimedia Commons and were
resized for this eval; the CC BY-SA ones remain under [CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0/).

| Image | Credit | License |
|---|---|---|
| e1 | Family photo (Museu da minha casa) | All rights reserved |
| e2 | [Bilhete, Museu do Colono (2022-370)](https://commons.wikimedia.org/wiki/File:Bilhete,_Museu_do_Colono_(2022-370).jpg), Museu do Colono / Midiateca Capixaba | CC BY-SA 4.0 |
| e3 | [Homenagem do Sport Nautico da Bahia a Santos Dumont, Acervo do Museu Paulista da USP (4)](https://commons.wikimedia.org/wiki/File:Homenagem_do_Sport_Nautico_da_Bahia_a_Santos_Dumont,_Acervo_do_Museu_Paulista_da_USP_(4).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| e4 | [Bule (1-07-03-000-05800-00-00-01), Acervo do Museu Paulista da USP](https://commons.wikimedia.org/wiki/File:Bule_(1-07-03-000-05800-00-00-01),_Acervo_do_Museu_Paulista_da_USP.jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| e5 | [Cesto com tampa - Karajá 1939 MN 01](https://commons.wikimedia.org/wiki/File:Cesto_com_tampa_-_Karaj%C3%A1_1939_MN_01.jpg), Photo by Dornicke; object by the Karajá people, Museu Nacional/UFRJ | CC BY-SA 4.0 |
| e6 | [Ex-voto, da coleção Museu Histórico Nacional](https://commons.wikimedia.org/wiki/File:Ex-voto,_da_cole%C3%A7%C3%A3o_Museu_Hist%C3%B3rico_Nacional.jpg), Museu Histórico Nacional | Public domain |
| e7 | [Cultura Marajoara - Cerâmica MN 05](https://commons.wikimedia.org/wiki/File:Cultura_Marajoara_-_Cer%C3%A2mica_MN_05.jpg), Photo by Dornicke, Museu Nacional/UFRJ | CC BY-SA 4.0 |
| e8 | [Bilhete, Museu do Colono (2022.370)](https://commons.wikimedia.org/wiki/File:Bilhete,_Museu_do_Colono_(2022.370).jpg), Museu do Colono / Midiateca Capixaba | CC BY-SA 4.0 |
| c01 | [Azulejo - Museu de Alcântara (MCHA.1837) - 2026-05-27 15.04.48](https://commons.wikimedia.org/wiki/File:Azulejo_-_Museu_de_Alc%C3%A2ntara_(MCHA.1837)_-_2026-05-27_15.04.48.jpg), Museu Casa Histórica de Alcântara (Ibram) | CC BY-SA 4.0 |
| c02 | [Leque - Museu de Alcântara (MCHA.0636) - 2023-02-27 09.12.19](https://commons.wikimedia.org/wiki/File:Leque_-_Museu_de_Alc%C3%A2ntara_(MCHA.0636)_-_2023-02-27_09.12.19.jpg), Museu Casa Histórica de Alcântara (Ibram) | CC BY-SA 4.0 |
| c03 | [Chapéu Masculino, Acervo do Museu Paulista da USP (1)](https://commons.wikimedia.org/wiki/File:Chap%C3%A9u_Masculino,_Acervo_do_Museu_Paulista_da_USP_(1).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| c04 | [Plaina, Museu do Colono (2022-283) 01](https://commons.wikimedia.org/wiki/File:Plaina,_Museu_do_Colono_(2022-283)_01.jpg), Museu do Colono / Midiateca Capixaba | CC BY-SA 4.0 |
| c05 | [Foice - Museu de Alcântara (MCHA.1286) - 2023-03-13 10.47.48](https://commons.wikimedia.org/wiki/File:Foice_-_Museu_de_Alc%C3%A2ntara_(MCHA.1286)_-_2023-03-13_10.47.48.jpg), Museu Casa Histórica de Alcântara (Ibram) | CC BY-SA 4.0 |
| c06 | [Barômetro, Museu do Colono (72.1.346)](https://commons.wikimedia.org/wiki/File:Bar%C3%B4metro,_Museu_do_Colono_(72.1.346).jpg), Museu do Colono / Midiateca Capixaba | CC BY-SA 4.0 |
| c07 | [Gramofone, Museu do Colono (74.3.14) 02](https://commons.wikimedia.org/wiki/File:Gramofone,_Museu_do_Colono_(74.3.14)_02.jpg), Museu do Colono / Midiateca Capixaba | CC BY-SA 4.0 |
| c08 | [Brinquedo - Pião, Acervo do Museu Paulista da USP (2)](https://commons.wikimedia.org/wiki/File:Brinquedo_-_Pi%C3%A3o,_Acervo_do_Museu_Paulista_da_USP_(2).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| c09 | [Papel-moeda - 1000 réis](https://commons.wikimedia.org/wiki/File:Papel-moeda_-_1000_r%C3%A9is.jpg), Casa da Moeda / American Bank Note Co. (printer); scan of Museu Paulista note from MP/Safra catalogue | Public domain |
| c10 | [Estribo - Museu de Alcântara (MCHA.0662) - 2026-02-20 15.25.18](https://commons.wikimedia.org/wiki/File:Estribo_-_Museu_de_Alc%C3%A2ntara_(MCHA.0662)_-_2026-02-20_15.25.18.jpg), Museu Casa Histórica de Alcântara (Ibram) | CC BY-SA 4.0 |
| c11 | [Garrucha 01- lado a- Museu da Capitania de Ilhéus](https://commons.wikimedia.org/wiki/File:Garrucha_01-_lado_a-_Museu_da_Capitania_de_Ilh%C3%A9us.jpg), Leslie Madureira Sá / Museu da Capitania de Ilhéus | CC BY-SA 4.0 |
| c12 | [Gargalheira, MAB (83.27), foto 1](https://commons.wikimedia.org/wiki/File:Gargalheira,_MAB_(83.27),_foto_1.jpg), Museu da Abolição (Ibram), Recife | CC BY-SA 4.0 |
| c13 | [Vaso (Fragmento De) (1-06-02-000-03577-00-00-01)](https://commons.wikimedia.org/wiki/File:Vaso_(Fragmento_De)_(1-06-02-000-03577-00-00-01).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| c14 | [Violão - Museu de Alcântara (MCHA.1567) - 2023-02-13 09.13.36](https://commons.wikimedia.org/wiki/File:Viol%C3%A3o_-_Museu_de_Alc%C3%A2ntara_(MCHA.1567)_-_2023-02-13_09.13.36.jpg), Museu Casa Histórica de Alcântara (Ibram) | CC BY-SA 4.0 |
| c15 | [Palmatória (1-06-05-000-11299-00-00-01)](https://commons.wikimedia.org/wiki/File:Palmat%C3%B3ria_(1-06-05-000-11299-00-00-01).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| c16 | [Aldrava - Museu de Alcântara (MCHA.1350) - 2023-06-23 09.16.38](https://commons.wikimedia.org/wiki/File:Aldrava_-_Museu_de_Alc%C3%A2ntara_(MCHA.1350)_-_2023-06-23_09.16.38.jpg), Museu Casa Histórica de Alcântara (Ibram) | CC BY-SA 4.0 |
| m1-1 | [Sextante, Acervo do Museu Paulista da USP (6)](https://commons.wikimedia.org/wiki/File:Sextante,_Acervo_do_Museu_Paulista_da_USP_(6).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| m1-2 | [Sextante, Acervo do Museu Paulista da USP (4)](https://commons.wikimedia.org/wiki/File:Sextante,_Acervo_do_Museu_Paulista_da_USP_(4).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| m1-3 | [Sextante, Acervo do Museu Paulista da USP (3)](https://commons.wikimedia.org/wiki/File:Sextante,_Acervo_do_Museu_Paulista_da_USP_(3).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| m2-1 | [Moeda 2000 réis- 1913- frente- Museu da Capitania de Ilhéus](https://commons.wikimedia.org/wiki/File:Moeda_2000_r%C3%A9is-_1913-_frente-_Museu_da_Capitania_de_Ilh%C3%A9us.jpg), VitóriaBCarvalho / Museu da Capitania de Ilhéus | CC BY-SA 4.0 |
| m2-2 | [Moeda 2000 réis- 1913- verso- Museu da Capitania de Ilhéus](https://commons.wikimedia.org/wiki/File:Moeda_2000_r%C3%A9is-_1913-_verso-_Museu_da_Capitania_de_Ilh%C3%A9us.jpg), VitóriaBCarvalho / Museu da Capitania de Ilhéus | CC BY-SA 4.0 |
| m3-1 | [Carimbo, Acervo do Museu Paulista da USP (1)](https://commons.wikimedia.org/wiki/File:Carimbo,_Acervo_do_Museu_Paulista_da_USP_(1).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| m3-2 | [Carimbo, Acervo do Museu Paulista da USP (3)](https://commons.wikimedia.org/wiki/File:Carimbo,_Acervo_do_Museu_Paulista_da_USP_(3).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| m3-3 | [Carimbo, Acervo do Museu Paulista da USP (4)](https://commons.wikimedia.org/wiki/File:Carimbo,_Acervo_do_Museu_Paulista_da_USP_(4).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| m4-1 | [Prato, Museu do Colono (72.1.40) (001)](https://commons.wikimedia.org/wiki/File:Prato,_Museu_do_Colono_(72.1.40)_(001).jpg), Museu do Colono / Midiateca Capixaba | CC BY-SA 4.0 |
| m4-2 | [Prato de decoração (1.1) (fundo), Acervo do Museu do Colono (Santa Leopoldina)](https://commons.wikimedia.org/wiki/File:Prato_de_decora%C3%A7%C3%A3o_(1.1)_(fundo),_Acervo_do_Museu_do_Colono_(Santa_Leopoldina).jpg), RDeminicis (Wikimedia Brasil), object of the Museu do Colono | CC BY-SA 4.0 |
