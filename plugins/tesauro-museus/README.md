# Tesauro Museus

WordPress plugin that imports the *Tesauro de Objetos do Patrimônio Cultural nos Museus Brasileiros*
(Helena Dodd Ferrez, 2016) into Tainacan taxonomies. It knows nothing about this site: any Tainacan
install can use it, so it can be released on its own later.

| Path | What |
|---|---|
| `tesauro-museus.php` | The plugin: `TesauroMuseus\import()` and the `wp tesauro import` command. |
| `tools/build.py` | Compiles the thesaurus PDF into `data/tesauro-ferrez-2016.json`. See [tools/README.md](tools/README.md). |
| `data/` | The compiled thesaurus. **Git-ignored**: the source only allows partial reproduction, so build it locally and copy it to the server until permission to redistribute is clarified. |

## Import

```bash
wp plugin activate tesauro-museus
# Classes and subclasses only (16 + 76 terms), e.g. for an INBCM "Classificação" field:
wp tesauro import "Classificação (Tesauro de Objetos)" --max-level=1
# Everything (3,476 entries, 3,412 distinct terms), e.g. for "Denominação":
wp tesauro import "Denominação (Tesauro de Objetos)"
```

The target taxonomy must already exist in Tainacan. Re-running is safe: terms are matched by their stable
thesaurus id (term meta `tesauro_id`) and updated in place. Each term gets the thesaurus definition as its
description and its non-preferred synonyms as `tesauro_alt_label` term meta (e.g. *Escudela* → *Tigela*),
for lookups.

## Data

Each term in the JSON: `id` (stable slug of the full path), `label`, `parent`, `level` (0 = class),
`path`, `alt_labels`, `scope_note`. Terms in several places in the hierarchy (polyhierarchy, e.g. *Sino*)
appear once per place, with different ids.

Source: Ferrez, Helena Dodd. *Tesauro de Objetos do Patrimônio Cultural nos Museus Brasileiros*. 2016.
