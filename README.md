# AKKSP – redesign webu aktrinec.cz

Pracovní repozitář pro redesign webu AKKSP advokátní kancelář, s.r.o. Obsahuje brand manual, moodboard, podklady a později i samotnou implementaci do WordPressu.

## Fáze

| Fáze | Obsah | Stav |
|---|---|---|
| 1 | Moodboard: 3 varianty každého prvku (paleta, písmo, tlačítka, navigace, hero, rozcestník, kontakt, čísla, oblasti, tým, reference, školení, aktuality, patička, pohyb) | **hotovo, čeká na výběr** |
| 2 | Brand manual z vybraných variant + kompletní homepage (desktop + mobil) | čeká |
| 3 | Implementace do WordPressu (staging → produkce) | čeká |

## Struktura

```
moodboard/              Moodboard (HTML/CSS/JS bez knihoven)
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
```

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
