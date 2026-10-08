# Tesauro extractor

`build.py` turns the PDF of *Tesauro de Objetos do Patrimônio Cultural nos Museus
Brasileiros* (Helena Dodd Ferrez, 2016) into `data/tesauro-ferrez-2016.json`.

## Not committed: the compiled thesaurus

The source only allows partial reproduction ("Reprodução parcial permitida desde que
citada a fonte"). `data/*.json` is git-ignored (`../.gitignore`). Do not commit the
compiled full thesaurus to this public repo until permission is clarified. Build it
locally instead.

## Rebuild

```sh
# one-time: a venv anywhere outside the repo, with the only dependency
python3 -m venv ~/.venvs/tesauro && ~/.venvs/tesauro/bin/pip install pdfplumber==0.11.10

curl -L -o /tmp/tesauro.pdf \
  https://cultura.rs.gov.br/upload/arquivos/carga20190600/17110012-tesauro-de-objetos-do-patrimonio-cultural-dos-museus-brasileiros.pdf

~/.venvs/tesauro/bin/python plugins/tesauro-museus/tools/build.py /tmp/tesauro.pdf \
  --report /tmp/tesauro-report.txt
```

The build takes about 35 seconds. The output defaults to `../data/tesauro-ferrez-2016.json`
(change it with `-o`). Stats go to stderr. `--report` writes the stats plus every
warning, fix and cross-check. `--strict` exits 1 if a systematic term or a USE target
can't be resolved. The output is byte-identical across runs: terms stay in document
order, keys and `alt_labels` are sorted, and the file has no timestamps. The expected PDF
has sha256 `5eaef0a2…f1670b570`, which is recorded in `source.pdf_sha256`. If you pass a
different file, the build warns.

## What the parser assumes about the PDF

- The PDF is a "Microsoft: Print To PDF" export with real text, 803 pages. It is
  AES-encrypted with an empty password, and pdfminer opens it. Every page has the
  running title at the top and a page number at the bottom. Both are dropped by
  content (exact title, digits-only line near the bottom), not by coordinates.
- Lines are rebuilt from glyphs. The PDF emits explicit space glyphs. A space that
  overlaps the next glyph is a layout artefact (`***** *`, `5.1 0`) and is dropped.
  Glyphs outside the page box are ignored.
- The sections are located by their headings: **PARTE SISTEMÁTICA** (pp. 31–135),
  **PARTE ALFABÉTICA** (pp. 137–777) and the end marker **BIBLIOGRAFIA**.
- **Systematic part (hierarchy):** the number of asterisks gives the depth. `*` is a
  class (16), `**` is a subclass (76; the introduction says 77, but its own list has
  76), and `***` to `*******` are terms. Classes 14–16 have no subclasses, so their
  terms sit at level 1. Leading section numbers are ignored. `[bracketed]` lines are
  *indicadores de classificação* (guide terms such as `[Cadeira: segundo a forma]`).
  They aren't terms and are skipped, including wrapped ones.
- **Alphabetical part (enrichment and cross-check):** a headword is any line at the
  left margin. Fields start with `NA:`, `NE:`, `NI:`, `UP:`, `TG:`, `TE:`, `TA:`,
  `SCA:` or `USE:`. Other lines continue the current field. In list fields
  (UP/TG/TE/TA/USE) each line is one item. Notes are joined with spaces. The book has
  no syllable hyphenation, so a line ending in `-` is a compound (`guarda-` +
  `chuva`) and is glued without a space.

## How the two parts are merged

1. Every systematic term is matched to its alphabetical entry. The parser tries these
   in order:
   - the exact normalised label;
   - a qualified homonym, when TG disagrees (`Cravo` under *Prego* becomes
     `Cravo (prego)`, not the instrument);
   - the USE target, when the systematic part printed the non-preferred form
     (`Capinadeira` becomes `Roçadeira`);
   - a spelling variant whose TG agrees (hyphen/space, plural, small typos).

   The output label is the alphabetical headword. Two exceptions apply: for
   hyphen/space variants the compound form wins, and `LABEL_OVERRIDES` fixes typos in
   the alphabetical part.
2. **Asterisk typos are repaired** only when two independent signals agree: the TG in
   the alphabetical part, and the label's indentation in the systematic part. This
   covers 36 cases, for example *Tranqueta* under *Tranca*, the lamps on p. 43, and
   the sculpture block on p. 95.
3. Repeated siblings are merged. These are a term listed under two facets of the same
   parent, or two spellings of one term.
4. Preferred headwords missing from the systematic part are attached under their TG.
   There are 3: *Molde (escultura)*, *Selo heráldico* and *Telégrafo de manobra*.
5. `scope_note` is NA. When NA is missing, it falls back to NE (20 entries carry their
   definition in NE). `alt_labels` is UP ∪ every `X USE term`. A non-preferred term with
   two USE targets becomes an alt label of both.
6. `id` is the slug of the full path (accents stripped, `--` between levels). It is
   stable across rebuilds of the same PDF. A term in several places (polyhierarchy,
   e.g. *Facão*, *Sino*) appears once per place with different ids and the same label.
7. Cross-checks:
   - systematic parent vs TG;
   - SCA (class/subclass code) vs the hierarchy;
   - UP vs reverse USE;
   - dangling USE targets;
   - duplicate ids and orphans. The build aborts on these.

## Known limitations

- The schema has no field for **guide terms**, so they're dropped. The facet grouping
  (`[X: segundo a forma]`) is lost.
- **NE/NI notes** are not exported, except NE as a fallback definition. TG/TE/TA are
  not exported either. TE is implied by `parent`, but the associative relations (TA)
  are lost.
- **23 TG mismatches stay as printed** because the indentation does not support moving
  them. Some are real polyhierarchy (*Colchão* and *Travesseiro* also under
  *Mobiliário*; the drum-kit parts under *Bateria*). Others are probably source
  inconsistencies (the "rural" credit notes on p. 120, *Castiçal de altar*). See the
  report.
- 56 distinct terms (57 occurrences) have no definition in the source: they have neither NA nor NE (e.g.
  *Xícara de chá*, *Relógio de pulso*, *Punhal*), and classes/subclasses such as
  *Acessórios comuns a diversos tipos de veículos* have none either.
- These cases are hard-coded in `build.py`:
  - `SYSTEMATIC_SKIP`: `*** Acessórios` on p. 102 is a guide heading typed as a term.
  - `LABEL_OVERRIDES`: 2 typos.
  - Empty placeholder lines (`........`, `-----`) are skipped automatically.
- The parser is tied to this PDF's layout. A new edition will need its assumptions
  re-checked. Start with the stats and the `--report` output.
