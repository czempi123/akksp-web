# AKKSP – redesign webu aktrinec.cz

Pracovní repozitář pro redesign webu AKKSP advokátní kancelář, s.r.o. Obsahuje brand manual, moodboard, podklady a později i samotnou implementaci do WordPressu.

## Fáze

| Fáze | Obsah | Stav |
|---|---|---|
| 1 | Moodboard: 3 varianty každého prvku (paleta, písmo, tlačítka, navigace, hero, rozcestník, kontakt, čísla, oblasti, tým, reference, školení, aktuality, patička, pohyb) | hotovo, vybráno |
| 2 | Brand manual v1 (`brand-manual/`) + kompletní homepage (`site/`, desktop + mobil) | **hotovo, čeká na připomínky** |
| 3 | Implementace do WordPressu (staging → produkce) | čeká |

## Struktura

```
site/                   Homepage v1 (prototyp pro WordPress)
  index.html
  css/tokens.css        design tokeny --ak-*
  css/base.css          základ, typografie, plochy, mřížka
  css/components.css    tlačítka, „+ → ×", sklo, karty, štítky
  css/sections.css      sekce homepage + responzivita
  js/site.js            navigace, menu, accordion, CZ/PL, odkrývání
brand-manual/           Vizuální brand manual v1 (používá CSS ze site/)
tools/shoot.ps1         celostránkové snímky přes headless Edge
moodboard/              Moodboard fáze 1 (HTML/CSS/JS bez knihoven)
  index.html            otevřít v prohlížeči
  css/tokens.css        design tokeny (barvy z konceptu, písma, spacing, pohyb)
  css/variants.css      varianty A / B / C všech prvků
  js/moodboard.js       rozbalování „+ → ×", přepínač CZ/PL, výběr variant
assets/
  concept/              původní koncept homepage (zdroj barev a struktury)
  aktrinec/             logo, fotky týmu a interiéru staženy z aktrinec.cz
  brand/                logo AKKSP v barevných mutacích (SVG)
docs/
  01-zadani-a-vychodiska.md
  02-reference-styly.md
  03-brand-tokeny.md
  04-wordpress-implementace.md
  05-design-system-v1.md    jak byl výběr sjednocen, mapování kódu do WP
```

Stránky: `http://localhost:4173/site/` (homepage), `/brand-manual/`, `/moodboard/`. Parametr `?static` vypne animace (pro snímky).

## Jak otevřít moodboard

Moodboard načítá obrázky relativně, proto je nejlepší pustit malý lokální server z kořene repozitáře:

```powershell
python -m http.server 4173
```

Pak otevřít `http://localhost:4173/moodboard/`. U každého prvku klikněte na **Vybrat**, výběr se ukládá v prohlížeči a tlačítkem **Kopírovat výběr** (panel vpravo dole) ho pošlete dál.

## Tři designové směry

- **A – Editorial minimalism:** papír a inkoust, kontrastní serif Newsreader, vlasové linky, číslované rejstříky.
- **B – Contemporary institutional:** navy plochy, mřížka 3×3 převzatá z loga, IBM Plex Mono štítky, dělená tlačítka.
- **C – Glass & Soft:** glassmorfismus přes fotky kanceláře, decentní neumorfismus na krémové, Fraunces + Manrope.

Varianty jdou kombinovat napříč prvky (např. paleta B + písmo A + hero C).
