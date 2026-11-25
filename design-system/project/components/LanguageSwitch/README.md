# LanguageSwitch

Přepínač jazykových mutací celého webu: segment CZ | PL se zlatým jezdcem na aktuálním jazyce.

- **Chování:** odkazy na protějšek aktuální stránky (Polylang). Jezdec nejdřív plynule dojede (260 ms), pak se načte druhá mutace. Nikdy nepřepíná jen kus textu na stránce.
- **Kde:** hlavička, mobilní menu, panel v hero.
- **Dodáš:** seznam jazyků; `--n` = počet, `--pos` = pořadí aktuálního.
