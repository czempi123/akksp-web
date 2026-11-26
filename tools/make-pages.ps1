# Sestavi podstranky prototypu: site/<slug>/index.html (slug muze mit i vic urovni, napr. pripadove-studie/xyz)
# Hlavicka, vyhledavani, kontaktni panel, paticka a cookie lista se berou ze site/index.html
# (jeden zdroj pravdy), obsah stranky z tools/pages/<slug>.html.
# Pouziti: .\tools\make-pages.ps1   (po kazde zmene homepage nebo obsahu podstranky)
$ErrorActionPreference = 'Stop'
$root = Resolve-Path "$PSScriptRoot\.."
$utf8 = New-Object Text.UTF8Encoding $false

# slug, <title>, meta description, fotka do nahledu (preload + og:image)
$pages = @(
  @{ slug = 'pro-jednotlivce'; title = 'Pro jednotlivce · AKKSP advokátní kancelář';
     desc = 'Právní jistota v důležitých životních situacích: rodina, nemovitosti, dědictví, pracovní právo, spory a obhajoba. AKKSP advokátní kancelář, Třinec.';
     img  = '2024-11-stolek.jpg' },
  @{ slug = 'pro-firmy'; title = 'Pro firmy a podnikatele · AKKSP advokátní kancelář';
     desc = 'Právní podpora pro firmy a podnikatele v Česku a Polsku: smlouvy, pracovní právo, korporátní agenda, M&A a přeshraniční podnikání.';
     img  = '2025-01-schody-3.jpg' },
  @{ slug = 'o-nas'; title = 'O nás · AKKSP advokátní kancelář';
     desc = 'AKKSP advokátní kancelář: právní partner v Česku a Polsku s více než 30 lety zkušeností. Kdo jsme, reference a případové studie.';
     img  = '2024-11-20241114_135120.jpg' },
  # Pripadove studie (3. vrstva, jako profily lidi a clanky)
  @{ slug = 'pripadove-studie/akvizice-polsko'; title = 'Akvizice výrobní společnosti v Polsku · Případová studie · AKKSP';
     desc = 'Případová studie AKKSP: komplexní právní podpora při akvizici výrobní společnosti v Polsku, od due diligence po uzavření transakce.';
     img  = '2025-01-schody-3.jpg' },
  @{ slug = 'pripadove-studie/restrukturalizace-holdingu'; title = 'Restrukturalizace skupiny a optimalizace holdingu · Případová studie · AKKSP';
     desc = 'Případová studie AKKSP: restrukturalizace skupiny společností, nastavení efektivní holdingové struktury a snížení rizik.';
     img  = '2024-11-bryle.jpg' }
)

$homePage = [IO.File]::ReadAllText((Join-Path $root 'site\index.html'), $utf8)
$open = '<main id="obsah">'; $close = '</main>'
$top = $homePage.Substring(0, $homePage.IndexOf($open) + $open.Length)
$bottom = $homePage.Substring($homePage.IndexOf($close))

foreach ($p in $pages) {
  $t = $top
  # hlavicka dokumentu
  $t = [regex]::Replace($t, '<title>.*?</title>', "<title>$($p.title)</title>")
  $t = [regex]::Replace($t, '(<meta name="description" content=")[^"]*', "`${1}$($p.desc)")
  $t = [regex]::Replace($t, '(<meta property="og:title" content=")[^"]*', "`${1}$($p.title)")
  $t = [regex]::Replace($t, '(<meta property="og:description" content=")[^"]*', "`${1}$($p.desc)")
  $t = [regex]::Replace($t, '\s*<link rel="alternate" hreflang[^>]*>', '')
  # hloubka stranky: 'o-nas' = 1, 'pripadove-studie/akvizice-polsko' = 2 -> $up = cesta zpet do site/
  $up = '../' * ($p.slug.Split('/').Count)
  $t = [regex]::Replace($t, '(<link rel="preload" as="image" href=")[^"]*', "`${1}$up../assets/aktrinec/$($p.img)")
  $t = [regex]::Replace($t, '(<meta property="og:image" content=")[^"]*', "`${1}$up../assets/aktrinec/$($p.img)")
  $t = $t.Replace('<link rel="stylesheet" href="css/sections.css?v=5">', "<link rel=`"stylesheet`" href=`"css/sections.css?v=5`">`n  <link rel=`"stylesheet`" href=`"css/pages.css?v=6`">")
  # cesty o uroven(e) niz
  $t = $t.Replace('"../assets/', "`"$up../assets/").Replace('"../wordpress/', "`"$up../wordpress/")
  $t = $t.Replace('href="css/', "href=`"${up}css/")
  $t = $t.Replace('class="ak-nav__logo" href="./"', "class=`"ak-nav__logo`" href=`"$up`"")
  $t = $t.Replace('href="pl/" hreflang', "href=`"${up}pl/`" hreflang").Replace('href="en/" hreflang', "href=`"${up}en/`" hreflang")
  # menu: kotvy vedou na homepage, podstranky o uroven(e) vys, aktualni polozka oznacena
  $t = [regex]::Replace($t, '(?s)<ul class="ak-nav__menu">.*?</ul>', {
    param($m)
    $s = [regex]::Replace($m.Value, 'href="(?!https?:|mailto:|tel:|\.\./)', "href=`"$up")
    $s.Replace("href=`"$up$($p.slug)/`"", 'href="./" aria-current="page"')
  })
  $b = $bottom.Replace('"../assets/', "`"$up../assets/").Replace('src="js/', "src=`"${up}js/")
  $body = [IO.File]::ReadAllText((Join-Path $root "tools\pages\$($p.slug).html"), $utf8)
  $out = Join-Path $root "site\$($p.slug)"
  New-Item -ItemType Directory -Force $out | Out-Null
  [IO.File]::WriteAllText((Join-Path $out 'index.html'), "$t`n`n$($body.TrimEnd())`n`n$b", $utf8)
  Write-Output "site/$($p.slug)/index.html"
}
