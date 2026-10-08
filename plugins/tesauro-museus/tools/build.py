#!/usr/bin/env python3
"""Extract the "Tesauro de Objetos do Patrimônio Cultural nos Museus Brasileiros"
(Helena Dodd Ferrez, 2016) from its PDF into structured JSON.

Requirements
    Python >= 3.9 and exactly one third-party package:
        pip install pdfplumber==0.11.10
    (pdfplumber pulls in pdfminer.six + cryptography; the PDF is AES-encrypted
    with an empty user password, which pdfminer handles transparently.)

Usage
    python3 tools/build.py path/to/tesauro.pdf [-o data/tesauro-ferrez-2016.json]
                           [--report report.txt] [--strict]

The output is deterministic: the same PDF always yields byte-identical JSON
(document order for terms, sorted keys, sorted alt_labels, no timestamps).

How it works (see tools/README.md for details)
    1. PARTE SISTEMÁTICA gives the hierarchy: every preferred term is printed
       with 1..7 asterisks giving its depth (* class, ** subclass, *** and
       deeper: terms). Lines in [brackets] are "indicadores de classificação"
       (guide terms / node labels), which are not terms and are skipped.
    2. PARTE ALFABÉTICA gives, per headword, NA (definition -> scope_note),
       NE/NI, UP (used-for -> alt_labels), USE (non-preferred -> its target),
       TG/TE/TA and SCA.
    3. Each systematic term is matched to its alphabetical entry (exact, then
       spelling variants), asterisk typos are repaired where TG and label
       indentation agree, preferred headwords missing from the systematic
       part are attached under their TG, and everything left over is
       reported (--report).
"""

import argparse
import hashlib
import json
import os
import re
import sys
import unicodedata
from collections import OrderedDict, defaultdict

try:
    import pdfplumber
except ImportError:  # pragma: no cover
    sys.exit("pdfplumber is required: pip install pdfplumber==0.11.10")

SOURCE = {
    "title": "Tesauro de Objetos do Patrimônio Cultural nos Museus Brasileiros",
    "author": "Helena Dodd Ferrez",
    "year": 2016,
    "url": "https://cultura.rs.gov.br/upload/arquivos/carga20190600/"
           "17110012-tesauro-de-objetos-do-patrimonio-cultural-dos-museus-brasileiros.pdf",
    "license_note": "Reprodução parcial permitida desde que citada a fonte.",
}

RUNNING_HEADER = "Tesauro de Objetos do Patrimônio Cultural nos Museus Brasileiros"
SYS_HEADING = "PARTE SISTEMÁTICA"
ALPHA_HEADING = "PARTE ALFABÉTICA"
END_HEADING = "BIBLIOGRAFIA"
FIELD_TAGS = ("NA", "NE", "NI", "UP", "TG", "TE", "TA", "SCA", "USE")
FIELD_RE = re.compile(r"^(%s)\s*:\s*(.*)$" % "|".join(FIELD_TAGS))
LIST_FIELDS = ("UP", "TG", "TE", "TA", "USE")

# The PDF this extractor was written against (803 pages, 7 225 403 bytes).
# A different file still builds, but layout assumptions may no longer hold.
EXPECTED_SHA256 = "5eaef0a2a119351b63088084b3404bbcf08380da9ebc5dcf1cc3c61f1670b570"

# Lines printed with asterisks that are not terms. ("Acessórios", p.102) sits
# under [Equipamento de computador, periféricos e acessórios] with no entry in
# the alphabetical part: it is a guide heading typed as a term.
SYSTEMATIC_SKIP = {("Acessórios", 102)}

# When the two parts spell a term differently and the alphabetical headword is
# the misspelt one, force the label here (alphabetical spelling -> output).
LABEL_OVERRIDES = {
    "Projetor de diapostivos": "Projetor de diapositivos",
    "Espátula farmacéutica": "Espátula farmacêutica",
}


# --------------------------------------------------------------------------
# PDF -> lines
# --------------------------------------------------------------------------

class Line:
    """One visual line. x0 = left edge of the first glyph; lx = left edge of
    the first glyph that is not part of a section number or asterisks (the
    indentation of the label itself in the systematic part)."""
    __slots__ = ("page", "top", "x0", "lx", "text", "fonts")

    def __init__(self, page, top, x0, lx, text, fonts):
        self.page, self.top, self.x0, self.lx, self.text, self.fonts = page, top, x0, lx, text, fonts

    def __repr__(self):
        return "Line(p%d %.0f x%.1f %r)" % (self.page, self.top, self.x0, self.text)


def _chars_to_text(chars):
    """Join chars of one visual line, honouring explicit space glyphs.

    The PDF ("Microsoft: Print To PDF") emits real space glyphs. Some of them
    overlap the following glyph (e.g. "***** *" or "5.1 0"): those are layout
    artefacts, not word breaks, so a space is dropped when the next glyph
    starts inside the first half of the space.
    """
    chars = sorted(chars, key=lambda c: (c["x0"], c["top"]))
    out = []
    prev_x1 = None
    pending_space = None
    for c in chars:
        t = c["text"]
        if t.isspace():
            if pending_space is None:
                pending_space = c
            continue
        if pending_space is not None:
            mid = pending_space["x0"] + (pending_space["x1"] - pending_space["x0"]) / 2.0
            if c["x0"] >= mid and out:
                out.append(" ")
            pending_space = None
        elif prev_x1 is not None and c["x0"] - prev_x1 > c["size"] * 0.3:
            out.append(" ")  # visible gap without a space glyph
        out.append(t)
        prev_x1 = c["x1"]
    return re.sub(r"\s+", " ", "".join(out)).strip()


def read_lines(pdf_path):
    lines = []
    with pdfplumber.open(pdf_path) as pdf:
        for pno, page in enumerate(pdf.pages, start=1):
            width = float(page.width)
            # glyphs outside the page box are invisible leftovers (p.74 has a
            # second "****" at x=-21 on the "Sargento" line)
            chars = [c for c in page.chars
                     if c.get("text") and c["x0"] >= 0 and c["x1"] <= width + 1]
            chars.sort(key=lambda c: (c["top"], c["x0"]))
            groups = []
            for c in chars:
                if groups and abs(c["top"] - groups[-1][0]) <= 3.0:
                    groups[-1][1].append(c)
                else:
                    groups.append([c["top"], [c]])
            height = float(page.height)
            for top, cs in groups:
                text = _chars_to_text(cs)
                if not text:
                    continue
                if text == RUNNING_HEADER:
                    continue
                if text.isdigit() and top > height * 0.85:
                    continue  # page number footer
                ink = [c for c in cs if not c["text"].isspace()]
                fonts = sorted({c["fontname"] for c in ink})
                x0 = min(c["x0"] for c in ink)
                lab = sorted((c for c in ink if c["text"] not in "0123456789.*"),
                             key=lambda c: c["x0"])
                lx = lab[0]["x0"] if lab else x0
                lines.append(Line(pno, round(top, 1), round(x0, 1), round(lx, 1), text, fonts))
    return lines


def split_sections(lines):
    """Return (systematic_lines, alphabetical_lines).

    A section starts at the first page whose first body line is exactly the
    section heading and which has real content (the divider pages that only
    carry the big title are skipped).
    """
    by_page = OrderedDict()
    for ln in lines:
        by_page.setdefault(ln.page, []).append(ln)

    def find(heading, after=0):
        for p, ls in by_page.items():
            if p > after and ls and ls[0].text == heading and len(ls) > 3:
                return p
        raise SystemExit("section heading %r not found" % heading)

    p_sys = find(SYS_HEADING)
    p_alpha = find(ALPHA_HEADING, p_sys)
    p_end = find(END_HEADING, p_alpha)
    # the divider page before PARTE ALFABÉTICA only carries the big title
    sys_lines = [l for l in lines if p_sys <= l.page < p_alpha and l.text != ALPHA_HEADING]
    alpha_lines = [l for l in lines if p_alpha <= l.page < p_end]
    # drop the heading line itself
    sys_lines = [l for l in sys_lines if not (l.page == p_sys and l.text == SYS_HEADING)]
    alpha_lines = [l for l in alpha_lines if not (l.page == p_alpha and l.text == ALPHA_HEADING)]
    return sys_lines, alpha_lines, (p_sys, p_alpha, p_end)


# --------------------------------------------------------------------------
# Normalisation helpers
# --------------------------------------------------------------------------

def nfc(s):
    s = unicodedata.normalize("NFC", s)
    s = s.replace("’", "'").replace("‘", "'").replace(" ", " ")
    s = s.replace("–", "-").replace("‐", "-").replace("‑", "-")
    return re.sub(r"\s+", " ", s).strip()


def clean_label(s):
    s = nfc(s)
    s = re.sub(r"(\w)\(", r"\1 (", s)      # "Molde(escultura)"
    s = re.sub(r"\(\s+", "(", s)
    s = re.sub(r"\s+\)", ")", s)
    return s


def strip_accents(s):
    return "".join(ch for ch in unicodedata.normalize("NFKD", s)
                   if not unicodedata.combining(ch))


def key(s):
    """Matching key: NFC, casefold, whitespace/punctuation-spacing normalised."""
    s = nfc(s).casefold()
    s = re.sub(r"\s*\(\s*", " (", s)
    s = re.sub(r"\s*\)", ")", s)
    s = re.sub(r"\s*/\s*", "/", s)
    s = re.sub(r"\s*-\s*", "-", s)
    return s.strip()


def loose_key(s):
    return strip_accents(key(s))


def slug(s):
    s = strip_accents(nfc(s)).lower()
    s = re.sub(r"[^a-z0-9]+", "-", s).strip("-")
    return s


def join_text(parts):
    """Join wrapped lines of a note. The source has no syllable hyphenation:
    a line ending in '-' is a compound word (guarda-chuva) split at its hyphen,
    so it is glued without a space."""
    out = ""
    for p in parts:
        p = p.strip()
        if not p:
            continue
        if not out:
            out = p
        elif re.search(r"\w-$", out):
            out += p
        else:
            out += " " + p
    return nfc(out) or None


# --------------------------------------------------------------------------
# PARTE SISTEMÁTICA
# --------------------------------------------------------------------------

NUM_TOKEN = re.compile(r"^\d+(\.\d*)*\.?$")


def parse_systematic(lines, warnings):
    """Return list of nodes in document order: dict(label, depth, page)."""
    nodes = []
    guide_open = None  # accumulating a wrapped [guide term]
    guides = 0
    for ln in lines:
        toks = ln.text.split(" ")
        i = 0
        # leading section number(s): "1.1", "5.1 0", "2.2.2 3", "4.4. 4"
        while i < len(toks) and NUM_TOKEN.match(toks[i]):
            i += 1
        stars = ""
        while i < len(toks) and re.fullmatch(r"\*+", toks[i]):
            stars += toks[i]
            i += 1
        rest = " ".join(toks[i:]).strip()

        if guide_open is not None:
            if not stars and not rest.startswith("[") and i == 0:
                guide_open += " " + rest
                if rest.endswith("]"):
                    guide_open = None
                continue
            warnings.append("unterminated guide term before p%d: %r" % (ln.page, guide_open))
            guide_open = None

        if rest.startswith("["):
            guides += 1
            if not rest.rstrip().endswith("]"):
                guide_open = rest
            continue  # indicador de classificação, not a term
        if not stars:
            warnings.append("systematic: unrecognised line p%d: %r" % (ln.page, ln.text))
            continue
        if not rest:
            warnings.append("systematic: asterisks without label p%d: %r" % (ln.page, ln.text))
            continue
        if not re.search(r"\w", rest):
            # "........" / "---------": empty slots left in the source
            warnings.append("systematic: placeholder skipped p%d: %r" % (ln.page, ln.text))
            continue
        if (rest, ln.page) in SYSTEMATIC_SKIP:
            warnings.append("systematic: skipped stray line p%d: %r" % (ln.page, ln.text))
            continue
        depth = len(stars)
        nodes.append({"label": clean_label(rest), "depth": depth, "page": ln.page, "lx": ln.lx})
    return nodes, guides


def build_tree(nodes, warnings):
    """Attach parents with a depth stack."""
    stack = []
    for idx, n in enumerate(nodes):
        while stack and nodes[stack[-1]]["depth"] >= n["depth"]:
            stack.pop()
        if n["depth"] == 1:
            if stack:
                warnings.append("class with non-empty stack: %r" % n["label"])
            n["parent"] = None
        else:
            if not stack:
                raise SystemExit("orphan at p%d: %r" % (n["page"], n["label"]))
            n["parent"] = stack[-1]
            gap = n["depth"] - nodes[stack[-1]]["depth"]
            # classes 14-16 have no subclasses, so *** directly under * is normal
            if gap > 1 and not (nodes[stack[-1]]["depth"] == 1 and n["depth"] == 3):
                warnings.append("systematic: depth jump %d->%d at p%d: %r under %r" % (
                    nodes[stack[-1]]["depth"], n["depth"], n["page"], n["label"],
                    nodes[stack[-1]]["label"]))
        stack.append(idx)
    return dedupe_siblings(nodes, warnings)


def dedupe_siblings(nodes, warnings):
    """The same term can be listed twice under one parent when it belongs to
    two facets ([Cadeira: segundo a forma] and [... segundo o mecanismo]).
    Without guide terms these are the same concept: keep the first one and
    re-attach any children of the repeat to it."""
    canon = {}
    seen = {}
    kept = []
    for idx, n in enumerate(nodes):
        p = canon.get(n["parent"], n["parent"]) if n["parent"] is not None else None
        k = (p, slug(n["label"]))  # slug: "Cruz-relicário" == "Cruz relicário"
        if k in seen:
            canon[idx] = seen[k]
            warnings.append("systematic: repeated sibling %r under %r (p%d), merged into %r" % (
                n["label"], nodes[p]["label"] if p is not None else None, n["page"],
                nodes[seen[k]]["label"]))
            continue
        seen[k] = idx
        canon[idx] = idx
        n["parent"] = p
        kept.append(idx)
    remap = {old: new for new, old in enumerate(kept)}
    out = []
    for old in kept:
        n = nodes[old]
        n["parent"] = remap[n["parent"]] if n["parent"] is not None else None
        out.append(n)
    return out


# --------------------------------------------------------------------------
# PARTE ALFABÉTICA
# --------------------------------------------------------------------------

def parse_alphabetical(lines, warnings):
    margin = min(l.x0 for l in lines)
    entries = []
    cur = None
    field = None
    for ln in lines:
        if ln.x0 < margin + 5:
            # headword at the left margin
            cur = {"label": clean_label(ln.text), "page": ln.page, "fields": OrderedDict()}
            entries.append(cur)
            field = None
            continue
        if cur is None:
            warnings.append("alphabetical: text before first headword p%d: %r" % (ln.page, ln.text))
            continue
        m = FIELD_RE.match(ln.text)
        if m:
            field = m.group(1)
            if field in cur["fields"]:
                warnings.append("alphabetical: repeated field %s in %r" % (field, cur["label"]))
            cur["fields"].setdefault(field, [])
            if m.group(2):
                cur["fields"][field].append(m.group(2))
            continue
        if field is None:
            warnings.append("alphabetical: orphan line p%d: %r" % (ln.page, ln.text))
            continue
        cur["fields"][field].append(ln.text)

    for e in entries:
        f = e["fields"]
        e["NA"] = join_text(f.get("NA", []))
        e["NE"] = join_text(f.get("NE", []))
        e["NI"] = join_text(f.get("NI", []))
        for t in LIST_FIELDS:
            e[t] = [clean_label(x) for x in f.get(t, []) if nfc(x)]
        sca = join_text(f.get("SCA", [])) or ""
        m = re.match(r"^(\d+)\s+(.*)$", sca)
        e["SCA_code"], e["SCA_name"] = (m.group(1), m.group(2)) if m else (None, sca or None)
    return entries


def repair_wrapped_items(entries, known_keys, warnings):
    """A list item (UP/TE/TA/TG/USE) that wrapped onto two lines appears as
    two items; merge consecutive items when the merge is a known headword
    and the pieces are not."""
    for e in entries:
        for t in LIST_FIELDS:
            items = e[t]
            out = []
            i = 0
            while i < len(items):
                a = items[i]
                if i + 1 < len(items):
                    b = items[i + 1]
                    for joined in (a + " " + b, a + b):
                        if key(joined) in known_keys and (key(a) not in known_keys
                                                          or key(b) not in known_keys):
                            warnings.append("alphabetical: merged wrapped %s item in %r: %r"
                                            % (t, e["label"], joined))
                            out.append(nfc(joined))
                            i += 2
                            break
                    else:
                        out.append(a)
                        i += 1
                    continue
                out.append(a)
                i += 1
            e[t] = out


# --------------------------------------------------------------------------
# Merge
# --------------------------------------------------------------------------

def base_label(s):
    """Label without a trailing qualifier: 'Cuspideira (dentista)' -> 'Cuspideira'."""
    return re.sub(r"\s*\([^()]*\)$", "", s).strip()


def similarity(a, b):
    import difflib
    return difflib.SequenceMatcher(None, loose_key(a), loose_key(b)).ratio()


def choose_label(sys_label, alpha_label):
    """Pick the output spelling for a term matched across the two parts."""
    if alpha_label in LABEL_OVERRIDES:
        return LABEL_OVERRIDES[alpha_label]
    if key(sys_label) == key(alpha_label):
        return alpha_label  # same term; alphabetical part has sentence case
    if slug(sys_label) == slug(alpha_label):
        # hyphen vs space ("Sofá-cama" / "Sofá cama"): keep the compound form
        return max((sys_label, alpha_label), key=lambda s: (s.count("-"), s == alpha_label))
    return alpha_label


def tg_agrees(entry, parent):
    if parent is None:
        return not entry["TG"]
    ps = {slug(parent["label"]), slug(parent.get("out_label") or parent["label"])}
    return any(slug(t) in ps for t in entry["TG"])


def match_nodes(nodes, alpha_pref, alpha_np, warnings):
    """Attach an alphabetical entry to every systematic node.

    1. exact match on the normalised label -- unless TG disagrees with the
       systematic parent and a qualified homonym agrees ("Cravo" under Prego
       is "Cravo (prego)", not the harpsichord "Cravo");
    2. the systematic part sometimes prints the non-preferred form
       ("Capinadeira" for "Roçadeira"): follow USE when the target is not
       printed elsewhere;
    3. otherwise the best alphabetical preferred headword with: same slug
       (hyphen/space/accent variants), or same label once the alphabetical
       qualifier is dropped, or string similarity >= 0.8 -- the last two
       only when TG agrees with the systematic parent. Classes/subclasses
       accept similarity >= 0.75 without TG (their wording drifts between the
       Plano Geral, the systematic and the alphabetical parts).
    """
    by_base = defaultdict(list)
    for e in alpha_pref.values():
        if base_label(e["label"]) != e["label"]:
            by_base[key(base_label(e["label"]))].append(e)
    printed = {key(n["label"]) for n in nodes}
    exact_used = set()
    for n in nodes:
        n["entry"] = alpha_pref.get(key(n["label"]))
        if n["entry"] is not None:
            exact_used.add(key(n["entry"]["label"]))
    cands = sorted(alpha_pref.values(), key=lambda e: (loose_key(e["label"]), e["label"]))

    for n in nodes:  # document order: parents are resolved before children
        parent = nodes[n["parent"]] if n["parent"] is not None else None
        e = n["entry"]
        how = None
        if e is not None and parent is not None and e["TG"] and not tg_agrees(e, parent):
            alt = [q for q in by_base.get(key(n["label"]), []) if tg_agrees(q, parent)]
            if len(alt) == 1:
                e, how = alt[0], "qualified homonym"
        if e is None:
            np_ = alpha_np.get(key(n["label"]))
            if np_ is not None and len(np_["USE"]) == 1:
                tgt = alpha_pref.get(key(np_["USE"][0]))
                if tgt is not None and key(tgt["label"]) not in printed:
                    e, how = tgt, "USE of non-preferred form"
        if e is None:
            best, best_score = None, 0.0
            for q in cands:
                tg_ok = tg_agrees(q, parent)
                if slug(q["label"]) == slug(n["label"]):
                    score = 1.0
                elif key(base_label(q["label"])) == key(n["label"]) and tg_ok:
                    score = 0.99
                else:
                    r = similarity(n["label"], q["label"])
                    need = 0.75 if n["depth"] <= 2 else 0.8
                    score = r if (r >= need and (tg_ok or n["depth"] <= 2)) else 0.0
                if score and key(q["label"]) not in exact_used:
                    score += 0.001  # prefer headwords no other node claimed
                if score > best_score:
                    best, best_score = q, score
            if best is not None:
                e, how = best, "fuzzy %.2f" % min(best_score, 1.0)
        n["entry"] = e
        if how:
            warnings.append("match: systematic %r (p%d) -> alphabetical %r (p%d) [%s]" % (
                n["label"], n["page"], e["label"], e["page"], how))
        if e is not None:
            n["out_label"] = choose_label(n["label"], e["label"])
        elif n["depth"] == 1 and n["label"].isupper():
            n["out_label"] = n["label"][:1] + n["label"][1:].lower()
            warnings.append("unmatched: class %r not in alphabetical part" % n["label"])
        else:
            n["out_label"] = n["label"]
            warnings.append("unmatched: systematic term %r (p%d) has no alphabetical entry"
                            % (n["label"], n["page"]))


def fix_parents(nodes, warnings):
    """Repair asterisk typos in the systematic part.

    A node is re-parented only when two independent signals agree against
    the printed asterisks:
      * its TG in the alphabetical part names the new parent, and
      * the label's indentation in the systematic part matches the new
        position: moving up (to an ancestor of the current parent) requires
        the label not to be indented deeper than the current parent; moving
        down (under a preceding sibling) requires it to be indented deeper
        than that sibling.
    Descendants move with the node. Returns (nodes, number of fixes).
    """
    children = defaultdict(list)
    for i, n in enumerate(nodes):
        if n["parent"] is not None:
            children[n["parent"]].append(i)
    for n in nodes:
        n["orig_parent"] = n["parent"]
    fixes = 0
    for i, n in enumerate(nodes):
        e = n["entry"]
        if e is None or n["parent"] is None or not e["TG"] or n.get("lx") is None:
            continue
        p = nodes[n["parent"]]
        if tg_agrees(e, p):
            continue
        tg = {slug(t) for t in e["TG"]}
        new = None
        # down: the nearest preceding sibling named by TG (a sibling as
        # printed, which may itself have been moved already, e.g. Letreiro)
        for j in range(i - 1, -1, -1):
            s = nodes[j]
            if s.get("removed") or s["orig_parent"] != n["orig_parent"]:
                continue
            if slug(s["out_label"]) in tg or slug(s["label"]) in tg:
                if s.get("lx") is not None and n["lx"] > s["lx"] + 2:
                    new = j
                break
        # up: an ancestor of the current parent
        if new is None and p.get("lx") is not None and n["lx"] <= p["lx"] + 2:
            a = p["parent"]
            while a is not None:
                if slug(nodes[a]["out_label"]) in tg or slug(nodes[a]["label"]) in tg:
                    new = a
                    break
                a = nodes[a]["parent"]
        if new is None:
            continue
        children[n["parent"]].remove(i)
        fixes += 1
        twin = [j for j in children[new] if slug(nodes[j]["out_label"]) == slug(n["out_label"])]
        if twin:
            # already listed under the new parent: merge into that occurrence
            merge_into(nodes, children, i, twin[0])
            warnings.append("fixed: %r (p%d) under %r merged into its twin under %r "
                            "(TG + indentation)" % (n["out_label"], n["page"], p["out_label"],
                                                    nodes[new]["out_label"]))
            continue
        warnings.append("fixed: %r (p%d) moved from %r to %r (TG + indentation)" % (
            n["out_label"], n["page"], p["out_label"], nodes[new]["out_label"]))
        n["parent"] = new
        children[new].append(i)
        children[new].sort()
    return compact(nodes), fixes


def merge_into(nodes, children, src, dst):
    """Merge subtree `src` into node `dst` (same concept), recursively."""
    for c in list(children[src]):
        same = [j for j in children[dst] if slug(nodes[j]["out_label"]) == slug(nodes[c]["out_label"])]
        if same:
            merge_into(nodes, children, c, same[0])
        else:
            nodes[c]["parent"] = dst
            children[dst] = sorted(children[dst] + [c])
    children[src] = []
    nodes[src]["removed"] = True


def merge_siblings(nodes, warnings):
    """After relabelling (matching) and re-parenting, two siblings can turn
    out to be the same term (e.g. 'Calibrador (pneus)' and 'Calibrador de
    pneus' under the same subclass): merge them."""
    children = defaultdict(list)
    roots = []
    for i, n in enumerate(nodes):
        (roots if n["parent"] is None else children[n["parent"]]).append(i)
    for group in [roots] + [children[i] for i in range(len(nodes))]:
        first = {}
        for c in list(group):
            if nodes[c].get("removed"):
                continue
            k = slug(nodes[c]["out_label"])
            if k in first:
                warnings.append("merged: repeated sibling %r (p%d) into %r (p%d)" % (
                    nodes[c]["label"], nodes[c]["page"], nodes[first[k]]["out_label"],
                    nodes[first[k]]["page"]))
                merge_into(nodes, children, c, first[k])
            else:
                first[k] = c
    return compact(nodes)


def compact(nodes):
    """Drop nodes flagged 'removed' and renumber parent indices."""
    kept = [i for i, n in enumerate(nodes) if not n.get("removed")]
    remap = {old: new for new, old in enumerate(kept)}
    out = []
    for old in kept:
        n = nodes[old]
        if n["parent"] is not None:
            n["parent"] = remap[n["parent"]]
        out.append(n)
    return out


def attach_missing(nodes, entries, warnings):
    """Preferred headwords that the systematic part forgot are attached under
    every occurrence of their TG. Returns the number of entries attached."""
    used = {key(n["entry"]["label"]) for n in nodes if n["entry"] is not None}
    by_label = defaultdict(list)
    for i, n in enumerate(nodes):
        by_label[key(n["out_label"])].append(i)
        by_label[key(n["label"])].append(i)
    attached = 0
    missing = sorted((e for e in entries if not e["USE"] and key(e["label"]) not in used),
                     key=lambda e: (loose_key(e["label"]), e["label"]))
    for e in missing:
        parents = sorted({i for t in e["TG"] for i in by_label.get(key(t), [])})
        if not parents:
            warnings.append("unmatched: preferred headword %r (p%d, TG %r) absent from "
                            "systematic part and TG not found; dropped" % (e["label"], e["page"], e["TG"]))
            continue
        attached += 1
        label = LABEL_OVERRIDES.get(e["label"], e["label"])
        for p in parents:
            nodes.append({"label": label, "out_label": label, "entry": e,
                          "depth": nodes[p]["depth"] + 1, "page": e["page"],
                          "parent": p, "added": True})
        warnings.append("added: %r (alphabetical p%d) under TG %r, absent from systematic part"
                        % (e["label"], e["page"], e["TG"]))
    return attached


def dfs_order(nodes):
    children = defaultdict(list)
    roots = []
    for i, n in enumerate(nodes):
        (roots if n["parent"] is None else children[n["parent"]]).append(i)
    order = []
    stack = list(reversed(roots))
    while stack:
        i = stack.pop()
        order.append(i)
        stack.extend(reversed(children[i]))
    return order


def build(pdf_path, out_path, report_path=None, strict=False):
    warnings = []
    digest = sha256(pdf_path)
    if digest != EXPECTED_SHA256:
        warnings.append("input: PDF sha256 %s differs from the expected %s" % (digest, EXPECTED_SHA256))
        print("warning: unexpected PDF (sha256 %s); check the report" % digest, file=sys.stderr)
    lines = read_lines(pdf_path)
    sys_lines, alpha_lines, pages = split_sections(lines)

    nodes, n_guides = parse_systematic(sys_lines, warnings)
    nodes = build_tree(nodes, warnings)
    entries = parse_alphabetical(alpha_lines, warnings)

    # index alphabetical part
    alpha = {}
    for e in entries:
        k = key(e["label"])
        if k in alpha:
            warnings.append("alphabetical: duplicate headword %r (p%d and p%d)" % (
                e["label"], alpha[k]["page"], e["page"]))
            if len(e["fields"]) <= len(alpha[k]["fields"]):
                continue
        alpha[k] = e
    repair_wrapped_items(entries, set(alpha), warnings)
    alpha_pref = {k: e for k, e in alpha.items() if not e["USE"]}
    alpha_np = {k: e for k, e in alpha.items() if e["USE"]}
    for e in entries:
        if e["USE"] and (e["NA"] or e["TG"] or e["TE"]):
            warnings.append("alphabetical: non-preferred %r also has NA/TG/TE (ignored)" % e["label"])
        if len(e["USE"]) > 1:
            warnings.append("alphabetical: %r has several USE targets %r (alt label of each)"
                            % (e["label"], e["USE"]))

    match_nodes(nodes, alpha_pref, alpha_np, warnings)
    nodes, n_fixed = fix_parents(nodes, warnings)
    nodes = merge_siblings(nodes, warnings)
    n_added = attach_missing(nodes, entries, warnings)

    # reverse USE map: target -> non-preferred labels
    use_rev = defaultdict(set)
    for e in entries:
        for tgt in e["USE"]:
            use_rev[key(LABEL_OVERRIDES.get(tgt, tgt))].add(e["label"])

    # paths and ids
    for n in nodes:
        path = [n["out_label"]]
        p = n["parent"]
        while p is not None:
            path.append(nodes[p]["out_label"])
            p = nodes[p]["parent"]
        n["path"] = path[::-1]
        n["id"] = "--".join(slug(x) for x in n["path"])
    seen_ids = {}
    for i, n in enumerate(nodes):
        if n["id"] in seen_ids:
            raise SystemExit("duplicate id %s (p%d and p%d)" % (
                n["id"], nodes[seen_ids[n["id"]]]["page"], n["page"]))
        seen_ids[n["id"]] = i

    preferred_keys = {key(n["out_label"]) for n in nodes}
    entry_keys = {}
    for n in nodes:
        if n["entry"] is not None:
            entry_keys[key(n["entry"]["label"])] = n["out_label"]

    terms = []
    alt_count_checked = set()
    for i in dfs_order(nodes):
        n = nodes[i]
        e = n["entry"]
        alts = set(use_rev.get(key(n["out_label"]), ()))
        if e is not None:
            alts |= use_rev.get(key(e["label"]), set())
            up = set(e["UP"])
            if key(e["label"]) not in alt_count_checked:
                alt_count_checked.add(key(e["label"]))
                for u in sorted(up - alts):
                    ue = alpha.get(key(u))
                    if ue is None:
                        warnings.append("crosscheck: UP %r of %r has no headword (kept)" % (u, e["label"]))
                    elif not ue["USE"]:
                        warnings.append("crosscheck: UP %r of %r is a preferred headword (kept)"
                                        % (u, e["label"]))
                    else:
                        warnings.append("crosscheck: UP %r of %r, but %r says USE %r (kept)" % (
                            u, e["label"], u, ue["USE"]))
                for u in sorted(alts - up):
                    warnings.append("crosscheck: %r USE %r, but target lacks UP (kept)" % (u, e["label"]))
            alts |= up
        alts = {a for a in alts if key(a) != key(n["out_label"])}
        for a in sorted(alts):
            if key(a) in preferred_keys:
                warnings.append("crosscheck: alt label %r of %r is also a preferred term" % (
                    a, n["out_label"]))
        terms.append({
            "id": n["id"],
            "label": n["out_label"],
            "parent": nodes[n["parent"]]["id"] if n["parent"] is not None else None,
            "level": len(n["path"]) - 1,
            "path": n["path"],
            "alt_labels": sorted(alts, key=lambda s: (loose_key(s), s)),
            # NA is the definition; ~20 entries carry their definition in NE
            # instead ("Poncho", "Torso", "Calibrador (pneus)"), so fall back.
            "scope_note": (e["NA"] or e["NE"]) if e is not None else None,
        })

    # crosscheck TG against systematic parent
    tg_mismatch = []
    for n in nodes:
        e = n["entry"]
        if e is None or n["parent"] is None or n.get("added"):
            continue
        par = nodes[n["parent"]]
        if e["TG"] and not tg_agrees(e, par):
            tg_mismatch.append(n)
            warnings.append("crosscheck: TG mismatch %r (p%d): systematic parent %r, TG %r" % (
                n["out_label"], n["page"], par["out_label"], e["TG"]))
        elif not e["TG"]:
            warnings.append("crosscheck: %r has no TG in alphabetical part" % n["out_label"])

    # crosscheck SCA (class/subclass code, e.g. "0206") of every term against
    # where the hierarchy put it. Classes and subclasses are numbered by order
    # (the printed numbers have typos: "1.3" for 11.3, "5.1 0" for 5.10).
    code = {}
    ncls = 0
    nsub = defaultdict(int)
    for i in dfs_order(nodes):
        n = nodes[i]
        if n["parent"] is None:
            ncls += 1
            code[i] = "%02d" % ncls
        elif n["depth"] == 2 and nodes[n["parent"]]["parent"] is None:
            nsub[n["parent"]] += 1
            code[i] = "%s%02d" % (code[n["parent"]], nsub[n["parent"]])
        else:
            code[i] = code[n["parent"]]
    where = defaultdict(set)
    for i, n in enumerate(nodes):
        if n["entry"] is not None and n["depth"] > 2:
            where[key(n["entry"]["label"])].add(code[i])
    sca_mismatch = 0
    for k, codes in sorted(where.items()):
        e = alpha[k]
        if e["SCA_code"] and e["SCA_code"] not in codes:
            sca_mismatch += 1
            warnings.append("crosscheck: SCA mismatch %r: alphabetical %s %s, hierarchy %s" % (
                e["label"], e["SCA_code"], e["SCA_name"], sorted(codes)))
        elif not e["SCA_code"]:
            warnings.append("crosscheck: %r has no SCA" % e["label"])

    dangling_use = []
    for e in entries:
        for tgt in e["USE"]:
            if key(LABEL_OVERRIDES.get(tgt, tgt)) not in preferred_keys and key(tgt) not in entry_keys:
                dangling_use.append((e["label"], tgt))
                warnings.append("unmatched: %r USE %r, target not in hierarchy (alt label lost)"
                                % (e["label"], tgt))

    # integrity
    ids = {t["id"] for t in terms}
    assert len(ids) == len(terms)
    for t in terms:
        assert t["parent"] is None or t["parent"] in ids, t

    doc = {
        "source": dict(SOURCE, pdf_sha256=digest),
        "generated_by": "tools/build.py",
        "terms": terms,
    }
    data = json.dumps(doc, ensure_ascii=False, indent=2, sort_keys=True) + "\n"
    os.makedirs(os.path.dirname(os.path.abspath(out_path)), exist_ok=True)
    with open(out_path, "w", encoding="utf-8", newline="\n") as fh:
        fh.write(data)

    stats = OrderedDict([
        ("pdf pages: systematic, alphabetical, bibliography", pages),
        ("classes", sum(1 for t in terms if t["level"] == 0)),
        ("subclasses", sum(1 for n in nodes if n["depth"] == 2)),
        ("terms below class/subclass", sum(1 for n in nodes if n["depth"] >= 3)),
        ("terms total (incl. classes/subclasses)", len(terms)),
        ("distinct preferred labels", len(preferred_keys)),
        ("polyhierarchy extra occurrences", len(terms) - len(preferred_keys)),
        ("added from alphabetical part via TG", n_added),
        ("parents fixed (TG + indentation)", n_fixed),
        ("guide terms (indicadores) skipped", n_guides),
        ("alphabetical headwords", len(entries)),
        ("alphabetical preferred / non-preferred", "%d / %d" % (
            sum(1 for e in entries if not e["USE"]), sum(1 for e in entries if e["USE"]))),
        ("alt labels (sum over terms)", sum(len(t["alt_labels"]) for t in terms)),
        ("distinct alt labels", len({a for t in terms for a in t["alt_labels"]})),
        ("terms with scope_note", sum(1 for t in terms if t["scope_note"])),
        ("  of which taken from NE (no NA)", sum(
            1 for n in nodes if n["entry"] is not None and not n["entry"]["NA"] and n["entry"]["NE"])),
        ("terms without alphabetical entry", sum(1 for n in nodes if n["entry"] is None)),
        ("TG mismatches (kept as printed)", len(tg_mismatch)),
        ("SCA mismatches", sca_mismatch),
        ("USE targets missing from hierarchy", len(dangling_use)),
        ("max level", max(t["level"] for t in terms)),
        ("warnings", len(warnings)),
    ])
    report = ["%s: %s" % kv for kv in stats.items()] + [""] + warnings
    if report_path:
        with open(report_path, "w", encoding="utf-8") as fh:
            fh.write("\n".join(report) + "\n")
    for k, v in stats.items():
        print("%-50s %s" % (k, v), file=sys.stderr)
    print("wrote %s (%d terms)" % (out_path, len(terms)), file=sys.stderr)
    if strict and (dangling_use or any(n["entry"] is None for n in nodes)):
        sys.exit(1)
    return doc


def sha256(path):
    h = hashlib.sha256()
    with open(path, "rb") as fh:
        for chunk in iter(lambda: fh.read(1 << 20), b""):
            h.update(chunk)
    return h.hexdigest()


def main():
    here = os.path.dirname(os.path.abspath(__file__))
    default_out = os.path.join(here, "..", "data", "tesauro-ferrez-2016.json")
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    ap.add_argument("pdf")
    ap.add_argument("-o", "--output", default=os.path.normpath(default_out))
    ap.add_argument("--report", help="write stats + all warnings to this file")
    ap.add_argument("--strict", action="store_true",
                    help="exit 1 if a systematic term or a USE target cannot be resolved")
    a = ap.parse_args()
    build(a.pdf, a.output, a.report, a.strict)


if __name__ == "__main__":
    main()
