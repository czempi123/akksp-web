# 09 · Podstránky Pro jednotlivce a Pro firmy a podnikatele

Podle konceptů `assets/concept/pro-jednotlivce-koncept-v1.webp` a `pro-firmy-koncept-v1.webp`, převedeno do design systému homepage.

## Jak jsou stránky postavené

- Obsah: `tools/pages/<slug>.html` (jen sekce mezi hlavičkou a patičkou).
- Hlavička, vyhledávání, kontaktní panel, patička a cookie lišta se berou ze `site/index.html` (jeden zdroj).
- Sestavení: `.\tools\make-pages.ps1` vytvoří `site/pro-jednotlivce/` a `site/pro-firmy/`. Spustit po každé změně homepage nebo obsahu.
- Styly navíc: `site/css/pages.css` (záhlaví podstránky se šikmým řezem fotky a drobečky, úvod, témata, tým v řadě, 3 reference, články, výzva, světlé karty oblastí s „+ → ×", případové studie, pruh Školení, rozcestník 2 karty, kontaktní lišta s ikonami).

## Prokliky

- Homepage: menu „Pro firmy" a „Pro jednotlivce" + karty rozcestníku vedou na podstránky.
- Podstránky: aktivní položka menu je podtržená, kotvy menu (O nás, Zkušenosti…) vedou na homepage, na konci rozcestník na sesterskou stránku.
- PL / EN homepage: odkazy zatím vedou na `#oblasti` (podstránky jsou jen česky), páry v `tools/i18n/*.json`.

## Rozdíly proti konceptu

- Tým podle zadání: jednotlivci = Troszok, Kadlubcová Škutová, Czempka, Večeřa; firmy = Filipczyk, O. Kadlubiec, Kantorová, Tomoszek, Mrózek (fotky a odkazy z aktrinec.cz).
- „Co řešíme pro firmy": karty se rozbalují (seznamy podle aktrinec.cz), protože z podstránek se dál neodkazuje.
- „Mohlo by vás zajímat": tři skutečné články z blogu (na blogu nejsou data, proto štítek tématu místo data).
- Fotky: koncept používá ilustrační fotky (rodina, skleněná budova); zatím vlastní fotky kanceláře.

## Otevřené (v kódu označeno `data-todo`)

- Reference na stránce Pro jednotlivce a třetí reference na Pro firmy jsou ukázkové texty z konceptu, nahradit skutečnými.
- Případové studie jsou ukázka z konceptu, nahradit skutečnými (se souhlasem klientů).
- Kontaktní lišta: „Třinec, Praha, Krakov" ověřit.
- Karta „Česko / Polsko" vede zatím na oblasti na homepage, dokud nevznikne podstránka.
- WordPress: nové sekce zatím nejsou jako Elementor widgety (`pages.css` se do tématu zatím nekopíruje).
