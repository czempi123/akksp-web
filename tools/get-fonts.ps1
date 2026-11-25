# Stahne pisma (SIL OFL 1.1) z Google Fonts jako WOFF2 do child tematu
# a vytvori assets/fonts/fonts.css s lokalnimi @font-face (subsety latin + latin-ext).
# Pouziti: .\tools\get-fonts.ps1
param(
  [string]$Out = "$PSScriptRoot\..\wordpress\akksp-child\assets\fonts"
)
$ErrorActionPreference = 'Stop'
$ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36'
$families = @(
  @{ q = 'Source+Serif+4:opsz,wght@8..60,400..700'; slug = 'source-serif-4' },
  @{ q = 'IBM+Plex+Sans:wght@400;500;600';          slug = 'ibm-plex-sans' },
  @{ q = 'IBM+Plex+Mono:wght@400;500';              slug = 'ibm-plex-mono' }
)
New-Item -ItemType Directory -Force $Out | Out-Null
$css = New-Object System.Text.StringBuilder
[void]$css.AppendLine('/* AKKSP - lokalni pisma (SIL Open Font License 1.1). Generovano tools/get-fonts.ps1 */')
foreach ($f in $families) {
  $src = (Invoke-WebRequest -UseBasicParsing -UserAgent $ua -Uri "https://fonts.googleapis.com/css2?family=$($f.q)&display=swap").Content
  $blocks = [regex]::Matches($src, '/\*\s*([a-z\-]+)\s*\*/\s*(@font-face\s*\{[^}]+\})')
  foreach ($b in $blocks) {
    $subset = $b.Groups[1].Value
    if ($subset -ne 'latin' -and $subset -ne 'latin-ext') { continue }
    $block = $b.Groups[2].Value
    $weight = ([regex]::Match($block, 'font-weight:\s*([^;]+);')).Groups[1].Value.Trim()
    $style = ([regex]::Match($block, 'font-style:\s*([^;]+);')).Groups[1].Value.Trim()
    $w = if ($weight -match ' ') { 'var' } else { $weight }
    $name = "$($f.slug)-$w$(if ($style -eq 'italic') { '-italic' })-$subset.woff2"
    $url = ([regex]::Match($block, 'url\(([^)]+)\)')).Groups[1].Value
    Invoke-WebRequest -UseBasicParsing -UserAgent $ua -Uri $url -OutFile (Join-Path $Out $name)
    $local = [regex]::Replace($block, 'url\([^)]+\)', "url(./$name)")
    [void]$css.AppendLine("/* $subset */")
    [void]$css.AppendLine($local)
    Write-Output "ok  $name"
  }
}
[IO.File]::WriteAllText((Join-Path $Out 'fonts.css'), $css.ToString(), (New-Object Text.UTF8Encoding $false))
Write-Output "fonts.css written to $Out"
