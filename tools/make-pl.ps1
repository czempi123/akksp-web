# Vytvori polskou mutaci prototypu homepage: site/index.html -> site/pl/index.html
# Preklady jsou v tools/i18n/pl.json (pary CZ -> PL, nahrazuji se v poradi).
# Pouziti: .\tools\make-pl.ps1
$ErrorActionPreference = 'Stop'
$root = Resolve-Path "$PSScriptRoot\.."
$utf8 = New-Object Text.UTF8Encoding $false
$html = [IO.File]::ReadAllText((Join-Path $root 'site\index.html'), $utf8)
$json = [IO.File]::ReadAllText((Join-Path $root 'tools\i18n\pl.json'), $utf8) | ConvertFrom-Json
$missing = @()
foreach ($p in $json.pairs) {
  if ($html.Contains($p[0])) { $html = $html.Replace($p[0], $p[1]) } else { $missing += $p[0] }
}
$out = Join-Path $root 'site\pl'
New-Item -ItemType Directory -Force $out | Out-Null
[IO.File]::WriteAllText((Join-Path $out 'index.html'), $html, $utf8)
Write-Output "site/pl/index.html written; pairs: $($json.pairs.Count); not found: $($missing.Count)"
$missing | ForEach-Object { Write-Output "  missing: $_" }
