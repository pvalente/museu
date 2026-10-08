# Tainacan AI prompt eval

A small eval for the AI cataloguing suggestions (Tainacan AI plugin + Claude). Use it to check a prompt change
before relying on it: run all cases, read the results, compare against the expectations below.

## What's here

| Path | What |
|---|---|
| `images/` | The 8 test photos (`e1`–`e8`), resized to ~1600 px. Credits below. |
| `prompt/preamble.txt` | The site-wide preamble (Tainacan → AI Tools → Default prompt preamble). **This is what's live.** |
| `prompt/fields.json` | Guidance per metadata field: `{ "<metadatum id>": ["description", "placeholder"] }`. IDs are collection 6 ("Museu da minha casa"): 9 = Título, 11 = Descrição. |
| `prompt/history/` | Earlier prompt versions. |
| `results/` | Output of each run, named `<date>-<label>.md`. |
| `php/` | WP-CLI scripts the runner uses: `apply.php` sets the prompt, `run.php` runs the analysis. |
| `run.sh` | Runs the whole eval on the server. |

## Running it

```bash
AWS_PROFILE=museu eval/tainacan-ai/run.sh my-change
```

This **applies `prompt/` to the live site** (it tests the real pipeline: WordPress AI → Tainacan AI → Anthropic),
uploads the images as temporary media titled `e1`…`e8` (neutral names so filenames don't hint the answer),
analyzes each one against collection 6's fields, deletes the uploads, and writes `results/<date>-my-change.md`.
A run takes about 2 minutes and costs roughly US$0.25 (~7k tokens per image on Claude Sonnet 5.5).

To try a change: edit `prompt/`, run, read the results against the checklist, and commit the prompt together with
its results file. To roll back, check out the previous `prompt/` and run again.

Field descriptions also count as prompt. If the collection's fields change (new fields, different IDs), update
`fields.json` and `php/run.php` (collection ID) to match.

## Cases and what a good answer looks like

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

Known limits: e4's monogram isn't read (fine; it's honest about it), and the ` / ` line breaks follow the AI's
reading of the layout, so check them on long texts.

## Image credits

Images e2–e8 come from Wikimedia Commons and were resized for this eval; the CC BY-SA ones remain under
[CC BY-SA 4.0](https://creativecommons.org/licenses/by-sa/4.0/).

| # | Credit | License |
|---|---|---|
| e1 | Family photo (Museu da minha casa) | All rights reserved |
| e2 | [Bilhete, Museu do Colono (2022-370)](https://commons.wikimedia.org/wiki/File:Bilhete,_Museu_do_Colono_(2022-370).jpg), Museu do Colono / Midiateca Capixaba | CC BY-SA 4.0 |
| e3 | [Homenagem do Sport Nautico da Bahia a Santos Dumont (4)](https://commons.wikimedia.org/wiki/File:Homenagem_do_Sport_Nautico_da_Bahia_a_Santos_Dumont,_Acervo_do_Museu_Paulista_da_USP_(4).jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| e4 | [Bule (1-07-03-000-05800-00-00-01)](https://commons.wikimedia.org/wiki/File:Bule_(1-07-03-000-05800-00-00-01),_Acervo_do_Museu_Paulista_da_USP.jpg), José Rosael/Hélio Nobre/Museu Paulista da USP | CC BY-SA 4.0 |
| e5 | [Cesto com tampa – Karajá 1939 MN 01](https://commons.wikimedia.org/wiki/File:Cesto_com_tampa_-_Karaj%C3%A1_1939_MN_01.jpg), photo by Dornicke; object by the Karajá people, Museu Nacional/UFRJ | CC BY-SA 4.0 |
| e6 | [Ex-voto, da coleção Museu Histórico Nacional](https://commons.wikimedia.org/wiki/File:Ex-voto,_da_cole%C3%A7%C3%A3o_Museu_Hist%C3%B3rico_Nacional.jpg), Museu Histórico Nacional | Public domain |
| e7 | [Cultura Marajoara – Cerâmica MN 05](https://commons.wikimedia.org/wiki/File:Cultura_Marajoara_-_Cer%C3%A2mica_MN_05.jpg), photo by Dornicke, Museu Nacional/UFRJ | CC BY-SA 4.0 |
| e8 | [Bilhete, Museu do Colono (2022.370)](https://commons.wikimedia.org/wiki/File:Bilhete,_Museu_do_Colono_(2022.370).jpg), Museu do Colono / Midiateca Capixaba | CC BY-SA 4.0 |
