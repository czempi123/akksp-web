# 08 · Druhá vlna připomínek (homepage v1.2)

| # | Připomínka | Řešení | Kde |
|---|---|---|---|
| 1 | Kontakt musí být vidět hned, na každé obrazovce | Tlačítko **Kontakt** je vždy v hlavičce (i na mobilu). Otevře **kontaktní panel** zprava: velké telefonní číslo a e-mail (celé plochy klikací), sídlo, pobočka, datová schránka a formulář. Hero má druhé tlačítko s telefonem. Panel otevře i jakýkoli odkaz na `#kontakt` (bez JS funguje přes `:target`) | `.ak-drawer`, `akksp_contact_drawer()` |
| 2 | Fotky týmu: částečně odbarvené, při najetí plná barva | `saturate(.35)` → plná barva za 0,9 s; stejně u fotek článků | `sections.css` (Tým, Aktuality) |
| 3 | Hero bez rámečku, každý prvek má svůj prostor | Bez skleněného panelu a štítku. Čitelnost drží tmavý přechod zleva a zespodu. Tři fotky kanceláře se **samy** pomalu prolínají s jemným přiblížením (7 s, bez ovládacích prvků; stojí mimo obrazovku, při omezeném pohybu jen první fotka). Rozcestník je samostatný světlý pruh pod hero | widget **Hero + rozcestník** (galerie fotek) |
| 4 | „Pracujeme pro“ → „Reference“ | Přejmenováno | widget Reference |
| 5 | Oblasti podle aktrinec.cz/pravni-sluzby-trinec, bez „Všechny oblasti“ | 9 oblastí: Firma, Obchod, Polsko, Rodina, Majetek, Práce, Problémy, © ® ™, Život, se seznamy ze současného webu. Tlačítko i štítek „Oblasti naší právní pomoci“ odstraněny | widget Oblasti |
| 6 | Patička: jednotná struktura, šipky přetékaly, duplicity, cookies | Značka · Kontakt · Adresy · Dokumenty · Média. Bez šipek a štítků u odkazů. Ochrana údajů a cookies jen jednou (v Dokumentech). Spodní lišta: © a **Nastavení cookies** (znovu otevře lištu Complianz). Cookie lišta má obsah ze současného webu (Complianz), mění se jen vzhled | `footer.php`, `wp.css` (Complianz) |
| 7 | Kontakt po kliknutí maximálně zřejmý | viz bod 1 | |
| 8 | Inverze tlačítka nepřebarvila celé tlačítko | Obrys je vrstva `::after` nad obsahem, takže při najetí obepne i šipkový modul; celé tlačítko je navy se zlatým obrysem | `components.css` (.ak-btn) |
| 9 | Jen firemní LinkedIn, YouTube ne | YouTube odstraněn všude; LinkedIn v patičce (URL v Přizpůsobit) | |
| 10 | Anglická verze | **EN jako třetí jazyk ve stejném přepínači CZ \| PL \| EN** (jedna logika, jedno místo). Ukázka `site/en/` | `tools/make-lang.ps1`, `tools/i18n/en.json` |
| 11 | Neopakovat firmu nad H1 | Štítek nad H1 zrušen | |
| 12 | V hlavičce rolovací lišta místo přímého přehledu stránek | **Žádný burger.** Hlavička je jeden řádek: logo · celé menu · hledání · CZ \| PL \| EN · Kontakt. Menu se rozloží po volné šířce; na užších obrazovkách se zhustí. Jen pod 1220 px (tablet, mobil), kde se 7 položek fyzicky nevejde vedle loga, přejde menu jako druhý řádek pod logo (na mobilu vodorovně posuvný) | `sections.css` (Hlavička) |

Další úpravy během vlny:

- Horní kontaktní řádek nad menu byl vyzkoušen a zrušen (stačí tlačítko Kontakt).
- Po odscrollování se hlavička zmenší jen nepatrně (80 → 74 px).
- Dlaždice „Česko & Polsko“ je stejná jako ostatní (bez zlatého rámečku); ampersand jen tlumený.
- Ikona telefonu při najetí „zazvoní“, obálka se nadzvedne, šipka se dvakrát pružně „pošťouchne“ vpřed (už nemizí a neobjevuje se).
- Česká typografie: jednopísmenné předložky (v, s, k…) nezůstávají na konci řádku (`akksp_typo()`).

## Struktura webu (připomínka zadavatele)

Minimum vrstev: homepage odkazuje na podstránky, z podstránek se dál neodkazuje, výjimkou jsou profily lidí a články blogu.

## WordPress (téma 1.2.0)

- Přizpůsobit → AKKSP: kontakty a firma: nově **ID datové schránky** a **shortcode formuláře do kontaktního panelu** (stávající formulář webu, např. šablona Elementor Pro); YouTube a „Odkaz tlačítka Kontakt“ odstraněny.
- Menu: „AKKSP: patička – Dokumenty“ (4 PDF), „AKKSP: patička – Média“. Menu spodní lišty zrušeno.
- Widget Hero: galerie fotek (1 fotka = statická), délka fotky, hlavní tlačítko (výchozí `#kontakt`), tlačítko s telefonem.
- Complianz: texty a logika beze změny; vzhled lišty přes proměnné Complianz ve `wp.css`. Plovoucí záložku „Spravovat souhlas“ lze v Complianz vypnout (odkaz je v patičce).
- Otestováno v Playground: homepage, podstránka, vyhledávání bez PHP chyb (hlášky Elementoru o zápisu do uploads jsou dané prostředím Playground).

## Ověřeno

- Prototyp CZ / PL / EN: hlavička v jednom řádku od 1220 px nahoru ve všech jazycích (změřeno), 1024 a 375 px bez přetékání, kontaktní panel (otevření, fokus, Esc, validace formuláře), cookie lišta (předvolby, uložení), vyhledávání, prolínání fotek.

## Otevřené

- URL firemního LinkedInu.
- Krakov, čísla 30+ / 15+, kontrola PL a EN textů.
- Na stagingu: který stávající formulář vložit do kontaktního panelu (shortcode).
- Design System artefakt zatím odpovídá verzi 1.1 (hlavička, hero, patička, panel a cookie lišta k doplnění).
