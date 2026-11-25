# 04 · Implementace do WordPressu (návrh postupu)

## Výchozí stav (zjištěno ze zdrojového kódu aktrinec.cz)

- WordPress, téma **Hello Elementor**, stavitel **Elementor 4.4.1**
- **Polylang** (CZ / PL / EN), **Complianz** (cookie lišta), **Rank Math** (SEO), **LiteSpeed Cache**, **Ivory Search** (hledání v menu)
- Elementor má zapnuté načítání **Google Fonts z CDN Googlu**

## Doporučená cesta: zůstat na Elementoru, postavit vlastní design systém

Důvody: redakce Elementor zná, Polylang i Complianz jsou napojené, odpadá migrace obsahu. Redesign je o vzhledu, ne o výměně platformy.

1. **Child téma** `akksp` nad Hello Elementor (vlastní CSS a JS v repozitáři, verzované).
2. **Elementor Site Settings = tokeny:** Global Colors (tabulka z `03-brand-tokeny.md`), Global Fonts (vybraná sada), Theme Style pro tlačítka a nadpisy.
3. **Vlastní CSS vrstva** v child tématu pro to, co Elementor sám neumí čistě: sklo (`backdrop-filter`), neumorfní stíny, rotace „+ → ×", dělená tlačítka, mřížka 3×3.
4. **Šablony (Theme Builder):** header, footer, single článek, archiv blogu, profil člena týmu.
5. **Sekce homepage jako uložené šablony / komponenty**, aby šly použít i na podstránkách (Pro firmy, Česko / Polsko …).
6. **Dynamický obsah:** tým a články přes Loop Grid (data z CPT / příspěvků, ne ručně), reference jako vlastní typ obsahu.
7. **Accordion „+ → ×":** widget Nested Accordion s vlastními ikonami + CSS rotace, nebo malý vlastní widget.
8. **Přepínač CZ / PL** v hero (varianta C) napojený na Polylang (přepíná jazykovou verzi stránky, ne jen text).

### Alternativa: blokové téma (Gutenberg / FSE)
Rychlejší a čistší kód, `theme.json` přímo nese tokeny, žádná závislost na Elementoru. Znamená ale převést obsah a naučit redakci nový editor. Dává smysl, pokud se stejně plánuje větší úprava obsahu. Rozhodneme po výběru variant.

## Písma a GDPR

- **Vypnout Google Fonts v Elementoru** a písma hostovat lokálně (Elementor → Custom Fonts, nebo `@font-face` v child tématu, formát WOFF2, subset latin + latin-ext).
- Důvod: načítání z CDN Googlu předává IP adresu návštěvníka do USA; v EU už kvůli tomu padly pokuty (rozsudek LG München z roku 2022). Pro advokátní kancelář zbytečné riziko.
- OFL licence vlastní hosting výslovně dovoluje.

## Výkon a přístupnost

- Obrázky: WebP/AVIF, `srcset`, lazy-load mimo hero; hero obrázek s `fetchpriority="high"`.
- Fotky z webu mají až 1 MB (portréty 1200 px); připravit sady 400 / 800 / 1200 px.
- `backdrop-filter` jen na jedné vrstvě na obrazovku; na mobilu nižší blur.
- Cíl: LCP pod 2,5 s, CLS ~0, celé ovládání klávesnicí, kontrast AA.

## Postup nasazení

1. **Staging** (kopie webu na subdoméně, neindexovat).
2. Child téma + tokeny + šablony header/footer.
3. Homepage CZ → kontrola → PL verze (Polylang).
4. Podstránky po skupinách (služby, tým, blog, kontakty).
5. Test: mobily, Safari (sklo), čtečka obrazovky, rychlost (PageSpeed), cookie lišta.
6. Přepnutí na produkci, zachování URL (žádné ztráty SEO), přesměrování jen kde se adresa mění.

## Otevřené otázky pro fázi 2

- Které varianty jednotlivých prvků (výběr z moodboardu)?
- Elementor, nebo blokové téma?
- Kdo dodá PL korekturu textů?
- Máme fotky ze školení / workshopů? (pro sekci Školení)
- Ověřit čísla: délka praxe, velikost týmu, spisová značka.
