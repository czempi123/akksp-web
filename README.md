# AKKSP – redesign webu aktrinec.cz

Pracovní repozitář pro redesign webu AKKSP advokátní kancelář, s.r.o. Obsahuje brand manual, moodboard, podklady a později i samotnou implementaci do WordPressu.

## Fáze

| Fáze | Obsah | Stav |
|---|---|---|
| 1 | Moodboard: 3 varianty každého prvku (paleta, písmo, tlačítka, navigace, hero, rozcestník, kontakt, čísla, oblasti, tým, reference, školení, aktuality, patička, pohyb) | hotovo, vybráno |
| 2 | Brand manual v1 (`brand-manual/`) + kompletní homepage (`site/`, desktop + mobil) | hotovo |
| 3 | WordPress child téma pro Hello Elementor (`wordpress/akksp-child/`), 10 Elementor widgetů, lokální písma, otestováno v lokálním WP | hotovo |
| 3.1 | Finální design homepage podle 11 připomínek, PL mutace (`site/pl/`), vyhledávání (Ivory Search), **AKKSP Design System** (`design-system/` + artefakt) | hotovo |
| 3.2 | Druhá vlna připomínek: hlavička v jednom řádku bez burgeru, kontaktní panel, hero s prolínáním fotek, nové oblasti, patička, cookie lišta, CZ \| PL \| EN (`docs/08`) | **hotovo, čeká na připomínky a staging** |

Platná pravidla designu: **`design-system/project/README.md`** (a artefakt AKKSP Design System). `brand-manual/` a `docs/03` jsou historické verze.

## Struktura

```
design-system/           Design system (zdroj artefaktu): README = brand book, tokens.json, komponenty
  build-components.mjs  vygeneruje komponenty a obálku (node design-system/build-components.mjs)
site/                   Homepage (prototyp pro WordPress)
  index.html            česká verze
  pl/, en/              jazykové mutace (generuje tools/make-lang.ps1 z tools/i18n/pl.json, en.json)
  css/tokens.css        design tokeny --ak-*
  css/base.css          základ, typografie, plochy, mřížka
  css/components.css    tlačítka, „+ → ×", sklo, karty, štítky
  css/sections.css      sekce homepage + responzivita
  js/site.js            navigace, menu, accordion, CZ/PL, odkrývání
  css/wp.css            doplňky jen pro WordPress / Elementor
brand-manual/           Vizuální brand manual v1 (používá CSS ze site/)
wordpress/akksp-child/  Child téma (Hello Elementor): header/footer, widgety, písma
tools/build-theme.ps1   zkopíruje CSS/JS ze site/ do tématu a vytvoří ZIP (wordpress/dist/)
tools/get-fonts.ps1     stáhne písma (OFL) jako WOFF2 do tématu
tools/playground/       lokální testovací WordPress (blueprint + seed)
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
  06-wordpress-instalace.md instalace tématu, nastavení, lokální test, checklist
  07-finalni-design-v1-1.md první vlna připomínek
  08-druha-vlna-pripominek.md druhá vlna připomínek (téma 1.2.0)
```

Stránky: `http://localhost:4173/site/` (homepage), `/site/pl/`, `/site/en/`, `/moodboard/`. `#kontakt` otevře kontaktní panel, `#cookies` cookie lištu. Parametr `?static` vypne animace (pro snímky), `#hledat` otevře vyhledávání.

## Jak otevřít moodboard

Moodboard načítá obrázky relativně, proto je nejlepší pustit malý lokální server z kořene repozitáře:

```powershell
python -m http.server 4173 --directory "C:\Nový web"
```

Pak otevřít `http://localhost:4173/moodboard/`. U každého prvku klikněte na **Vybrat**, výběr se ukládá v prohlížeči a tlačítkem **Kopírovat výběr** (panel vpravo dole) ho pošlete dál.

## Tři designové směry

- **A – Editorial minimalism:** papír a inkoust, kontrastní serif Newsreader, vlasové linky, číslované rejstříky.
- **B – Contemporary institutional:** navy plochy, mřížka 3×3 převzatá z loga, IBM Plex Mono štítky, dělená tlačítka.
- **C – Glass & Soft:** glassmorfismus přes fotky kanceláře, decentní neumorfismus na krémové, Fraunces + Manrope.

Varianty jdou kombinovat napříč prvky (např. paleta B + písmo A + hero C).
