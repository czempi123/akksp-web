# Sestavi podstranky prototypu: site/<slug>/index.html
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
     img  = '2024-11-20241114_135120.jpg' }
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
  $t = [regex]::Replace($t, '(<link rel="preload" as="image" href=")[^"]*', "`${1}../../assets/aktrinec/$($p.img)")
  $t = [regex]::Replace($t, '(<meta property="og:image" content=")[^"]*', "`${1}../../assets/aktrinec/$($p.img)")
  $t = $t.Replace('<link rel="stylesheet" href="css/sections.css?v=5">', "<link rel=`"stylesheet`" href=`"css/sections.css?v=5`">`n  <link rel=`"stylesheet`" href=`"css/pages.css?v=3`">")
  # cesty o uroven niz
  $t = $t.Replace('"../assets/', '"../../assets/').Replace('"../wordpress/', '"../../wordpress/')
  $t = $t.Replace('href="css/', 'href="../css/')
  $t = $t.Replace('class="ak-nav__logo" href="./"', 'class="ak-nav__logo" href="../"')
  $t = $t.Replace('href="pl/" hreflang', 'href="../pl/" hreflang').Replace('href="en/" hreflang', 'href="../en/" hreflang')
  # menu: kotvy vedou na homepage, podstranky o uroven vys, aktualni polozka oznacena
  $t = [regex]::Replace($t, '(?s)<ul class="ak-nav__menu">.*?</ul>', {
    param($m)
    $s = [regex]::Replace($m.Value, 'href="(?!https?:|mailto:|tel:|\.\./)', 'href="../')
    $s.Replace("href=`"../$($p.slug)/`"", 'href="./" aria-current="page"')
  })
  $b = $bottom.Replace('"../assets/', '"../../assets/').Replace('src="js/', 'src="../js/')
  $body = [IO.File]::ReadAllText((Join-Path $root "tools\pages\$($p.slug).html"), $utf8)
  $out = Join-Path $root "site\$($p.slug)"
  New-Item -ItemType Directory -Force $out | Out-Null
  [IO.File]::WriteAllText((Join-Path $out 'index.html'), "$t`n`n$($body.TrimEnd())`n`n$b", $utf8)
  Write-Output "site/$($p.slug)/index.html"
}
