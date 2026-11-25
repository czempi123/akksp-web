# PlusToggle

Přepínač „+ → ×“ pro rozbalování (oblasti, FAQ). Čtverec 40 px, dvě čáry 14 px.

- **Stavy:** zavřeno = obrys (`ak-ink-44` na světlé, `ak-gold-40` na tmavé); najetí = výplň; otevřeno = `ak-gold-soft` a rotace o 45° na „×“ (500 ms); otevřeno + najetí = `ak-navy` se zlatým ×.
- **Dodáš:** `<button aria-expanded="false" aria-controls="…">`; stav nese atribut, ne jen tvar.
- Klikni v náhledu pro otevření.
