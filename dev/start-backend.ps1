$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent $PSScriptRoot
$backend = Join-Path $root 'backend'
$rrExe = Join-Path $backend 'rr.exe'
$backendUrl = 'http://localhost:8000/api/v1/settings'

Write-Host "==> Stopping existing QAC backend processes..." -ForegroundColor Yellow
Get-Process -Name rr -ErrorAction SilentlyContinue | Stop-Process -Force
Start-Sleep -Seconds 1

Write-Host "==> Optimizing Laravel caches..." -ForegroundColor Yellow
& php artisan optimize --no-interaction 2>&1 | Out-Null

Write-Host "==> Starting RoadRunner backend on http://localhost:8000 ..." -ForegroundColor Green
Start-Process -FilePath $rrExe -ArgumentList 'serve' -WorkingDirectory $backend -WindowStyle Hidden

Write-Host "==> Waiting for backend to respond..." -ForegroundColor Yellow
$ready = $false
for ($i = 1; $i -le 20; $i++) {
    Start-Sleep -Milliseconds 500
    try {
        $r = Invoke-WebRequest -Uri $backendUrl -Method GET -TimeoutSec 2 -UseBasicParsing
        if ($r.StatusCode -eq 200) { $ready = $true; break }
    } catch { }
}

if ($ready) {
    Write-Host "Backend is UP at http://localhost:8000" -ForegroundColor Green
} else {
    Write-Host "WARNING: Backend did not respond yet. Check port 8000." -ForegroundColor Red
}
