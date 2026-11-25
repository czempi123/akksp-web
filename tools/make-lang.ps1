# Vytvori jazykovou mutaci prototypu homepage: site/index.html -> site/<lang>/index.html
# Preklady jsou v tools/i18n/<lang>.json (pary CZ -> cizi jazyk, nahrazuji se v poradi).
# Po nahrazeni skript vypise ceske znaky, ktere v mutaci zustaly (= chybejici preklad).
# Pouziti: .\tools\make-lang.ps1 -Lang pl   |   .\tools\make-lang.ps1 -Lang en   |   bez parametru obe
param([string[]]$Lang = @('pl', 'en'))
$ErrorActionPreference = 'Stop'
$root = Resolve-Path "$PSScriptRoot\.."
$utf8 = New-Object Text.UTF8Encoding $false
$src = [IO.File]::ReadAllText((Join-Path $root 'site\index.html'), $utf8)
foreach ($l in $Lang) {
  $html = $src
  $json = [IO.File]::ReadAllText((Join-Path $root "tools\i18n\$l.json"), $utf8) | ConvertFrom-Json
  $missing = @()
  foreach ($p in $json.pairs) {
    if ($html.Contains($p[0])) { $html = $html.Replace($p[0], $p[1]) } else { $missing += $p[0] }
  }
  $out = Join-Path $root "site\$l"
  New-Item -ItemType Directory -Force $out | Out-Null
  [IO.File]::WriteAllText((Join-Path $out 'index.html'), $html, $utf8)
  Write-Output "site/$l/index.html written; pairs: $($json.pairs.Count); not found: $($missing.Count)"
  $missing | ForEach-Object { Write-Output "  missing: $_" }
  # zbyle ceske texty mimo komentare (jen pro kontrolu)
  $body = [regex]::Replace($html, '<!--.*?-->', '', 'Singleline')
  $cz = [regex]::Matches($body, '>[^<>]*[ěščřžůĚŠČŘŽ][^<>]*<') | ForEach-Object { $_.Value.Trim('<', '>').Trim() } | Where-Object { $_ } | Select-Object -Unique
  if ($l -ne 'cs') { $cz | ForEach-Object { Write-Output "  czech left: $_" } }
}
