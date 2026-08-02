$ErrorActionPreference = 'Stop'

$root = Split-Path -Parent $PSScriptRoot
$frontend = Join-Path $root 'frontend'
$frontendUrl = 'http://localhost:3001'

Write-Host "==> Stopping existing QAC frontend (Next.js) processes..." -ForegroundColor Yellow
Get-CimInstance Win32_Process -Filter "Name = 'node.exe'" -ErrorAction SilentlyContinue |
    Where-Object { $_.CommandLine -match 'qac[\\/]frontend|next( |-server)' } |
    ForEach-Object { Stop-Process -Id $_.ProcessId -Force }
Start-Sleep -Seconds 1

Write-Host "==> Starting Next.js frontend on http://localhost:3001 ..." -ForegroundColor Green
Start-Process -FilePath 'npm.cmd' -ArgumentList 'run', 'dev' -WorkingDirectory $frontend -WindowStyle Hidden

Write-Host "==> Waiting for frontend to respond (first compile can take a while)..." -ForegroundColor Yellow
$ready = $false
for ($i = 1; $i -le 60; $i++) {
    Start-Sleep -Milliseconds 500
    try {
        $r = Invoke-WebRequest -Uri $frontendUrl -Method GET -TimeoutSec 2 -UseBasicParsing
        if ($r.StatusCode -eq 200) { $ready = $true; break }
    } catch { }
}

if ($ready) {
    Write-Host "Frontend is UP at http://localhost:3001" -ForegroundColor Green
} else {
    Write-Host "WARNING: Frontend did not respond yet. Check port 3001." -ForegroundColor Red
}
