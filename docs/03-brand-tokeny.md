# 03 · Brand tokeny (pracovní verze)

Zdroj pravdy v kódu: `moodboard/css/tokens.css`. Po výběru variant se tento dokument zúží na jednu verzi a stane se z něj brand manual.

## Barvy (vzorkováno z konceptu)

Hodnoty jsou změřené přímo z pixelů konceptu (dominantní barva plochy, u textu jádro tahu).

| Token | Hex | Kde je v konceptu | Použití |
|---|---|---|---|
| `navy-900` | `#001628` | pozadí hero, služeb, patičky | hlavní tmavá |
| `navy-850` | `#02182C` | navigace, kontaktní lišta | lišty |
| `navy-700` | `#123249` | světlo v hero | zvednuté plochy, hover |
| `ink` | `#0A0E45` | nadpisy na krémové | nadpisy, text, primární tlačítko (A) |
| `ink-body` | `#111335` | běžný text na krémové | text |
| `gold` | `#F8C46F` | „Česku a Polsku." | zvýraznění na tmavé |
| `gold-soft` | `#FCCE85` | výplň tlačítek | tlačítka |
| `gold-line` | `#DEB66C` | rámečky karet, obrysová tlačítka | linky |
| `amber` | `#F49915` | uvozovky | drobný akcent |
| `amber-deep` | `#E29421` | zlatá čára v mapě | glow, tečky (jen dekor) |
| `mustard` | `#E9C634` | štítek „Advokátní kancelář AKKSP" | mono štítky na navy |
| `cream` | `#F5F1EE` | světlé sekce | pozadí |
| `paper` | `#FAF8F4` | karty | karty, papír |
| `sand` | `#FCE4C4` | box „Česko & Polsko" | zvýraznění |

**Pravidla kontrastu:** zlatá a jantarová nikdy jako text na světlém pozadí (kontrast pod 3:1). Na světlém je zlatá jen jako výplň za inkoustovým textem nebo jako linka. Na navy je zlatá i hořčičná čitelná (AA).

## Písma (všechna SIL Open Font License 1.1)

| Sada | Nadpisy | Text | Data / štítky |
|---|---|---|---|
| A | Newsreader (Production Type) | Instrument Sans | — |
| B | Source Serif 4 (Adobe) | IBM Plex Sans | IBM Plex Mono |
| C | Fraunces (Undercase Type), osa SOFT | Manrope | — |

OFL dovoluje komerční použití, úpravy i vlastní hosting zdarma; nesmí se jen prodávat samotné soubory písma. Všechna písma mají latin-ext (čeština i polština).

## Spacing

`2 · 4 · 8 · 12 · 16 · 24 · 32 · 40 · 48 · 64 · 80 · 96 px` (tokeny `--s-25` až `--s-900`). Sekce na desktopu 64–96 px, na mobilu 48 px.

## Zaoblení

| Varianta | Tlačítka | Karty | Pravidlo |
|---|---|---|---|
| A | 0 | 0 | ostré hrany, jen kruhové ikonové prvky |
| B | 2 px | 2 px | „téměř ostré", modul |
| C | pilulka | 20–28 px | vnořené: vnitřní = vnější − mezera |

## Pohyb

- Základní křivka `cubic-bezier(0.22, 1, 0.36, 1)`, B pro odkrytí `cubic-bezier(0.76, 0, 0.24, 1)`.
- Délky: hover 300 ms, změna stavu 500 ms, příchod sekce 600–700 ms.
- Animuje se jen `transform`, `opacity` (výjimečně `clip-path`, `filter`).
- Každá sekce se objeví jen jednou (IntersectionObserver), žádné smyčky ani paralaxa.
- `prefers-reduced-motion` → jen okamžitá změna; `prefers-reduced-transparency` → sklo jako plná plocha.

## Komponenta „+ → ×"

- Jeden prvek se dvěma čarami (`::before`, `::after`), při `aria-expanded="true"` rotace 45° (A, B) nebo 135° (C, pomalejší 700 ms).
- Stav nese i barva / výplň (A inkoust, B zlatá, C „zamáčknutí" + zlatá na tmavé), nejen tvar.
- Použití: rozbalení oblastí právní pomoci, FAQ, mobilní menu (tři čáry → ×), panel výběru.
- Ovládání klávesnicí: `<button>` s `aria-expanded` a `aria-controls`.

## Ikony

Tenké tahy 1,5 px ve stylu Phosphor „regular" (šipka, telefon, obálka, špendlík, lupa, LinkedIn, YouTube). Pro produkci knihovna Phosphor Icons (MIT licence).

## Logo

- `assets/brand/akksp-logo-original.svg` – originál z webu (bílý).
- `akksp-logo-white.svg` (na navy), `akksp-logo-ink.svg` a `akksp-logo-navy.svg` (na světlé), `akksp-logo-gold.svg` (speciální použití).
- Motiv mřížky 3×3 lze použít samostatně jako grafický prvek (varianta B, patička C).
- Ochranná zóna a minimální velikost: doplní se ve fázi 2.
