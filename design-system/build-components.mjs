// Generates design-system/project/components/<Comp>/{README.md,preview.html} and the Cover.
// Usage: node design-system/build-components.mjs
import { mkdirSync, writeFileSync } from "node:fs";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";

const here = dirname(fileURLToPath(import.meta.url));
const out = join(here, "project", "components");

const BLOB = {
  logoWhite: "/_blob/372971e8d5f66e27f553f035876a0e69",
  logoInk: "/_blob/9be2861a4a8cd51cf1e9d21ce852c971",
  hero: "/_blob/4c18cf88550b714f05ec0c05d79e56be",
  portrait: "/_blob/4d20f77198159d3400314cbf50f6b1ac",
  glasses: "/_blob/fd7500b149dab0584bc5ce3e64bac77f",
};

const SPRITE = `<svg width="0" height="0" style="position:absolute" aria-hidden="true">
<symbol id="i-arrow" viewBox="0 0 24 24"><path d="M4 12h16M14 6l6 6-6 6"/></symbol>
<symbol id="i-phone" viewBox="0 0 24 24"><path d="M6.6 3.5h2.8l1.6 4.4-2 1.3a10.6 10.6 0 0 0 5.8 5.8l1.3-2 4.4 1.6v2.8a2 2 0 0 1-2.1 2A16.5 16.5 0 0 1 4.6 5.6a2 2 0 0 1 2-2.1z"/></symbol>
<symbol id="i-search" viewBox="0 0 24 24"><circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.4-4.4"/></symbol>
<symbol id="i-quote" viewBox="0 0 32 32"><path d="M4 22.5c0-6.2 2.9-10.9 8.6-14l1.6 2.3c-3.3 2-5 4.4-5.3 7.2H14V27H4v-4.5zm14 0c0-6.2 2.9-10.9 8.6-14l1.6 2.3c-3.3 2-5 4.4-5.3 7.2H28V27H18v-4.5z"/></symbol>
<symbol id="i-in" viewBox="0 0 24 24"><rect x="3.5" y="3.5" width="17" height="17" rx="2.5"/><path d="M8.2 10.5v6M8.2 7.6v.1M11.8 16.5v-3.6a2.3 2.3 0 0 1 4.6 0v3.6M11.8 10.5v6"/></symbol>
<symbol id="i-yt" viewBox="0 0 24 24"><rect x="2.8" y="5.8" width="18.4" height="12.4" rx="3.5"/><path d="m10.3 9.3 4.4 2.7-4.4 2.7z"/></symbol>
</svg>`;

const ico = (n, sm) => `<svg class="ak-ico${sm ? " ak-ico--sm" : ""}"><use href="#i-${n}"/></svg>`;
const btn = (t, v = "primary", size = "") => `<a class="ak-btn ak-btn--${v}${size ? " ak-btn--" + size : ""}" href="#"><span>${t}</span><i class="ak-arrow-box">${ico("arrow", size === "sm")}</i></a>`;
const seg = (pos = 0) => `<nav class="ak-seg" style="--n:2;--pos:${pos}" aria-label="Jazyk webu"><span class="ak-seg__thumb" aria-hidden="true"></span><a href="#"${pos === 0 ? ' aria-current="true"' : ""} lang="cs">CZ</a><a href="#"${pos === 1 ? ' aria-current="true"' : ""} lang="pl">PL</a></nav>`;
const pxScript = `<script>document.querySelectorAll("[data-px]").forEach(function(b){b.addEventListener("click",function(){b.setAttribute("aria-expanded",String(b.getAttribute("aria-expanded")!=="true"))})});document.querySelectorAll(".ak-area__btn").forEach(function(b){b.addEventListener("click",function(){var i=b.parentElement,o=!i.classList.contains("is-open");i.classList.toggle("is-open",o);b.setAttribute("aria-expanded",String(o))})});</script>`;

const C = [];
const add = (name, group, height, readme, body, extra = "") => C.push({ name, group, height, readme, body, extra });

add("Button", "Akce", 200,
`Tlačítko: text a čtvercový šipkový modul. Primární (\`ak-gold-soft\`) je jedno na obrazovku, sekundární (obrys) pro další akce.

- **Použij:** hlavní výzva sekce („Kontaktovat nás“, „Zobrazit nabídku školení“), přechod na seznam („Seznam odborníků“).
- **Dodáš:** text (sloveso + co dostanu, větou), odkaz. Ikona v modulu je šipka, u telefonu sluchátko.
- **Stavy:** najetí = plynulá inverze modrá ↔ zlatá (350 ms) se zlatým obrysem; šipka projede modulem; stisk posun 1 px; fokus 2px \`ak-amber\`.
- **Varianty:** \`ak-btn--primary\`, \`ak-btn--secondary\` (barvy bere z plochy \`ak-light\` / \`ak-paper\` / \`ak-dark\`), \`ak-btn--glass\` (nad fotkou), velikost \`ak-btn--sm\` (40 px, hlavička).
- **Ne:** dvě primární vedle sebe, pilulky, verzálky, text bez slovesa.`,
`<div style="display:grid;grid-template-columns:1fr 1fr">
  <div class="ak-paper" style="padding:32px;display:flex;flex-wrap:wrap;gap:16px;align-items:center">${btn("Kontaktovat nás")}${btn("Seznam odborníků", "secondary")}${btn("Kontakt", "primary", "sm")}</div>
  <div class="ak-dark" style="padding:32px;display:flex;flex-wrap:wrap;gap:16px;align-items:center">${btn("Kontaktovat nás")}${btn("Všechny oblasti", "secondary")}</div>
</div>`);

add("TextLink", "Akce", 120,
`Textový odkaz: IBM Plex Mono verzálky (styl \`link\`) s šipkou; na najetí se natáhne zlatá linka (\`ak-gold-line\` / \`ak-gold\`) a šipka se posune o 3 px.

- **Použij:** vedlejší přechody v hlavičce sekce („Všechny články“, „Více o nás“), „Číst článek“ v kartě.
- **Dodáš:** krátký text, odkaz.
- **Ne:** jako hlavní výzva (to je Button) ani uprostřed odstavce (tam běžný podtržený odkaz).`,
`<div style="display:grid;grid-template-columns:1fr 1fr">
  <div class="ak-paper" style="padding:32px;display:flex;gap:32px"><a class="ak-link" href="#">Všechny články ${ico("arrow", true)}</a><a class="ak-link" href="#">Více o nás ${ico("arrow", true)}</a></div>
  <div class="ak-dark" style="padding:32px"><a class="ak-link" href="#">Všechny články ${ico("arrow", true)}</a></div>
</div>`);

add("PlusToggle", "Akce", 120,
`Přepínač „+ → ×“ pro rozbalování (oblasti, FAQ). Čtverec 40 px, dvě čáry 14 px.

- **Stavy:** zavřeno = obrys (\`ak-ink-44\` na světlé, \`ak-gold-40\` na tmavé); najetí = výplň; otevřeno = \`ak-gold-soft\` a rotace o 45° na „×“ (500 ms); otevřeno + najetí = \`ak-navy\` se zlatým ×.
- **Dodáš:** \`<button aria-expanded="false" aria-controls="…">\`; stav nese atribut, ne jen tvar.
- Klikni v náhledu pro otevření.`,
`<div style="display:grid;grid-template-columns:1fr 1fr">
  <div class="ak-paper" style="padding:32px;display:flex;gap:16px"><button type="button" class="ak-px" data-px aria-expanded="false" aria-label="Rozbalit"><span class="ak-px__ico"></span></button><button type="button" class="ak-px" data-px aria-expanded="true" aria-label="Sbalit"><span class="ak-px__ico"></span></button></div>
  <div class="ak-dark" style="padding:32px;display:flex;gap:16px"><button type="button" class="ak-px" data-px aria-expanded="false" aria-label="Rozbalit"><span class="ak-px__ico"></span></button><button type="button" class="ak-px" data-px aria-expanded="true" aria-label="Sbalit"><span class="ak-px__ico"></span></button></div>
</div>`, pxScript);

add("LanguageSwitch", "Navigace", 120,
`Přepínač jazykových mutací celého webu: segment CZ | PL se zlatým jezdcem na aktuálním jazyce.

- **Chování:** odkazy na protějšek aktuální stránky (Polylang). Jezdec nejdřív plynule dojede (260 ms), pak se načte druhá mutace. Nikdy nepřepíná jen kus textu na stránce.
- **Kde:** hlavička, mobilní menu, panel v hero.
- **Dodáš:** seznam jazyků; \`--n\` = počet, \`--pos\` = pořadí aktuálního.`,
`<div class="ak-dark" style="padding:32px;display:flex;gap:24px">${seg(0)}${seg(1)}</div>`);

add("SectionHead", "Rozvržení", 260,
`Hlavička sekce: nepovinný štítek (styl \`label\` se čtverečkem), nadpis \`h2\`, lead, vpravo akce. Nad ní linka \`ak-ink-14\` / \`ak-paper-16\`.

- **Použij:** na začátku každé sekce kromě hero. Boční varianta (nadpis 3 sloupce vlevo) jen u Proč AKKSP, Týmu a Referencí.
- **Štítek** jen když nese informaci („Oblasti naší právní pomoci“). Nikdy číslování (01 · …).
- **Dodáš:** nadpis větou s otazníkem nebo pojmenováním, lead do ~120 znaků, max. jednu akci (TextLink nebo sekundární Button).`,
`<div class="ak-dark" style="padding:48px 48px 8px"><div class="ak-head ak-grid" style="margin-bottom:24px"><div class="ak-head__title"><p class="ak-label">Oblasti naší právní pomoci</p><h2 class="ak-h2">S čím vám pomůžeme?</h2><p class="ak-lead">Široké spektrum právních služeb pro firmy a podnikatele.</p></div><div class="ak-head__action">${btn("Všechny oblasti právní pomoci", "secondary")}</div></div></div>`);

add("Header", "Navigace", 120,
`Hlavička webu: navy lišta \`ak-navy-850\` přes celou šířku, přichycená nahoře (80 px, po odscrollování 64 px se stínem \`ak-nav-drop\`).

- **Obsah:** logo (bílá mutace, 120 px) oddělené svislou linkou, hlavní menu (7 položek, aktivní podtržená \`ak-gold\`), lupa (Search), LanguageSwitch, primární Button „Kontakt“.
- **Mobil (pod 1300 px):** menu a jazyk se schovají do panelu, ikona menu se mění na „×“.
- **Ne:** plovoucí kapsle, průhledná lišta nad textem.`,
`<header class="ak-nav" style="position:relative"><div class="ak-nav__bar"><a class="ak-nav__logo" href="#"><img src="${BLOB.logoWhite}" alt="AKKSP advokátní kancelář" width="120" height="30"></a><nav aria-label="Hlavní navigace"><ul class="ak-nav__menu"><li><a href="#" aria-current="page">Pro firmy</a></li><li><a href="#">Pro jednotlivce</a></li><li><a href="#">Česko / Polsko</a></li><li><a href="#">O nás</a></li><li><a href="#">Zkušenosti</a></li><li><a href="#">Školení a workshopy</a></li><li><a href="#">Právní poradna</a></li></ul></nav><div class="ak-nav__tools"><button type="button" class="ak-nav__icon" aria-label="Hledat">${ico("search")}</button>${seg(0)}${btn("Kontakt", "primary", "sm")}</div></div></header>`, "", 1440);

add("Hero", "Rozvržení", 520,
`Úvod homepage: fotka kanceláře s navy překryvem, tmavší skleněný panel (\`ak-glass-panel\`) se štítkem, LanguageSwitch, H1 ve stylu \`display\` (druhá část v \`ak-gold\`) a leadem; pod ním rozcestník ze tří RouteCard.

- **Dodáš:** vlastní fotku interiéru (žádná fotobanka), nadpis do dvou řádků, lead do dvou vět.
- **Ne:** výčet měst, mapy, čísla v panelu.`,
`<section class="ak-hero ak-dark" style="min-height:0;padding:64px 0 48px"><div class="ak-photo"><img src="${BLOB.hero}" alt=""></div><div class="ak-hero__light" aria-hidden="true"></div><div class="ak-wrap ak-grid"><div class="ak-hero__panel ak-glass"><div class="ak-hero__top"><p class="ak-label">Advokátní kancelář AKKSP</p>${seg(0)}</div><h1 class="ak-display">Právní partner v <em>Česku a Polsku.</em></h1><p class="ak-lead ak-hero__sub">Komplexní právní podpora pro firmy, podnikatele i jednotlivce. Srozumitelně, spolehlivě a s důrazem na praktická řešení.</p></div></div></section>`, "", 1440);

add("RouteCard", "Obsah", 260,
`Karta rozcestníku: sklo nad fotkou, nadpis \`h3\`, jedna věta, vpravo dole čtvercový šipkový modul \`ak-gold-soft\`.

- **Najetí:** karta zesvětlí a dostane zlatý obrys, modul se invertuje na \`ak-navy\` se zlatou šipkou a šipka projede (odjede vpravo, vrátí se zleva).
- **Dodáš:** 3 karty v řadě (na mobilu pod sebou), nadpis do jednoho řádku, větu do ~60 znaků, odkaz.
- **Ne:** číslování karet, ikony, čtverečky navíc.`,
`<section class="ak-hero ak-dark" style="min-height:0;padding:40px 0"><div class="ak-photo"><img src="${BLOB.hero}" alt=""></div><div class="ak-wrap ak-grid ak-hero__routes" style="padding-top:0"><a class="ak-route ak-glass" href="#"><h2 class="ak-h3 ak-route__title">Podnikání v Česku a Polsku</h2><p>Právní podpora bez přeshraničních bariér.</p><span class="ak-route__go ak-arrow-box" aria-hidden="true">${ico("arrow")}</span></a><a class="ak-route ak-glass" href="#"><h2 class="ak-h3 ak-route__title">Pro firmy a podnikatele</h2><p>Právní servis pro stabilní fungování a růst firmy.</p><span class="ak-route__go ak-arrow-box" aria-hidden="true">${ico("arrow")}</span></a><a class="ak-route ak-glass" href="#"><h2 class="ak-h3 ak-route__title">Pro jednotlivce</h2><p>Právní jistota v klíčových životních situacích.</p><span class="ak-route__go ak-arrow-box" aria-hidden="true">${ico("arrow")}</span></a></div></section>`, "", 1440);

add("ContactBar", "Obsah", 120,
`Kontaktní lišta pod hero na \`ak-navy-850\`: nadpis \`Kontaktujte nás\`, buňky se štítkem \`label\` (telefon, e-mail, kancelář) oddělené linkou \`ak-paper-08\`, vpravo primární Button.

- **Dodáš:** údaje z Přizpůsobit (telefon, e-mail, adresa sídla). Uvádíme jen skutečnou kancelář: Husova 401, Třinec.
- **Mobil:** buňky pod sebou.`,
`<section class="ak-contact ak-dark"><div class="ak-wrap"><div class="ak-contact__row"><h2 class="ak-contact__title">Kontaktujte nás</h2><a href="#"><small class="ak-label">Telefon</small><span>+420 558 997 103</span></a><a href="#"><small class="ak-label">E-mail</small><span>info@aktrinec.cz</span></a><div><small class="ak-label">Kancelář</small><span style="color:var(--ak-paper)">Husova 401, Třinec</span></div><div>${btn("Kontaktovat nás")}</div></div></div></section>`, "", 1440);

add("ReliefTile", "Obsah", 260,
`Dlaždice s měkkým reliéfem (\`ak-relief\`) pro argumenty „Proč AKKSP“. Jen na \`ak-cream\`, vždy s 1px světlým okrajem a 8px čtverečkem \`ak-gold-line\` vpravo nahoře.

- **Obsah:** číslo (\`number\`) nebo krátké slovo (\`h3\`), pod tím popis \`small\` v \`ak-ink-64\`.
- **Zvýraznění** (\`ak-tile--accent\`): jemné — bez výplně, jen obrys \`ak-gold-line\`, tmavý čtvereček a ampersand v \`ak-amber\` („Česko & Polsko“). Jedna zvýrazněná dlaždice na sekci.
- **Ne:** písková výplň, štítky nad čísly (01 · …), reliéf na tmavé ploše.`,
`<div class="ak-light" style="padding:40px"><div class="ak-why__tiles" style="grid-column:auto"><div class="ak-tile"><span class="ak-why__num">30+</span><p>let zkušeností v právním poradenství</p></div><div class="ak-tile"><span class="ak-why__num">15+</span><p>členný tým odborníků</p></div><div class="ak-tile ak-tile--accent"><span class="ak-why__word">Česko <span class="ak-amp">&amp;</span> Polsko</span><p>jedna kancelář, široké možnosti</p></div><div class="ak-tile"><span class="ak-why__word">Praktická řešení</span><p>srozumitelná, efektivní a s důrazem na výsledek</p></div></div></div>`, "", 1200);

const area = (id, t, s, items, open) => `<div class="ak-area ak-glass${open ? " is-open" : ""}"><button type="button" class="ak-area__btn" aria-expanded="${open}" aria-controls="${id}"><span class="ak-area__title">${t}</span><span class="ak-area__sub">${s}</span><span class="ak-px" aria-hidden="true"><span class="ak-px__ico"></span></span></button><div class="ak-acc__panel" id="${id}"><div><div class="ak-area__body"><p class="ak-label">Pomůžeme vám</p><ul class="ak-list-sq">${items.map((i) => `<li>${i}</li>`).join("")}</ul></div></div></div></div>`;
add("AreaAccordion", "Obsah", 340,
`Oblast právní pomoci: skleněná karta s názvem (\`h4\`), upřesněním a PlusToggle; po rozbalení „Pomůžeme vám“ a seznam situací se zlatými čtverečky.

- **Rozvržení:** 9 oblastí ve 3 sloupcích (1–3, 4–6, 7–9 shora dolů); rozbalení neposune sousední sloupce.
- **Dodáš:** název, upřesnění, situace jako krátké fráze navazující na „Pomůžeme vám …“ („když vám zákazník neplatí“).
- **Ne:** číslování (01 / 09), dlouhé odstavce v rozbalení.
- Klikni v náhledu.`,
`<div class="ak-dark ak-areas" style="padding:40px"><div class="ak-areas__cols">${area("p1", "Smlouvy", "a obchodní vztahy", ["sepsat smlouvu", "vyjednat smlouvu", "když sjednáváte úvěr"], true)}${area("p2", "Spory", "a vymáhání pohledávek", ["když vám zákazník neplatí", "bránit se žalobě"], false)}${area("p3", "Přeshraniční podnikání", "(CZ / PL)", ["podnikat v Polsku", "zřídit organizační složku"], false)}</div></div>`, pxScript, 1200);

add("Person", "Obsah", 460,
`Člen týmu: portrét 4:5 černobíle (na najetí barva a měřítko 1,02), jméno ve \`h3\` (21 px), role ve stylu \`meta\`.

- **Dodáš:** profesionální portrét se dřevem v pozadí, celé jméno s titulem, roli, odkaz na profil.
- **Rozvržení:** 4 v řadě vedle boční hlavičky, na mobilu 2.`,
`<div class="ak-paper" style="padding:40px;display:grid;grid-template-columns:repeat(2,240px);gap:24px"><a class="ak-person" href="#"><span class="ak-person__img"><img src="${BLOB.portrait}" alt="Mgr. David Troszok" width="600" height="750"></span><span class="ak-person__name">Mgr. David Troszok</span><span class="ak-mono">Advokát</span></a><a class="ak-person" href="#"><span class="ak-person__img"><img src="${BLOB.portrait}" alt="Mgr. David Troszok (barevně)" width="600" height="750" style="filter:none"></span><span class="ak-person__name">Stav po najetí</span><span class="ak-mono">Advokát</span></a></div>`, "", 640);

add("ReferenceCard", "Obsah", 320,
`Reference klienta: papírová karta (\`ak-paper\`, obrys \`ak-ink-08\`), uvozovky \`ak-gold-line\`, citace \`body\` v Source Serif 20 px, dole linka a autor (logo na navy, jinak iniciály na \`ak-gold-soft\`).

- **Dodáš:** skutečnou citaci (nikdy smyšlenou), jméno, firmu nebo kontext. Na polské mutaci zůstává citace v originále.
- **Ne:** číslování (Reference / 01), hvězdičky.`,
`<div class="ak-light" style="padding:40px"><div class="ak-refs__cards" style="grid-column:auto"><figure class="ak-ref ak-card"><svg class="ak-ref__q" aria-hidden="true"><use href="#i-quote"/></svg><blockquote><p>Během naší spolupráce jsme se opakovaně setkali s vysoce profesionálním přístupem, odbornou erudicí a spolehlivostí.</p></blockquote><figcaption class="ak-ref__who"><span class="ak-ref__badge ak-ref__badge--mono" aria-hidden="true">JG</span><span><b>Jiří Gerlich</b><span class="ak-mono">Media CZ, s.r.o.</span></span></figcaption></figure><figure class="ak-ref ak-card"><svg class="ak-ref__q" aria-hidden="true"><use href="#i-quote"/></svg><blockquote><p>Chtěla bych tímto velmi poděkovat paní advokátce Mgr. Dorotě Filipczyk za poskytnuté služby při likvidaci obchodní společnosti v Polsku.</p></blockquote><figcaption class="ak-ref__who"><span class="ak-ref__badge ak-ref__badge--mono" aria-hidden="true">DŠ</span><span><b>Dagmar Šefčíková</b><span class="ak-mono">Likvidace společnosti v Polsku</span></span></figcaption></figure></div></div>`, "", 1100);

add("PostCard", "Obsah", 480,
`Článek v aktualitách: fotka 3:2 černobíle, kategorie (a datum) ve stylu \`meta\`, titulek \`h3\` (24 px), perex \`small\`, TextLink „Číst článek“. Sloupce odděluje svislá linka \`ak-ink-14\`.

- **Dodáš:** náhledový obrázek (vlastní fotka), kategorii, titulek do tří řádků, perex do ~140 znaků.`,
`<div class="ak-paper" style="padding:40px;max-width:420px"><article class="ak-post"><a class="ak-post__img" href="#"><img src="${BLOB.glasses}" alt="" width="800" height="533"></a><span class="ak-mono">Nástupnictví · 23. 11. 2026</span><h3 class="ak-h3"><a href="#">Nástupce nestačí jmenovat: Jak oddělit vlastnictví firmy od jejího řízení</a></h3><p class="ak-small">Dobrý budoucí vlastník nemusí být nejlepším generálním ředitelem a schopný manažer nemusí být členem rodiny.</p><a class="ak-link" href="#">Číst článek ${ico("arrow", true)}</a></article></div>`);

add("PageHeader", "Rozvržení", 300,
`Záhlaví podstránky: tmavý pruh \`ak-navy\` pod hlavičkou, štítek, H1 (\`display\`), lead; volitelně fotka s navy překryvem. Používá ho i stránka výsledků vyhledávání a článek.

- **Dodáš:** štítek (sekce webu), nadpis do dvou řádků, lead do dvou vět.
- Stránka bez PageHeader se automaticky odsadí pod hlavičku.`,
`<section class="ak-page-header ak-dark" style="position:relative;padding:64px 0"><div class="ak-wrap ak-grid"><div style="grid-column:1/10;display:grid;gap:24px"><p class="ak-label">Pro firmy</p><h1 class="ak-display">Právní servis pro stabilní fungování a růst firmy</h1><p class="ak-lead">Smlouvy, spory, zaměstnanci, korporátní agenda i přeshraniční podnikání.</p></div></div></section>`, "", 1440);

add("Search", "Navigace", 360,
`Vyhledávání: overlay přes celou obrazovku (\`ak-navy\` 90 % + rozmazání), štítek „S čím vám můžeme pomoci?“, velké pole v Source Serif na \`ak-paper\`, zlaté tlačítko s lupou, výsledky jako papírový seznam (typ ve \`meta\`, titulek serifem, shoda zvýrazněná \`ak-sand\`).

- **Funkce:** na webu formulář stávajícího Ivory Search (ID 350, živé výsledky od 2 znaků, stejné filtry); téma mění jen vzhled.
- **Ovládání:** otevře lupa v hlavičce (nebo klávesa /), zavře ×, Esc nebo klik mimo; fokus skočí do pole a po zavření se vrátí.`,
`<div class="ak-search is-open" style="position:relative;padding:48px;min-height:360px"><div class="ak-search__inner" style="transform:none"><div class="ak-search__head"><p class="ak-label">S čím vám můžeme pomoci?</p><button type="button" class="ak-search__close" aria-label="Zavřít vyhledávání"><span class="ak-px__ico"></span></button></div><form role="search" action="#"><label><span class="ak-sr">Hledat na webu</span><input type="search" value="polsk" aria-label="Hledat"></label><button type="submit" aria-label="Hledat">${ico("search")}</button></form><div class="ak-search__results"><a class="ak-search__result" href="#"><span>Článek</span><b>Jak efektivně vymáhat pohledávky v <mark>Polsk</mark>u?</b></a><a class="ak-search__result" href="#"><span>Článek</span><b><mark>Polsk</mark>á Sp. z o.o. v korporátní správě</b></a></div></div></div>`, "", 1100);

add("Footer", "Navigace", 360,
`Patička na \`ak-navy\` podle současného webu: logo a údaje společnosti (IČO, zápis C 29686 u Krajského soudu v Ostravě, datová schránka), Další informace (4 PDF), Média + Sledujte nás, Adresa (sídlo a pobočka v Třinci); spodní lišta s copyrightem a právními odkazy.

- **Dodáš:** údaje z Přizpůsobit a tři menu. Sociální sítě se zobrazí, jen když mají vyplněnou adresu.
- Odkazy mají zlatou šipku, ikony sítí jsou čtverce se zlatým obrysem a inverzí na najetí.`,
`<footer class="ak-footer ak-dark"><div class="ak-wrap ak-grid ak-footer__cols"><div class="ak-footer__brand"><img src="${BLOB.logoWhite}" alt="AKKSP advokátní kancelář" width="136" height="34"><h2>AKKSP advokátní kancelář, s.r.o.</h2><p>IČO: 27787541<br>Zápis v obchodním rejstříku:<br>C 29686 u Krajského soudu v Ostravě<br>ID datové schránky: xvwje95</p></div><div class="ak-footer__info"><h2>Další informace</h2><ul><li><a href="#">Mimosoudní řešení spotřebitelských sporů${ico("arrow", true)}</a></li><li><a href="#">Poučení klienta podle AML zákona${ico("arrow", true)}</a></li><li><a href="#">Ochrana osobních údajů${ico("arrow", true)}</a></li><li><a href="#">Cookies${ico("arrow", true)}</a></li></ul></div><div class="ak-footer__media"><h2>Média</h2><ul><li><a href="#">Právní rady veřejnosti (eHutnik)${ico("arrow", true)}</a></li><li><a href="#">Teaser${ico("arrow", true)}</a></li></ul><div><h2>Sledujte nás</h2><div class="ak-footer__icons"><a href="#" aria-label="LinkedIn">${ico("in")}</a><a href="#" aria-label="YouTube">${ico("yt")}</a></div></div></div><div class="ak-footer__addr"><h2>Adresa</h2><p><b>Sídlo</b>Husova 401<br>739 61 Třinec<br>Česká republika</p><p><b>Pobočka</b>Průmyslová 1032<br>739 61 Třinec<br>Česká republika</p></div></div></footer>`, "", 1440);

for (const c of C) {
  const dir = join(out, c.name);
  mkdirSync(dir, { recursive: true });
  writeFileSync(join(dir, "README.md"), `# ${c.name}\n\n${c.readme}\n`);
}

// write previews (separate loop so the width argument can be read)
const widths = { Header: 1440, Hero: 1440, RouteCard: 1440, ContactBar: 1440, PageHeader: 1440, Footer: 1440, ReliefTile: 1200, AreaAccordion: 1200, ReferenceCard: 1100, Search: 1100, Person: 640 };
for (const c of C) {
  const w = widths[c.name] ? ` width=${widths[c.name]}` : "";
  const html = `<!-- @dsCard group="${c.group}" height=${c.height}${w} -->\n<div class="ak-preview">\n${SPRITE}\n${c.body}\n</div>\n${c.extra || ""}\n`;
  writeFileSync(join(out, c.name, "preview.html"), html);
}

// Cover
const cover = `<!-- @dsCard height=300 -->
<div class="cov">
<!--
blocks: ak-navy 480x300 bleed (wide slab), ak-navy-850 strip, ak-gold-soft 96x96, ak-cream ground, ak-paper 2 tiles
arrangement: one tall navy slab from x=480 to the right edge with the logo's 3x3 module cut out of it
pattern: geometric, modular (the logo is a 3x3 grid of squares) -> 9 square tiles, one gold = Třinec
scales: tile 64px (ak-s-8), gap 32px (ak-s-6), corners ak-r-1
-->
<svg class="art" viewBox="0 0 480 300" aria-hidden="true">
  <rect class="navy" x="0" y="0" width="480" height="300"/>
  <rect class="navy2" x="0" y="252" width="480" height="48"/>
  <g transform="translate(176 26)">
    <rect class="tile paper" x="0" y="0" width="64" height="64"/><rect class="tile paper" x="96" y="0" width="64" height="64"/><rect class="tile paper" x="192" y="0" width="64" height="64"/>
    <rect class="tile paper" x="0" y="96" width="64" height="64"/><rect class="tile gold" x="96" y="96" width="64" height="64"/><rect class="tile paper" x="192" y="96" width="64" height="64"/>
    <rect class="tile paper" x="0" y="192" width="64" height="64"/><rect class="tile paper" x="96" y="192" width="64" height="64"/><rect class="tile paper" x="192" y="192" width="64" height="64"/>
  </g>
</svg>
<div class="txt"><h1>AKKSP Design<br>System</h1><p>Klidná jistota na hranici dvou zemí: Třinec, Česko a Polsko.</p></div>
</div>
<style>
.cov{position:relative;width:960px;height:300px;background:var(--ak-cream);overflow:hidden}
.art{position:absolute;left:480px;top:0;width:480px;height:300px}
.navy{fill:var(--ak-navy)}.navy2{fill:var(--ak-navy-850)}
.paper{fill:var(--ak-paper)}.gold{fill:var(--ak-gold-soft)}
.tile{rx:var(--ak-r-1)}
.txt{position:absolute;left:40px;bottom:40px;max-width:420px}
.txt h1{margin:0;font-family:var(--font-serif);font-weight:600;font-size:64px;line-height:.95;letter-spacing:-.02em;color:var(--ak-ink)}
.txt p{margin:14px 0 0;font-family:var(--font-sans);font-size:14px;line-height:1.5;color:var(--ak-ink-64)}
</style>
`;
mkdirSync(join(out, "Cover"), { recursive: true });
writeFileSync(join(out, "Cover", "preview.html"), cover);
console.log(`components: ${C.length} + Cover`);
