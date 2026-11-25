# 07 · Finální design v1.1 (připomínky ke homepage)

| # | Připomínka | Řešení | Kde |
|---|---|---|---|
| 1 | Hlavička schovaná v plovoucí liště | Navy lišta `ak-navy-850` přes celou šířku, přichycená nahoře jako v konceptu; logo oddělené linkou; po odscrollování nižší (80 → 64 px) se stínem | `sections.css` (Hlavička), `header.php` |
| 2 | Inverze modrá ↔ zlatá při najetí | Všechna modrozlatá tlačítka, šipkové moduly, „+“, lupa, ikony sítí a jazykový přepínač si plynule (350 ms) prohodí barvy; inverzní stav má 1px zlatý obrys, takže nesplyne s navy pozadím | `components.css` (proměnné `--inv-*` v `base.css`) |
| 3 | Zbytečné nadpisy a číslování | Odstraněno 01 / 09, 01 / 03, „01 · O kanceláři“, „Reference / 01“, štítky dlaždic. Štítek zůstal jen u hero a u „Oblasti naší právní pomoci“ (byly v konceptu) | HTML, widgety |
| 4 | Uříznuté „ý“ a další drobné vady | Maska odkrytí nadpisů má přesah, aby neřezala háčky a dotahy; tmavší sklo v hero a školení (rozmazané předměty z fotky už nepůsobí jako šmouha); uvozovky referencí jako ikona (nepřekrývají text); šipka v patičce už neodskakuje na nový řádek; zalamování menu vyřešeno | `base.css`, `sections.css` |
| 5 | Pryč „Třinec · Praha · Krakov“ | Odstraněno z hero (CZ i widget) | |
| 6 | CZ / PL = celé mutace | Přepínač vede na jazykovou verzi celé stránky (Polylang); jezdec plynule dojede a pak se načte mutace. Ukázková polská homepage `site/pl/` | `site/pl/`, `akksp_lang_switch()` |
| 7 | Vyžlucení „Česko & Polsko“ | Bez pískové výplně: jen jemný zlatý obrys dlaždice, tmavý čtvereček a ampersand v `ak-amber` | `ak-tile--accent` |
| 8 | Ucelený manuál pro celý web | **AKKSP Design System** (artefakt + `design-system/`): brand book, tokeny, 17 komponent s živými náhledy, logo, fotky, pravidla stavby podstránek | `design-system/project/` |
| 9 | Stávající vyhledávač | Funkčně zůstává **Ivory Search Premium** (formulář ID 350, živé výsledky, filtry). Téma ho vkládá do overlaye otevíraného lupou a stylizuje pole i výsledky; nová šablona výsledků `search.php` | `header.php`, `search.php`, `components.css` |
| 10 | Kancelář v Praze neexistuje | Praha odstraněna všude (hero, kontaktní lišta, výchozí hodnoty tématu). Kontaktní lišta uvádí „Kancelář: Husova 401, Třinec“ | |
| 11 | Čistší animace šipky | Šipka ve čtvercovém modulu při najetí „projede“: odjede vpravo a vrátí se zleva (520 ms); karta se už nezvedá, jen zesvětlí a dostane zlatý obrys | `@keyframes ak-arrow-through` |

## Ověřeno

- Prototyp: snímky 1440 px (CZ, PL), úzké zobrazení, 390 px bez vodorovného přetékání; inverze při najetí změřena v prohlížeči (primární: navy + zlatý text + zlatý obrys; sekundární na tmavé: zlatá výplň); vyhledávání (otevření, fokus, výsledky, Esc); PL stránka bez chybějících obrázků.
- WordPress (Playground, Hello Elementor + Elementor): homepage, podstránka, běžná stránka a stránka výsledků bez PHP chyb; výsledky vyhledávání se vykreslí v novém vzhledu.

## Otevřené

- Polské texty (`tools/i18n/pl.json`) zkontrolovat rodilým mluvčím.
- Krakov: v návrhu se již neuvádí; pokud tam kancelář je, doplnit do Přizpůsobit.
- Čísla 30+ / 15+ ověřit.
- Ivory Search otestovat na stagingu s jeho skutečnou konfigurací (v lokálním testu plugin není, protože je placený).
