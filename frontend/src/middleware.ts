import { NextRequest, NextResponse } from 'next/server';

const API_URL = process.env.NEXT_PUBLIC_API_URL ?? 'http://localhost:8000/api/v1';

const MAINTENANCE_HTML = `<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quality Automotive Center - Coming Soon</title>
<link rel="icon" href="https://qachouston.com/favicon.ico">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    background: #13161b;
    color: #e8ecf1;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 24px;
  }
  .container { max-width: 560px; }
  .logo {
    width: 84px; height: 84px;
    margin: 0 auto 28px;
    border-radius: 20px;
    background: linear-gradient(135deg, #ff9000, #ffb347);
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 34px; color: #13161b;
    box-shadow: 0 12px 40px rgba(255, 144, 0, 0.35);
  }
  h1 { font-size: 2.4rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 12px; }
  .badge {
    display: inline-block; padding: 6px 14px; border-radius: 999px;
    background: rgba(255, 144, 0, 0.12); color: #ffb347;
    font-size: 0.8rem; font-weight: 600; letter-spacing: 0.12em; text-transform: uppercase;
    margin-bottom: 22px;
  }
  p.lead { font-size: 1.15rem; line-height: 1.6; color: #aab3c0; }
  .contact { font-size: 0.95rem; color: #6b7280; }
  .contact a { color: #ffb347; text-decoration: none; }
</style>
</head>
<body>
  <div class="container">
    <div class="logo">QAC</div>
    <h1>Quality Automotive Center</h1>
    <div class="badge">Coming Soon</div>
    <p class="lead">We're working on something great. Our new website is under construction and will be launching soon. Please check back shortly.</p>
    <p class="contact">Questions? <a href="mailto:hello@qachouston.com">hello@qachouston.com</a> &middot; (832) 896-3865</p>
  </div>
</body>
</html>`;

async function maintenanceEnabled(): Promise<boolean> {
  try {
    const res = await fetch(`${API_URL}/maintenance-status`, {
      cache: 'no-store',
    });
    if (!res.ok) return false;
    const data = (await res.json()) as { enabled?: boolean };
    return data.enabled === true;
  } catch {
    return false;
  }
}

export async function middleware(req: NextRequest) {
  if (await maintenanceEnabled()) {
    return new NextResponse(MAINTENANCE_HTML, {
      status: 503,
      headers: { 'content-type': 'text/html; charset=utf-8' },
    });
  }
  return NextResponse.next();
}

export const config = {
  matcher: ['/((?!api|_next/static|_next/image|favicon.ico|.*\\..*).*)'],
};