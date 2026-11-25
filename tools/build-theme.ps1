# Sestavi child tema: zkopiruje CSS/JS z site/ (jediny zdroj pravdy), loga a vychozi fotky,
# vytvori screenshot.png a ZIP pro nahrani do WordPressu (Vzhled > Motivy > Nahrat).
# Pouziti: .\tools\build-theme.ps1
$ErrorActionPreference = 'Stop'
$root  = Resolve-Path "$PSScriptRoot\.."
$theme = Join-Path $root 'wordpress\akksp-child'
$src   = Join-Path $root 'site'
$img   = Join-Path $root 'assets\aktrinec'

# 1) CSS + JS ze site/
New-Item -ItemType Directory -Force "$theme\assets\css", "$theme\assets\js", "$theme\assets\img\demo" | Out-Null
foreach ($f in 'tokens', 'base', 'components', 'sections', 'wp') {
  Copy-Item "$src\css\$f.css" "$theme\assets\css\$f.css" -Force
}
Copy-Item "$src\js\site.js" "$theme\assets\js\site.js" -Force

# 2) Loga
foreach ($v in 'white', 'ink', 'navy') {
  Copy-Item (Join-Path $root "assets\brand\akksp-logo-$v.svg") "$theme\assets\img\akksp-logo-$v.svg" -Force
}

# 3) Vychozi fotky widgetu (zmensene)
Add-Type -AssemblyName System.Drawing
function Save-Resized([string]$from, [string]$to, [int]$maxW) {
  $im = [Drawing.Image]::FromFile($from)
  try {
    $w = [Math]::Min($maxW, $im.Width); $h = [int]($im.Height * $w / $im.Width)
    $bmp = New-Object Drawing.Bitmap $w, $h
    $g = [Drawing.Graphics]::FromImage($bmp)
    $g.InterpolationMode = 'HighQualityBicubic'; $g.SmoothingMode = 'HighQuality'
    $g.DrawImage($im, 0, 0, $w, $h)
    if ($to -like '*.png') { $bmp.Save($to, [Drawing.Imaging.ImageFormat]::Png) }
    else {
      $enc = [Drawing.Imaging.ImageCodecInfo]::GetImageEncoders() | Where-Object { $_.MimeType -eq 'image/jpeg' }
      $p = New-Object Drawing.Imaging.EncoderParameters 1
      $p.Param[0] = New-Object Drawing.Imaging.EncoderParameter ([Drawing.Imaging.Encoder]::Quality), 82L
      $bmp.Save($to, $enc, $p)
    }
    $g.Dispose(); $bmp.Dispose()
  } finally { $im.Dispose() }
}
$demo = @{
  'hero-zasedacka.jpg'    = @('2024-11-20241114_135120.jpg', 2000)
  'jednaci-mistnost.jpg'  = @('2025-01-20241114_134712-orez.jpg', 2000)   # orez bez balkonovych dveri
  'schody.jpg'            = @('2025-01-schody-3.jpg', 2000)
  'troszok.jpg'           = @('2024-12-P6200389CHO-scaled-e1692876753442.jpg', 800)
  'filipczyk.jpg'         = @('2024-12-P6290544CHO-scaled-e1692876639242.jpg', 800)
  'kadlubiec.jpg'         = @('2024-12-182-1.jpg', 800)
  'kadlubcova.jpg'        = @('2024-12-Kadlubcova.jpg', 800)
  'mediacz-logo-bile.png' = @('2026-01-mediacz-logo-bile.png', 400)
}
foreach ($k in $demo.Keys) { Save-Resized (Join-Path $img $demo[$k][0]) "$theme\assets\img\demo\$k" $demo[$k][1] }

# 4) screenshot.png (1200x900) z nahledu homepage, pokud existuje
$shot = Join-Path $root 'shots\w2-1440.png'
if (Test-Path $shot) {
  $im = [Drawing.Image]::FromFile($shot)
  $bmp = New-Object Drawing.Bitmap 1200, 900
  $g = [Drawing.Graphics]::FromImage($bmp); $g.InterpolationMode = 'HighQualityBicubic'
  $g.DrawImage($im, (New-Object Drawing.Rectangle 0, 0, 1200, 900), (New-Object Drawing.Rectangle 0, 0, 1440, 1080), [Drawing.GraphicsUnit]::Pixel)
  $bmp.Save("$theme\screenshot.png", [Drawing.Imaging.ImageFormat]::Png)
  $g.Dispose(); $bmp.Dispose(); $im.Dispose()
}

# 5) ZIP s lomitky "/" (Compress-Archive v PS 5.1 pouziva "\" a WordPress na Linuxu ho rozbali spatne)
$ver = ([regex]::Match((Get-Content "$theme\style.css" -Raw), 'Version:\s*([0-9.]+)')).Groups[1].Value
$dist = Join-Path $root 'wordpress\dist'
New-Item -ItemType Directory -Force $dist | Out-Null
$zip = Join-Path $dist "akksp-child-$ver.zip"
if (Test-Path $zip) { Remove-Item $zip -Force }
Add-Type -AssemblyName System.IO.Compression, System.IO.Compression.FileSystem
$fs = [IO.File]::Open($zip, 'Create')
$archive = New-Object IO.Compression.ZipArchive($fs, [IO.Compression.ZipArchiveMode]::Create)
$base = (Resolve-Path "$theme\..").Path.TrimEnd('\') + '\'
Get-ChildItem $theme -Recurse -File | ForEach-Object {
  $entry = $_.FullName.Substring($base.Length).Replace('\', '/')
  [void][IO.Compression.ZipFileExtensions]::CreateEntryFromFile($archive, $_.FullName, $entry, [IO.Compression.CompressionLevel]::Optimal)
}
$archive.Dispose(); $fs.Dispose()
$kb = [int]((Get-Item $zip).Length / 1KB)
Write-Output "Theme built: $zip ($kb kB)"
