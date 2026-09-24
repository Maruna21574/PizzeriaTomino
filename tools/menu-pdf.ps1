# Vygeneruje jedálny lístok na stiahnutie (PDF) v slovenčine aj maďarčine
# zo stránky menu-tlac.php. Spusti po každej zmene data/menu.php alebo prekladu:
#
#   powershell -ExecutionPolicy Bypass -File tools\menu-pdf.ps1
#
# Predpoklad: web beží lokálne v Laragone (predvolene http://pizzeriatomino.test)
# a je nainštalovaný Microsoft Edge alebo Google Chrome.

param(
    [string]$BaseUrl = 'http://pizzeriatomino.test'
)

$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $PSScriptRoot
$outDir = Join-Path $root 'assets\menu'
New-Item -ItemType Directory -Force $outDir | Out-Null

$browser = @(
    "${env:ProgramFiles(x86)}\Microsoft\Edge\Application\msedge.exe",
    "$env:ProgramFiles\Microsoft\Edge\Application\msedge.exe",
    "$env:ProgramFiles\Google\Chrome\Application\chrome.exe",
    "$env:LOCALAPPDATA\Google\Chrome\Application\chrome.exe"
) | Where-Object { Test-Path $_ } | Select-Object -First 1

if (-not $browser) {
    throw 'Nenašiel sa Microsoft Edge ani Google Chrome.'
}

$versions = @{ 'sk' = '/menu-tlac'; 'hu' = '/hu/menu-tlac' }

foreach ($lang in $versions.Keys) {
    $url = $BaseUrl.TrimEnd('/') + $versions[$lang]
    $pdf = Join-Path $outDir "pizzeria-tominno-menu-$lang.pdf"
    Write-Host "Generujem $pdf z $url"
    if (Test-Path $pdf) { Remove-Item $pdf -Confirm:$false }
    # Start-Process - prehliadač píše do stderr neškodné hlásenia, ktoré by
    # PowerShell 5.1 inak považoval za chybu.
    Start-Process -FilePath $browser -Wait -WindowStyle Hidden -ArgumentList @(
        '--headless=new', '--disable-gpu', '--no-pdf-header-footer',
        '--virtual-time-budget=5000', "--print-to-pdf=`"$pdf`"", $url
    )
    if (-not (Test-Path $pdf)) {
        throw "PDF $pdf sa nepodarilo vytvoriť - beží web na adrese $BaseUrl?"
    }
}

Write-Host 'Hotovo.'
