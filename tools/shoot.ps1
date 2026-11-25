# Celostránkový snímek přes headless Microsoft Edge a rozřezání na díly.
# Použití: .\tools\shoot.ps1 -Url "http://localhost:4173/site/?static" -Width 1440 -Height 5600 -Name home-1440
param(
  [Parameter(Mandatory)] [string]$Url,
  [int]$Width = 1440,
  [int]$Height = 5600,
  [string]$Name = "shot",
  [string]$OutDir = "$PSScriptRoot\..\shots",
  [int]$Slice = 1100
)
$edge = @("${env:ProgramFiles(x86)}\Microsoft\Edge\Application\msedge.exe", "$env:ProgramFiles\Microsoft\Edge\Application\msedge.exe") | Where-Object { Test-Path $_ } | Select-Object -First 1
New-Item -ItemType Directory -Force $OutDir | Out-Null
$png = Join-Path $OutDir "$Name.png"
$sep = if ($Url -match '\?') { '&' } else { '?' }
Start-Process -FilePath $edge -Wait -ArgumentList "--headless=new", "--disable-gpu", "--hide-scrollbars", "--force-device-scale-factor=1", "--virtual-time-budget=6000", "--window-size=$Width,$Height", "--screenshot=$png", "$Url${sep}r=$(Get-Random)"
Add-Type -AssemblyName System.Drawing
$img = [Drawing.Image]::FromFile($png)
$i = 0
for ($y = 0; $y -lt $img.Height; $y += $Slice) {
  $h = [Math]::Min($Slice, $img.Height - $y)
  $bmp = New-Object Drawing.Bitmap $img.Width, $h
  $g = [Drawing.Graphics]::FromImage($bmp)
  $g.DrawImage($img, (New-Object Drawing.Rectangle 0, 0, $img.Width, $h), (New-Object Drawing.Rectangle 0, $y, $img.Width, $h), [Drawing.GraphicsUnit]::Pixel)
  $bmp.Save((Join-Path $OutDir "$Name-$i.jpg"), [Drawing.Imaging.ImageFormat]::Jpeg)
  $g.Dispose(); $bmp.Dispose(); $i++
}
$img.Dispose()
"$Name : $i parts in $OutDir"

