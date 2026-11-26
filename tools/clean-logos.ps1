# Vycisti loga klientu s bilym (neprusvitnym) pozadim: bila -> pruhledna, kresba -> cerna s alfou.
# Vysledek (assets/aktrinec/clean/*.png) se pak barvi stejne jako ostatni loga (brightness(0), na tmave invert).
# Pouziti: .\tools\clean-logos.ps1
$ErrorActionPreference = 'Stop'
$root = Resolve-Path "$PSScriptRoot\.."
$src = Join-Path $root 'assets\aktrinec'
$out = Join-Path $src 'clean'
New-Item -ItemType Directory -Force $out | Out-Null
Add-Type -AssemblyName System.Drawing

$files = @{
  'slevarny-trinec'   = '2024-12-logo.jpg'
  'energetika-trinec' = '2024-12-energetika_trinec_logo-1.png'
  'refrasil'          = '2024-12-logo-3.png'
  'enviform'          = '2024-12-Logo_ENVIFORM____200_88.jpg'
  'distribuce-tepla'  = '2024-12-Dttas1.jpg'
  'jelmark'           = '2024-12-logo-1.png'
  'obec-vendryne'     = '2024-12-crest.png'
  'klimaprodukt'      = '2024-12-cropped-Logo.png'
}
foreach ($name in $files.Keys) {
  $im = New-Object Drawing.Bitmap (Join-Path $src $files[$name])
  $w = $im.Width; $h = $im.Height
  $bmp = New-Object Drawing.Bitmap $w, $h, ([Drawing.Imaging.PixelFormat]::Format32bppArgb)
  for ($y = 0; $y -lt $h; $y++) {
    for ($x = 0; $x -lt $w; $x++) {
      $c = $im.GetPixel($x, $y)
      $lum = 0.299 * $c.R + 0.587 * $c.G + 0.114 * $c.B
      # cim svetlejsi, tim pruhlednejsi; skoro bila = uplne pryc (zbytky JPEG sumu)
      $a = [Math]::Min(255, [Math]::Max(0, [int]((235 - $lum) * 1.25)))
      $a = [int]($a * $c.A / 255)
      $bmp.SetPixel($x, $y, [Drawing.Color]::FromArgb($a, 0, 0, 0))
    }
  }
  $bmp.Save((Join-Path $out "$name.png"), [Drawing.Imaging.ImageFormat]::Png)
  $bmp.Dispose(); $im.Dispose()
  Write-Output "clean/$name.png ($w x $h)"
}
