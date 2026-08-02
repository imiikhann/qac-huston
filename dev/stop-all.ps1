$ErrorActionPreference = 'Continue'

Write-Host "==> Stopping QAC backend (RoadRunner / rr.exe)..." -ForegroundColor Yellow
$rr = Get-Process -Name rr -ErrorAction SilentlyContinue
if ($rr) {
    $rr | Stop-Process -Force
    Write-Host "    Stopped $($rr.Count) rr.exe process(es)." -ForegroundColor Green
} else {
    Write-Host "    No rr.exe process found." -ForegroundColor DarkGray
}

Write-Host "==> Stopping QAC frontend (Next.js) node processes..." -ForegroundColor Yellow
$frontend = Get-CimInstance Win32_Process -Filter "Name = 'node.exe'" -ErrorAction SilentlyContinue |
    Where-Object { $_.CommandLine -match 'qac[\\/]frontend|next( |-server)' }
if ($frontend) {
    $frontend | ForEach-Object { Stop-Process -Id $_.ProcessId -Force }
    Write-Host "    Stopped $($frontend.Count) Next.js process(es)." -ForegroundColor Green
} else {
    Write-Host "    No QAC Next.js process found." -ForegroundColor DarkGray
}

Write-Host ""
Write-Host "Note: The saloon Vite server on port 5174 was NOT touched." -ForegroundColor DarkGray
Write-Host "QAC servers stopped." -ForegroundColor Cyan
