Klidná jistota na hranici dvou zemí. Web advokátní kanceláře AKKSP (Třinec) pro klienty v Česku a v Polsku: švýcarský řád mřížky a typografie, teplo papíru a dřeva z kanceláře, sklo a zlaté světlo jen tam, kde je pod nimi fotka. Tento manuál platí pro homepage i všechny podstránky; nový prvek nejdřív hledej tady.

## Principy

- **Řád.** 12sloupcová mřížka, vše zarovnané vlevo, jedna hlavička sekce pro celý web (SectionHead). Nic nepřidávej „pro ozdobu“.
- **Čtverec.** Logo je mřížka 3×3. Čtverec se vrací jako 8px značka u štítků, jako šipkový modul tlačítka a jako „+“ v oblastech. Rohy jen `ak-r-1` (2 px) a `ak-r-2` (4 px), nikdy pilulky.
- **Teplo.** Světlý web (`ak-cream`, `ak-paper`) s tmavými navy ostrovy (`ak-navy`). Zlatá je světlo, ne plocha.
- **Dva jazyky.** Česko a Polsko rovnocenně: přepínač CZ | PL přepíná celé jazykové mutace webu, nikdy jen kus textu.
- **Struktura je informace.** Žádné číslování sekcí ani položek (01 / 09, 01 · O kanceláři, Reference / 01). Štítek nad nadpisem jen tam, kde říká něco navíc.

## Obsah a tón

- Mluvíme jako dobrý advokát u stolu: věcně, lidsky, bez mlhy. Vykáme („Pomůžeme vám“), s malým „v“ uprostřed věty.
- Konkrétní situace místo obecných slibů. Ano: „Pomůžeme vám, když vám zákazník neplatí.“ Ne: „Komplexní řešení na nejvyšší úrovni.“
- Nadpisy a tlačítka větou, ne VERZÁLKAMI. Verzálky jen ve stylech `label`, `meta`, `link` (IBM Plex Mono).
- Tlačítko = sloveso + co dostanu: „Kontaktovat nás“, „Zobrazit nabídku školení“, „Seznam odborníků“.
- Čísla jen ověřená. Kancelář je v **Třinci** (Husova 401, pobočka Průmyslová 1032); Prahu ani jiná města neuváděj, dokud je kancelář nepotvrdí.
- Bez emoji. České uvozovky „ “, polské „ ”; pevná mezera za jednopísmennými předložkami a spojkami.

## Barvy

- Světlé sekce: pozadí `ak-cream` nebo `ak-paper` (střídej, když jdou dvě světlé po sobě), nadpisy `ak-ink`, text `ak-ink-body`, doprovodný text `ak-ink-64`, linky `ak-ink-14`.
- Tmavé sekce: pozadí `ak-navy`, text `#faf8f4` (hodnota `ak-paper`), doprovodný `ak-paper-72`, linky `ak-paper-16`, štítky a ikony `ak-gold`.
- Hlavička a kontaktní lišta: `ak-navy-850`.
- Zlatá: `ak-gold` jen na navy (zvýrazněná část H1, štítky), `ak-gold-soft` jako výplň primárního tlačítka, `ak-gold-line` jen dekor na světlé (čtverečky, uvozovky, obrys zvýraznění). Zlatou nikdy nepiš text na světlou plochu.
- `ak-amber` je fokus a jediný ampersand v „Česko & Polsko“. Nikde jinde.
- Poměr na stránce zhruba: krémová a papír 60 %, navy 30 %, inkoust 8 %, zlatá do 5 %.

## Typografie

- Tři rodiny (vše SIL OFL 1.1, hostované lokálně, žádné Google Fonts): **Source Serif 4** pro nadpisy (`display`, `h2`, `h3`, `number`), **IBM Plex Sans** pro text (`lead`, `body`, `small`, `button`, `h4`), **IBM Plex Mono** pro štítky a data (`label`, `meta`, `link`).
- Jediné H1 na stránce ve stylu `display`; sekce začíná `h2`. Velikosti na webu jsou fluidní (viz usage stylů).
- Nadpisy `text-wrap: balance`, text `text-wrap: pretty`; řádek textu max. ~65 znaků (`lead` 560 px).
- Zvýraznění v nadpisu jen v hero: druhá část H1 v `ak-gold` („v **Česku a Polsku.**“).

## Mřížka, rozestupy, rozvržení

- Obsah max. 1320 px, 12 sloupců, mezera `ak-s-5` (24 px), okraj 20–64 px.
- Hlavička sekce (SectionHead): štítek + nadpis + lead ve sloupcích 1–8, akce vpravo ve 9–12, nad ní linka `ak-ink-14` / `ak-paper-16`. Boční varianta (Proč AKKSP, Tým, Reference): hlavička 3 sloupce vlevo, obsah 9 sloupců.
- Sekce svisle 64–128 px (`ak-s-8` až `ak-s-10`), hlavička ↔ obsah `ak-s-8`, karty v řadě s mezerou `ak-s-5`.
- Rytmus stránky: tmavá (hero) → tmavá lišta → světlá → tmavá → světlá → světlá (jiný odstín) → tmavá s fotkou → světlá → tmavá patička.

## Tvary a povrchy

Tři povrchy, každý na svém místě:

- **Papír** (karty referencí, výsledky): `ak-paper`, obrys 1 px `ak-ink-08`, na najetí `ak-ink-44`, zaoblení `ak-r-2`.
- **Měkký reliéf** (jen dlaždice Proč AKKSP na `ak-cream`): stín `ak-relief` + 1px světlý okraj. Zvýrazněná dlaždice nemá výplň, jen obrys `ak-gold-line` a ampersand v `ak-amber`.
- **Sklo** (rozcestník, oblasti, panely nad fotkou): výplň `ak-glass-bg`, velké panely `ak-glass-panel`, obrys `ak-glass-border`, `backdrop-filter: blur(18px)`, odlesk `ak-glass-highlight`. Sklo jen nad fotkou s navy překryvem, max. jedna vrstva skla nad sebou.

## Interakce: inverze modrá ↔ zlatá

- Každý modrozlatý prvek si při najetí plynule (350 ms) prohodí barvy: primární tlačítko `ak-gold-soft` → `ak-navy` se zlatým textem; sekundární na světlé → `ak-navy` výplň; sekundární na tmavé → `ak-gold-soft` výplň.
- Inverzní stav má vždy 1px zlatý obrys, aby na `ak-navy` pozadí nesplynul.
- Šipka v čtvercovém modulu při najetí „projede“: odjede vpravo a vrátí se zleva (520 ms). Textové odkazy jen posunou šipku o 3 px a natáhnou zlatou linku.
- „+ → ×“: čtverec 40 px; najetí = výplň, otevřeno = `ak-gold-soft` a rotace o 45°, otevřeno + najetí = `ak-navy` se zlatým ×. Vždy `<button aria-expanded>`.
- Fokus: 2px obrys `ak-amber`, odsazení 3 px, na každém prvku.

## Pohyb

- Jen potvrzuje akci nebo uvádí sekci, každá sekce se objeví jednou. Žádné smyčky, paralaxa ani karusely.
- Nadpis sekce: maska zleva (500 ms, `cubic-bezier(0.76, 0, 0.24, 1)`); maska má přesah, aby neuřízla háčky a dotahy (ý, j, g).
- Karty: opacity + posun 16 px, 600 ms, rozestup 60 ms. Fotky týmu a článků: černobílá → barva, 700 ms.
- `prefers-reduced-motion`: vše hned viditelné, bez pohybu. `prefers-reduced-transparency`: sklo jako plná `ak-navy-850`.

## Logo

- Skupina **Logos**: `akksp-logo-white.svg` na `ak-navy` a fotkách, `akksp-logo-ink.svg` nebo `akksp-logo-navy.svg` na `ak-cream` / `ak-paper`.
- V hlavičce 120 px šířky (mobil 104 px), v patičce 136 px, minimum 96 px. Kolem loga volno o velikosti jednoho čtverce mřížky.
- Logo nedeformuj, nepřebarvuj mimo uvedené mutace, nedávej mu stín ani přechod.

## Fotografie

- Jen vlastní fotky z kanceláře a týmu (skupina **Photography**). Žádné fotobanky, váhy spravedlnosti ani kladívka.
- Nad fotkou vždy navy překryv (72–95 %), text nikdy přímo na fotku.
- Portréty: černobílé, barva na najetí; výřez 4:5, oči v horní třetině.
- Chybí a stojí za nafocení: školení / workshopy, práce s dokumenty, polští kolegové.

## Ikony

- Vlastní sada tenkých tahů 1,5 px na mřížce 24 px, zakončení kulatá (styl Phosphor Regular): šipka, telefon, e-mail, lupa, LinkedIn, YouTube, uvozovky. Barva z `currentColor`.
- Ikona nikdy nenese význam sama: vždy s textem nebo `aria-label`.

## Jazykové mutace

- CZ je výchozí, PL plnohodnotná mutace celého webu (Polylang). Přepínač LanguageSwitch je v hlavičce, v mobilním menu a v hero; vede na protějšek aktuální stránky.
- Polské texty píše nebo kontroluje rodilý mluvčí. Reference klientů se nepřekládají (zůstávají v originále s `lang="cs"`).

## Vyhledávání

- Funkčně zůstává stávající **Ivory Search** (formulář ID 350, živé výsledky od 2 znaků, stejné filtry). Téma mění jen vzhled.
- Otevírá se lupou v hlavičce jako overlay (Search), zavírá se ×, Esc nebo klikem mimo. Stránka výsledků používá PageHeader a seznam výsledků v papírovém stylu.

## Stavba stránek

- **Homepage:** Header → Hero s rozcestníkem (RouteCard) → ContactBar → Proč AKKSP (ReliefTile) → Oblasti (AreaAccordion) → Tým (Person) → Reference (ReferenceCard) → Školení (skleněný panel nad fotkou) → Aktuality (PostCard) → Footer.
- **Podstránka:** Header → PageHeader (tmavý, H1) → sekce se SectionHead a obsahem (střídej `ak-cream` / `ak-paper`, jednou za stránku smí být tmavá) → závěrečná výzva s primárním Button → Footer.
- **Článek:** PageHeader s kategorií a datem v `meta`, text max. 760 px, mezititulky `h3`, odrážky se čtverečkem `ak-gold-line`.
- Jedno primární tlačítko na obrazovku.

## Přístupnost

- Kontrast textu min. 4,5:1 (ověřené páry: `ak-ink` na `ak-cream` 16,1:1, `ak-paper` text na `ak-navy` 17,3:1, `ak-gold` na `ak-navy` 11,5:1, `ak-navy` na `ak-gold-soft` 12,5:1). `ak-gold-line` a `ak-amber` jen dekor.
- Celý web ovladatelný klávesnicí, „Přejít k obsahu“ jako první odkaz, sémantické nadpisy, alt texty u portrétů.

## Implementace

- WordPress: child téma `akksp-child` pro Hello Elementor. Sekce jsou Elementor widgety (kategorie „AKKSP sekce“), hlavička a patička jsou v tématu, údaje kanceláře ve Vzhled → Přizpůsobit.
- Zdroj stylů je `site/css/` v repozitáři; `tools/build-theme.ps1` je zkopíruje do tématu. Třídy mají prefix `ak-`.
- Plochy: obal sekce dostane `ak-light`, `ak-paper` nebo `ak-dark`; komponenty uvnitř si z toho samy vezmou barvy textu, linek i inverze.
