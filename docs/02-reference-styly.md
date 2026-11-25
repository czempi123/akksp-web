# 02 · Reference a styly

Pro každý styl: jak ho poznat, jak ho dělají ostatní (vzorce prvků), co z toho bereme pro AKKSP a kde jsou hranice. Weby uvedené níže jsou orientační příklady stylu; konkrétní podobu si před fází 2 projdeme naživo.

---

## A · Editorial minimalism

**Poznávací znaky:** layout jako tištěný časopis nebo dobře vysázená smlouva. Velký kontrastní serif, hodně prázdného místa, asymetrická mřížka, vlasové linky místo rámečků, kurzíva jako akcent místo barvy, číslované seznamy (01, 02, 03), popisky pod fotkami.

**Kde ho vidět:** magazíny typu Kinfolk nebo Cereal, značky jako Aesop. V právu ho podle přehledů používají např. Ropes & Gray (minimalismus, negativní prostor, jednoduchá typografie) a menší „butikové" kanceláře s velkým serifem a temnou paletou. Agentury letos popisují posun právnických webů k high-end editorial designu: silná mřížka, asymetrie, velké okraje.

**Jak to implementují (vzorce):**
| Prvek | Vzorec |
|---|---|
| Nadpisy | serif Light 300 ve velkých stupních (72–96 px), mírně stažené prostrkání (−0,02 em), kurzíva pro zvýraznění |
| Seznamy služeb | rejstřík: číslo · název · popis · šipka, oddělené 1px linkou přes celou šířku |
| Tlačítka | ostré hrany, inkoustová výplň nebo jen podtržený text, šipka se při hoveru posune |
| Fotky | velké výřezy s popiskem, často černobílé, barva až na hover |
| Barvy | 1 papírová, 1 inkoustová, 1 akcent jen na detaily |

**Pro AKKSP:** hero jako **dvojjazyčná smlouva** (CZ vlevo, PL vpravo, zlatá linka = hranice s Třincem), služby jako rejstřík, tým černobíle, citát jako titulní strana revue.

**Hranice:** nesmí to být „prázdné". Každá sekce potřebuje jeden silný typografický moment, jinak působí jako šablona.

---

## B · Contemporary institutional

**Poznávací znaky:** jistota a řád. Silné barevné plochy, modulární mřížka, data a štítky v neproporcionálním písmu (mono), jasná hierarchie, minimum dekorace, přístupnost jako samozřejmost. Typické pro centrální banky, univerzity, velké mezinárodní kanceláře, moderní státní design systémy (např. GOV.UK Design System).

**Jak to implementují (vzorce):**
| Prvek | Vzorec |
|---|---|
| Mřížka | opakující se modul (čtverec, sloupec), dlaždice s číslem „01 / 03" |
| Typografie | pevný knižní serif nebo grotesk + mono pro štítky, data, data publikace |
| Tlačítka | obdélník s minimálním zaoblením, často dělené na text a ikonový modul |
| Navigace | plná barevná lišta, aktivní položka podtržená silnou linkou |
| Barvy | 1 tmavá dominantní (navy), 1 světlá, 1 signální akcent |

**Pro AKKSP:** logo je mřížka 3×3, tedy hotový modul. Z něj vychází hero (9 dlaždic: fotky + fakta, Třinec uprostřed zlatě), 9 oblastí právní pomoci v mřížce 3×3, čtvercový šipkový modul v tlačítkách, osa Praha · Třinec · Krakov se souřadnicemi.

**Hranice:** riziko chladu. Proto teplé fotky interiéru (dřevo) v navy duotónu, které se na hover zbarví.

---

## C · Glass & Soft (glassmorfismus + neumorfismus)

### Glassmorfismus
**Poznávací znaky:** poloprůhledné „mléčné sklo" (`backdrop-filter: blur`), jemný světlý okraj, vnitřní odlesk nahoře, pod sklem barevný nebo fotografický podklad. Aktuálně posílený vlnou Apple „Liquid Glass".

**Doporučení z praxe (a jak je dodržujeme):**
- Hlavní riziko je kontrast textu. Měřit proti nejhoršímu místu podkladu → pod sklem vždy tmavý navy překryv fotky.
- Blur jen na horní vrstvě, nižší vrstvy plné. Vyšší blur je drahý na mobilech → max. 18 px.
- Respektovat `prefers-reduced-transparency` → v moodboardu se sklo mění na plnou navy plochu.
- Fallback přes `@supports (backdrop-filter: …)`, pro Safari prefix `-webkit-`.

### Neumorfismus
**Poznávací znaky:** prvek má stejnou barvu jako pozadí a „vystupuje" dvojicí stínů (světlý vlevo nahoře, tmavý vpravo dole); stisk = vnitřní stín.

**Známé problémy a opravy:**
- Nízký kontrast hran → přidáváme 1px světlý/zlatý obrys, ne jen stín.
- Stav jen stínem není vidět pro čtečky ani slabozraké → stav nese i barva ikony a `aria-expanded`.
- Viditelný fokus (2px jantarový outline) je povinný.
- Používáme jen na krémové ploše a jen na velkých prvcích (dlaždice, karty, knoflík „+"), nikdy na textová pole.

**Pro AKKSP:** hero z fotky zasedačky se skleněným panelem a přepínačem CZ / PL, skleněné karty nad fotkou, neumorfní dlaždice s čísly, „zamáčknutý" zvýrazněný argument Česko & Polsko, knoflík „+" který se při otevření zamáčkne a otočí na „×".

**Hranice:** maximálně jedna skleněná vrstva na obrazovku; žádné duhové gradienty.

---

## Společné principy pro všechny varianty

- **Typografie dělá 90 % dojmu.** Max. 2 rodiny + mono pro data.
- **Rytmus:** sekce nemají stejné odsazení ani strukturu, střídá se světlá a tmavá plocha (jako v konceptu).
- **Pohyb jen potvrzuje:** příchod sekce jednou, hover, otevření. Žádné smyčky, paralaxa, automatické karusely.
- **Přístupnost:** kontrast WCAG 2.2 AA (kontrast se v moodboardu počítá živě), viditelný fokus, `prefers-reduced-motion`.
- **Žádné stockové fotky:** jen vlastní fotky z kanceláře a týmu.

## Zdroje rešerše

- [PaperStreet: 2026 Law Firm Website Design Trends](https://www.paperstreet.com/blog/2026-law-firm-website-design-trends/)
- [HubSpot: Best attorney website designs](https://blog.hubspot.com/website/best-attorney-website-designs)
- [Really Good Designs: Law firm website design](https://reallygooddesigns.com/law-firm-website-design/)
- [Clio: Best law firm websites](https://www.clio.com/uk/blog/best-law-firm-websites/)
- [Lawyerist: Best law firm websites 2026](https://lawyerist.com/news/best-law-firm-websites-2026/)
- [Axess Lab: Glassmorphism meets accessibility](https://axesslab.com/?p=5111)
- [LogRocket: What is glassmorphism](https://blog.logrocket.com/ux-design/what-is-glassmorphism)
- [CSS Glassmorphism best practices](https://lifa-su.github.io/blog/ai-css-glassmorphism-accessible-design.html)
- [Axess Lab: Neumorphism – the accessible and inclusive way](https://axesslab.com/neumorphism/?lang=sv/)
- [Built In: Neumorphism accessibility](https://builtin.com/design-ux/neumorphism-accessibility)
- [BOIA: How soft UI designs impact accessibility](https://www.boia.org/blog/how-soft-user-interface-designs-impact-accessibility)
