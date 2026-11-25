# 05 · Design system v1 (fáze 2)

Vizuální verze: `brand-manual/index.html`. Homepage: `site/index.html`. Kód systému: `site/css/`.

## Výběr klienta → jak byl sjednocen

Výběr z moodboardu byl brán jako doporučení. Cíl: jeden jednotný web, ne koláž tří stylů.

| Prvek | Výběr | Výsledek ve v1 | Co se upravilo kvůli jednotě |
|---|---|---|---|
| Paleta | A (papír & inkoust) | krémová + papír jako základ, navy „ostrovy" | hořčičná vypuštěna, štítky na navy jsou zlatou |
| Typografie | B (Source Serif 4 + IBM Plex) | beze změny | písmo platí i pro skleněné prvky (původně Fraunces/Manrope) |
| Tlačítka | B (dělené, šipkový modul) | beze změny | „+" je čtverec z B i ve skleněných kartách |
| Navigace | C (plovoucí sklo) | plovoucí skleněná lišta | pilulka → obdélník 4 px, menu s podtržením z B |
| Hero | C (sklo + CZ/PL) | beze změny konceptu | rohy 4 px, mono štítky, rozcestník přesunut do hero (jako v konceptu) |
| Rozcestník | C (skleněné karty) | uvnitř hero nad stejnou fotkou | šipka v zlatém čtverci z B |
| Kontaktní lišta | B | beze změny | — |
| Proč AKKSP | C (neumorfismus) | měkký reliéf na krémové | rohy 2 px, čísla v Source Serif, mono indexy |
| Oblasti | C (sklo) | 3×3 skleněné karty | čtvercový „+" z B, mono číslování 01/09 |
| Tým | A | černobílá → barva | jména v Source Serif |
| Reference | B | karty + nově řada klientských log z webu | — |
| Školení | C | fotka + sklo | — |
| Aktuality | A | novinové sloupce | — |
| Patička | B | beze změny struktury | — |
| Pohyb | B | maska na nadpisech + jemný výstup karet | — |

**Pojivo (švýcarský styl / Bauhaus):** 12sloupcová mřížka, hlavička sekce „štítek | nadpis | akce", číslování sekcí 01–06, mono štítky se čtverečkem, rohy 2/4 px, čtverec a mřížka 3×3 z loga jako opakující se modul.

**Pravidlo povrchů:** sklo jen na tmavé fotce, reliéf jen na krémové, papírové karty všude jinde.

## Struktura kódu (připraveno pro WordPress)

| Soubor | Obsah | Kam ve WordPressu |
|---|---|---|
| `site/css/tokens.css` | proměnné `--ak-*` | Elementor Global Colors / Fonts + `:root` v child tématu |
| `site/css/base.css` | reset, typografie, plochy `.ak-light / .ak-paper / .ak-dark`, mřížka | child téma `style.css` |
| `site/css/components.css` | tlačítka, „+ → ×", sklo, štítky, hlavička sekce, dlaždice, karty, accordion | child téma, třídy přidávané v Elementoru (pole „CSS Classes") |
| `site/css/sections.css` | navigace, hero, sekce homepage, patička, responzivita | child téma; sekce = uložené Elementor šablony |
| `site/js/site.js` | navigace, mobilní menu, accordion, CZ/PL, odkrývání | child téma, jeden skript ve footeru |

Všechny třídy mají prefix `ak-`, aby nekolidovaly s Elementorem.

## Rozhodnutí a poznámky

- Kontaktní lišta a hlavní CTA vedou na patičku `#kontakt`. Ve WP doplnit kontaktní formulář (současný web ho má).
- Přepínač CZ / PL v hero: v prototypu přepíná text, ve WP bude odkazovat na polskou verzi stránky (Polylang).
- Řada „Pracujeme pro" používá loga ze stránky Reference na aktrinec.cz (jednobarevně). Před spuštěním potvrdit, že je klienti dovolují uvádět.
- Odkazy na LinkedIn a YouTube chybí (na webu nejsou), jsou označené k doplnění.
- Čísla 30+ / 15+ zůstávají podle konceptu, k ověření (viz docs/01).
- Kontroly: snímky celé stránky přes `tools/shoot.ps1` (headless Edge), přetékání na 390 px ověřeno v prohlížeči, interakce (accordion, CZ/PL, menu, Esc) otestovány.
