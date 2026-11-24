<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Analytics;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\SiteSettings;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\VisitorsChartWidget;
use App\Livewire\ClearCacheButton;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Livewire\Livewire;

class AdminPanelProvider extends PanelProvider
{
    public function boot(): void
    {
        Livewire::component('clear-cache-button', ClearCacheButton::class);

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_END,
            fn (): string => Blade::render('@livewire(\'clear-cache-button\')'),
        );

        FilamentView::registerRenderHook(
            'panels::head.start',
            fn (): string => '<link rel="stylesheet" href="' . Vite::asset('resources/css/app.css') . '">',
        );

        // Real-time admin notifications: listen to the private
        // `admin.tickets` channel over Reverb (WebSocket) and pop a toast
        // the moment a customer starts a ticket or sends a message.
        FilamentView::registerRenderHook(
            'panels::head.start',
            fn (): string => <<<'HTML'
                <script>
                    (function () {
                        var joined = false;

                        var style = document.createElement('style');
                        style.textContent = '@keyframes qacToastIn{from{opacity:0;transform:translateX(24px)}to{opacity:1;transform:none}}';
                        document.head.appendChild(style);

                        function showNotification(e) {
                            var isNewTicket = e.type === 'new_ticket';
                            var toast = document.createElement('div');
                            toast.setAttribute('role', 'status');
                            toast.style.cssText = 'position:fixed;top:1.25rem;right:1.25rem;z-index:99999;width:330px;background:#fff;color:#1f2937;border-radius:.75rem;border-left:4px solid #f59e0b;box-shadow:0 10px 30px rgba(0,0,0,.18);padding:.9rem 1rem;cursor:pointer;font-family:ui-sans-serif,system-ui,-apple-system,sans-serif;animation:qacToastIn .25s ease;';

                            var title = document.createElement('div');
                            title.style.cssText = 'font-weight:700;font-size:.9rem;margin-bottom:.3rem;display:flex;align-items:center;gap:.45rem;';

                            var dot = document.createElement('span');
                            dot.style.cssText = 'width:.5rem;height:.5rem;border-radius:50%;background:#f59e0b;display:inline-block;flex:none;';
                            title.appendChild(dot);
                            title.appendChild(document.createTextNode(isNewTicket ? 'New support ticket' : 'New customer message'));

                            var who = document.createElement('div');
                            who.textContent = (e.name || '') + (e.email ? ' · ' + e.email : '');
                            who.style.cssText = 'font-size:.78rem;color:#6b7280;margin-bottom:.3rem;';

                            var msg = document.createElement('div');
                            msg.textContent = e.message || '';
                            msg.style.cssText = 'font-size:.85rem;line-height:1.4;word-break:break-word;';

                            toast.appendChild(title);
                            toast.appendChild(who);
                            toast.appendChild(msg);

                            toast.addEventListener('click', function () {
                                window.location.href = '/admin/tickets/' + e.id + '/edit';
                            });

                            document.body.appendChild(toast);
                            setTimeout(function () {
                                toast.style.transition = 'opacity .4s';
                                toast.style.opacity = '0';
                                setTimeout(function () { toast.remove(); }, 450);
                            }, 8000);
                        }

                        function subscribe(attempt) {
                            if (joined) return;
                            if (window.Echo && typeof window.Echo.private === 'function') {
                                joined = true;
                                window.Echo.private('admin.tickets').listen('.customer-message', showNotification);
                                return;
                            }
                            if (attempt > 120) return;
                            setTimeout(function () { subscribe(attempt + 1); }, 500);
                        }

                        window.addEventListener('EchoLoaded', function () { subscribe(0); });

                        // Watchdog: pusher-js can get stuck in a "disconnected"
                        // state after a server restart. Hard-reset so the toast
                        // pipeline recovers without a page reload.
                        setInterval(function () {
                            try {
                                var p = window.Echo && window.Echo.connector && window.Echo.connector.pusher;
                                if (!p || !p.connection) return;
                                var state = p.connection.state;
                                if (state !== 'connected' && state !== 'connecting') {
                                    p.disconnect();
                                    p.connect();
                                }
                            } catch (e) {}
                        }, 15000);

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', function () { subscribe(0); });
                        } else {
                            subscribe(0);
                        }
                    })();
                </script>
                HTML
        );
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->spa(true, true)
            ->broadcasting(true)
            ->darkMode(true, false)
            ->themeSwitcher(true)
            ->defaultThemeMode(ThemeMode::Light)
            ->brandLogo(fn () => view('filament.logo'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
                Analytics::class,
                SiteSettings::class,
            ])
            ->navigationItems([
                NavigationItem::make('Visit Website')
                    ->url(env('FRONTEND_URL', 'http://localhost:3001'), shouldOpenInNewTab: true)
                    ->icon('heroicon-o-globe-alt')
                    ->sort(999),
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
                StatsOverviewWidget::class,
                VisitorsChartWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
