# 06 · WordPress: instalace a práce s tématem AKKSP

Child téma `wordpress/akksp-child/` (rodič: **Hello Elementor**). Balíček k nahrání: `wordpress/dist/akksp-child-1.0.0.zip`.

## Co téma obsahuje

| Část | Kde | Poznámka |
|---|---|---|
| Hlavička (plovoucí skleněná navigace, mobilní menu, CZ \| PL) | `header.php` | menu „AKKSP: hlavní menu"; přepínač jazyků z Polylangu |
| Patička (struktura jako dnes) | `footer.php` | 3 menu + údaje z Přizpůsobit |
| Údaje kanceláře | Vzhled → Přizpůsobit → **AKKSP: kontakty a firma** | telefon, e-mail, IČO, sídlo, pobočka, LinkedIn, YouTube |
| 10 Elementor widgetů | panel Elementoru → kategorie **AKKSP sekce** | výchozí obsah = schválená homepage |
| Design system | `assets/css/` (kopie `site/css/`) | needitovat v tématu, ale v `site/` a spustit build |
| Lokální písma | `assets/fonts/` | stáhne `tools/get-fonts.ps1`; Google Fonts z Elementoru jsou vypnutá |
| Globální barvy a písma Elementoru | nastaví se při aktivaci tématu | Site Settings → Global Colors „AKKSP …" |

Funguje s Elementorem zdarma; Elementor Pro není potřeba. Pokud v Pro existuje vlastní hlavička/patička (Theme Builder), má přednost.

### Widgety

| Widget | Sekce |
|---|---|
| AKKSP · Hero + rozcestník | fotka, skleněný panel, přepínač CZ / PL, 3 karty |
| AKKSP · Záhlaví podstránky | tmavý pruh s H1 pro podstránky |
| AKKSP · Kontaktní lišta | telefon, e-mail, kanceláře, CTA (prázdná pole = údaje z Přizpůsobit) |
| AKKSP · Proč AKKSP (čísla) | dlaždice s reliéfem; zvýrazněná = písková |
| AKKSP · Oblasti právní pomoci | 9 skleněných karet, „+ → ×", situace po řádcích |
| AKKSP · Tým | portréty černobílé → barva |
| AKKSP · Reference | citace + galerie log klientů |
| AKKSP · Banner s fotkou (Školení) | fotka + skleněný panel vlevo/vpravo |
| AKKSP · Aktuality z blogu | posledních N článků (kategorie volitelná) |
| AKKSP · Hlavička sekce | štítek \| nadpis \| akce pro libovolný obsah |

## Instalace na staging (doporučený postup)

1. **Staging:** kopie webu na subdoméně (např. `staging.aktrinec.cz`), Nastavení → Zobrazování: *Požádat vyhledávače…* zapnout.
2. **Záloha** produkce (soubory + databáze).
3. Ověřit, že je nainstalované téma **Hello Elementor** (rodič).
4. Na počítači: `.\tools\get-fonts.ps1` (stáhne písma) a `.\tools\build-theme.ps1` (sestaví ZIP).
5. Vzhled → Motivy → Přidat → Nahrát motiv → `akksp-child-1.0.0.zip` → **Aktivovat**.
6. Elementor → Nastavení:
   - *Disable Default Colors* a *Disable Default Fonts*: zaškrtnuto (téma nastaví samo),
   - Elementor → Nastavení → Pokročilé → *Google Fonts*: **Disable**,
   - *Font-display*: swap.
7. Vzhled → Menu:
   - **AKKSP: hlavní menu**: Pro firmy, Pro jednotlivce, Česko / Polsko, O nás, Zkušenosti, Školení a workshopy, Právní poradna,
   - **AKKSP: patička – Další informace**: 4 PDF (spotřebitelské spory, AML, osobní údaje, cookies),
   - **AKKSP: patička – Média**: Právní rady veřejnosti (eHutnik), Teaser,
   - **AKKSP: patička – spodní lišta**: Ochrana osobních údajů, Cookies, Kontakty,
   - pro polskou verzi vytvořit stejná menu v PL (Polylang → Jazyky → Menu).
8. Přizpůsobit → AKKSP: kontakty a firma → zkontrolovat údaje (spisová značka **C 29686**), doplnit LinkedIn / YouTube nebo nechat prázdné (sloupec se skryje).
9. **Homepage:** nová stránka → Upravit pomocí Elementoru → šablona stránky **Elementor Full Width** → postupně vložit widgety:
   Hero → Kontaktní lišta → Proč AKKSP → Oblasti → Tým → Reference → Školení → Aktuality.
   Každý widget do vlastního kontejneru, nebo všechny do jednoho; téma vynuluje okraje kontejnerů samo.
10. Nastavení → Zobrazování → statická úvodní stránka = nová homepage.
11. V Reference doplnit galerii log klientů z knihovny médií (po souhlasu klientů).
12. Polylang: přeložit homepage do PL (Jazyky → přidat překlad), v Hero nechat režim „Odkazy na jazykové verze".
13. LiteSpeed Cache: po nasazení *Purge All*; pokud je zapnutá optimalizace CSS/JS, ověřit, že nerozbíjí `site.js` (případně vyloučit `akksp-site`).
14. Complianz: beze změny (písma jsou lokální, žádné nové cookies).

## Podstránky

Začínají widgetem **Záhlaví podstránky** (navigace je pak přes tmavý pruh), pod ním běžné Elementor widgety nebo **Hlavička sekce** + obsah. Stránky bez tmavého záhlaví téma automaticky odsadí pod navigaci.

## Úpravy designu

1. Upravit `site/css/*.css` nebo `site/js/site.js` (prototyp `site/index.html` slouží jako náhled).
2. `.\tools\build-theme.ps1` → nový ZIP; zvednout `Version` v `wordpress/akksp-child/style.css`.
3. Nahrát přes Vzhled → Motivy (WordPress nabídne nahrazení) nebo přes FTP jen změněné soubory.

## Lokální test (WordPress Playground)

Bez serveru a databáze, jen Node.js. Konfigurace je v `.claude/launch.json` (název `wordpress`), ručně:

```powershell
npx -y @wp-playground/cli@latest server --port=9400 --mount=./wordpress/akksp-child:/wordpress/wp-content/themes/akksp-child --mount=./tools/playground:/wordpress/akksp-seed --blueprint=./tools/playground/blueprint.json
```

Po startu (cca 1–2 min) jednou otevřít `http://127.0.0.1:9400/akksp-seed/seed.php`: aktivuje téma, vytvoří homepage ze všech widgetů, podstránku „Pro firmy", stránku Kontakty, menu a 3 články. Web je pak na `http://127.0.0.1:9400/`. Instance je dočasná (po zastavení zmizí). Složka `tools/playground` se na produkci nenahrává.

**Výsledek testu (25. 11. 2026, WordPress latest, PHP 8.3, Hello Elementor + Elementor z wordpress.org):**
- homepage: všech 8 widgetů se vykreslí, žádné PHP chyby ani varování, žádný požadavek na Google Fonts, lokální písma a preload načtené,
- editor Elementoru: všech 10 widgetů v kategorii „AKKSP sekce", úprava textu se v náhledu projeví ihned, „+ → ×" funguje i v editoru,
- podstránka se Záhlavím podstránky (tmavý vrch), běžná stránka bez Elementoru (odsazení pod navigaci), článek: bez chyb,
- mobil 390 px: bez vodorovného přetékání, mobilní menu s číslováním funguje,
- opraveno během testu: reset Hello Elementor (růžové rámečky tlačítek) vypnut, Elementor rušil obrys sekundárních tlačítek a sklo na odkazech (vráceno v `wp.css`).

Netestováno: Polylang (přepínač ve variantě odkazů), Elementor Pro, LiteSpeed Cache, Complianz, Safari.

## Kontrolní seznam před spuštěním

- [ ] Hlavní menu a patička v CZ i PL
- [ ] Údaje v Přizpůsobit (IČO, spisová značka, adresy)
- [ ] Homepage: texty ověřeny (30+ / 15+), polské texty zkontrolovány
- [ ] Loga klientů schválena
- [ ] Kontaktní formulář (stávající) umístěn na stránku Kontakty
- [ ] Písma se načítají lokálně (DevTools → Network: žádný požadavek na fonts.googleapis.com)
- [ ] Mobil 390 px, Safari (sklo), klávesnice, PageSpeed
- [ ] Přesměrování starých URL, pokud se mění
