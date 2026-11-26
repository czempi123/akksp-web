# 10 · Zveřejnění prototypu (náhled pro kolegy)

Repozitář: https://github.com/czempi123/akksp-web (soukromý). Web jsou statické soubory, nic se nesestavuje.

## Nastavení hostingu (Netlify nebo Cloudflare Pages)

- Zdroj: GitHub, repozitář `czempi123/akksp-web`, větev `main`.
- Build command: žádný (prázdné). Publish / output directory: kořen repozitáře (`/`).
- Kořen přesměruje na `site/` (`index.html`), takže adresa webu rovnou ukazuje homepage.
- Každý `git push` do `main` web automaticky aktualizuje.

## Aby prototyp nenašly vyhledávače

- Každá stránka má `<meta name="robots" content="noindex">`.
- `_headers` posílá `X-Robots-Tag: noindex, nofollow` (Netlify i Cloudflare Pages ho čtou).
- `robots.txt` zakazuje procházení celého webu.
- Adresa je neveřejná, ale kdo ji zná, web uvidí (bez hesla). Heslo umí Netlify jen v placeném tarifu; u Cloudflare Pages jde přes Cloudflare Access (zdarma do 50 uživatelů).
