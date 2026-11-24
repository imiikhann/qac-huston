<x-filament-panels::page>
    @php
        $period = $this->period;
        $periodLabel = $this->periodLabel();
        $trafficChart = $period === 'overview' ? $this->getBarChartData('daily') : $this->getBarChartData();
        $device = $this->getDeviceBreakdown();
        $deviceTotal = $device['total'] ?: 1;
        $topPages = $this->getTopPages();
        $topLocations = $this->getTopLocations();
        $referrers = $this->getTopReferrers();
        $topPagesMax = max(array_column($topPages, 'count') ?: [1]);
        $uniqueRate = $period === 'overview'
            ? round($this->getUniqueVisitors() / max($this->getTotalVisits(), 1) * 100)
            : round($this->getPeriodUnique() / max($this->getPeriodVisits(), 1) * 100);

        $topLoc = collect($topLocations)->first();

        $overviewCards = [
            [
                'label' => 'Total Visits',
                'value' => \App\Filament\Pages\Analytics::human($this->getTotalVisits()),
                'icon' => 'heroicon-o-eye',
                'color' => '#FF9000',
                'bg' => 'rgba(255,144,0,0.12)',
                'footer' => 'All-time tracked page views',
            ],
            [
                'label' => 'Unique Visitors',
                'value' => \App\Filament\Pages\Analytics::human($this->getUniqueVisitors()),
                'icon' => 'heroicon-o-users',
                'color' => '#3b82f6',
                'bg' => 'rgba(59,130,246,0.12)',
                'footer' => number_format($this->getUniqueVisitors()) . ' distinct IPs of ' . number_format($this->getTotalVisits()) . ' visits',
            ],
            [
                'label' => 'Today',
                'value' => \App\Filament\Pages\Analytics::human($this->getTodayVisits()),
                'icon' => 'heroicon-o-calendar-days',
                'color' => '#10b981',
                'bg' => 'rgba(16,185,129,0.12)',
                'footer' => number_format($this->getTodayUnique()) . ' unique today',
            ],
            [
                'label' => 'Top City',
                'value' => $topLoc['city'] ?? '-',
                'icon' => 'heroicon-o-map-pin',
                'color' => '#8b5cf6',
                'bg' => 'rgba(139,92,246,0.12)',
                'footer' => $topLoc ? number_format((int) $topLoc['count']) . ' visits &middot; ' . ($topLoc['zip_code'] ?? '') : 'No location data yet',
            ],
        ];

        $change = $this->getPeriodChange();
        $changeUp = $change['direction'] === 'up';
        $changeDown = $change['direction'] === 'down';
        $changeColor = $changeUp ? '#10b981' : ($changeDown ? '#ef4444' : '#6b7280');
        $changeRgb = $changeUp ? '16,185,129' : ($changeDown ? '239,68,68' : '107,114,128');
        $changeBadgeText = ($changeUp ? '+' : ($changeDown ? '-' : '')) . number_format($change['pct']) . '%';

        $periodCards = [
            [
                'label' => $period === 'daily' ? "Today's Visits" : $periodLabel . ' Visits',
                'value' => \App\Filament\Pages\Analytics::human($this->getPeriodVisits()),
                'icon' => 'heroicon-o-eye',
                'color' => '#FF9000',
                'bg' => 'rgba(255,144,0,0.12)',
                'footer' => 'Tracked visits in this period',
            ],
            [
                'label' => 'Unique Visitors',
                'value' => \App\Filament\Pages\Analytics::human($this->getPeriodUnique()),
                'icon' => 'heroicon-o-user-group',
                'color' => '#3b82f6',
                'bg' => 'rgba(59,130,246,0.12)',
                'footer' => number_format($this->getPeriodUnique()) . ' distinct IPs &middot; ' . $uniqueRate . '% unique rate',
            ],
            [
                'label' => 'Avg / Day',
                'value' => number_format($this->getAvgPerDay()),
                'icon' => 'heroicon-o-chart-bar',
                'color' => '#10b981',
                'bg' => 'rgba(16,185,129,0.12)',
                'footer' => 'Average over the period',
            ],
            [
                'label' => 'Previous Period',
                'value' => \App\Filament\Pages\Analytics::human($this->getPreviousPeriodVisits()),
                'icon' => 'heroicon-o-clock',
                'color' => $changeColor,
                'bg' => 'rgba(' . $changeRgb . ',0.12)',
                'footer' => 'Visits in the period before this one',
                'badge' => [
                    'text' => $changeBadgeText,
                    'direction' => $change['direction'],
                    'bg' => 'rgba(' . $changeRgb . ',0.14)',
                    'color' => $changeColor,
                ],
            ],
        ];

        $cards = $period === 'overview' ? $overviewCards : $periodCards;
    @endphp

    <div id="qac-analytics" class="qac-space-y">
        <style>
            #qac-analytics, #qac-analytics *, #qac-analytics *::before, #qac-analytics *::after, .qac-space-y > * { box-sizing: border-box; }
            .qac-space-y > * + * { margin-top: 1.5rem; }

            .qac-chip-row { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem; }
            .qac-chips { display: flex; flex-wrap: wrap; gap: 0.5rem; }
            .qac-chip { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 9999px; border: 1px solid #e5e7eb; background: #ffffff; font-size: 12px; font-weight: 500; color: #4b5563; }
            .qac-chip-icon { width: 14px; height: 14px; }
            .qac-dot { width: 6px; height: 6px; border-radius: 9999px; }

            .qac-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1rem; }
            .qac-card { position: relative; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; padding: 1.25rem; border: 1px solid #e5e7eb; border-radius: 1rem; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
            .qac-card-accent { position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: 9999px; }
            .qac-card-body { position: relative; display: flex; align-items: flex-start; justify-content: space-between; gap: 0.75rem; }
            .qac-card-idx { display: flex; align-items: center; gap: 0.75rem; min-width: 0; }
            .qac-icon-box { display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0; }
            .qac-line-icon { width: 20px; height: 20px; }
            .qac-card-text { min-width: 0; }
            .qac-card-label { margin: 0 0 6px 0; font-size: 11px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; color: #9ca3af; }
            .qac-card-value { margin: 0; font-size: 24px; font-weight: 700; line-height: 1.1; color: #111827; font-variant-numeric: tabular-nums; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .qac-card-footer { position: relative; margin-top: 0.75rem; font-size: 12px; color: #9ca3af; line-height: 1.5; }
            .qac-badge { display: inline-flex; align-items: center; gap: 3px; padding: 2px 8px; border-radius: 9999px; font-size: 12px; font-weight: 700; flex-shrink: 0; }
            .qac-badge svg { width: 14px; height: 14px; }

            .qac-grid-charts { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
            .qac-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
            .qac-grid-locs { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
            .qac-device-grid { display: grid; grid-template-columns: 200px 1fr; gap: 1rem; align-items: center; }
            @media (max-width: 1100px) { .qac-grid-charts, .qac-grid-2 { grid-template-columns: 1fr; } }
            @media (max-width: 900px) { .qac-grid-locs { grid-template-columns: 1fr; } }
            @media (max-width: 640px) { .qac-device-grid { grid-template-columns: 1fr; } }

            .qac-panel { border: 1px solid #e5e7eb; border-radius: 1rem; background: #ffffff; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
            .qac-panel-head { display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 0.75rem; margin-bottom: 1rem; }
            .qac-title { margin: 0; font-size: 14px; font-weight: 600; color: #111827; }
            .qac-subtitle { margin: 2px 0 0 0; font-size: 12px; color: #9ca3af; }
            .qac-legend { display: flex; flex-wrap: wrap; align-items: center; gap: 1rem; font-size: 12px; color: #6b7280; }
            .qac-swatch { display: inline-block; width: 8px; height: 8px; border-radius: 9999px; margin-right: 5px; vertical-align: 1px; }
            .qac-swatch-dark { background: #313639; }
            .qac-legend-num { font-weight: 600; color: #111827; font-variant-numeric: tabular-nums; }

            .qac-row { display: flex; align-items: center; gap: 0.75rem; margin-top: 14px; }
            .qac-swatch-lg { width: 10px; height: 10px; border-radius: 9999px; flex-shrink: 0; }
            .qac-device-name { width: 64px; flex-shrink: 0; font-size: 12px; font-weight: 500; color: #4b5563; }
            .qac-bar-track { flex: 1; height: 6px; border-radius: 9999px; background: #f3f4f6; overflow: hidden; }
            .qac-bar-fill { height: 100%; border-radius: 9999px; }
            .qac-bar-num { width: 96px; flex-shrink: 0; text-align: right; font-size: 12px; font-weight: 600; color: #111827; font-variant-numeric: tabular-nums; }
            .qac-bar-num small { color: #9ca3af; font-weight: 400; }

            .qac-simple-row { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; }
            .qac-rank { width: 20px; flex-shrink: 0; font-size: 12px; font-weight: 700; color: #9ca3af; }
            .qac-url-pill { display: inline-block; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; padding: 2px 8px; border-radius: 6px; background: #f3f4f6; font-family: ui-monospace, SFMono-Regular, "Segoe UI Mono", Menlo, Consolas, monospace; font-size: 12px; color: #374151; }
            .qac-host { flex: 1; min-width: 0; font-size: 14px; font-weight: 500; color: #111827; }
            .qac-host-truncate { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .qac-small-num { font-size: 12px; font-weight: 600; color: #4b5563; font-variant-numeric: tabular-nums; }

            .qac-loc { display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; padding: 8px 12px; border: 1px solid rgba(229,231,235,0.9); border-radius: 12px; }
            .qac-loc-idx { display: flex; align-items: center; gap: 10px; min-width: 0; }
            .qac-loc-icon { display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; flex-shrink: 0; background: rgba(139,92,246,0.12); color: #7c3aed; }
            .qac-loc-icon svg { width: 16px; height: 16px; }
            .qac-loc-title { margin: 0; font-size: 14px; font-weight: 500; color: #111827; }
            .qac-loc-sub { margin: 0; font-size: 12px; color: #9ca3af; }
            .qac-count-pill { flex-shrink: 0; padding: 2px 8px; border-radius: 9999px; background: rgba(139,92,246,0.12); color: #7c3aed; font-size: 12px; font-weight: 700; font-variant-numeric: tabular-nums; }

            .qac-empty { padding: 2rem 0; text-align: center; font-size: 13px; color: #9ca3af; }

            html.dark .qac-card, html.dark .qac-panel, html.dark .qac-chip { background: #1f2937; border-color: #374151; box-shadow: none; }
            html.dark .qac-card-value, html.dark .qac-title, html.dark .qac-host, html.dark .qac-loc-title, html.dark .qac-legend-num, html.dark .qac-bar-num { color: #f9fafb; }
            html.dark .qac-card-label, html.dark .qac-subtitle, html.dark .qac-card-footer, html.dark .qac-empty, html.dark .qac-loc-sub, html.dark .qac-rank { color: #6b7280; }
            html.dark .qac-device-name, html.dark .qac-small-num, html.dark .qac-chip, html.dark .qac-legend { color: #d1d5db; }
            html.dark .qac-url-pill { background: #374151; color: #e5e7eb; }
            html.dark .qac-swatch-dark { background: #9ca3af; }
            html.dark .qac-bar-track { background: #374151; }
            html.dark .qac-loc { border-color: rgba(55,65,81,0.6); }
            html.dark .qac-bar-num small { color: #6b7280; }
        </style>

        {{-- Period switcher + chips --}}
        <div class="qac-chip-row">
            <x-filament::tabs>
                <x-filament::tabs.item
                    wire:click="$set('period', 'overview')"
                    :active="$period === 'overview'"
                    icon="heroicon-o-squares-2x2"
                >
                    Overview
                </x-filament::tabs.item>
                <x-filament::tabs.item
                    wire:click="$set('period', 'daily')"
                    :active="$period === 'daily'"
                    icon="heroicon-o-calendar"
                >
                    Daily
                </x-filament::tabs.item>
                <x-filament::tabs.item
                    wire:click="$set('period', 'weekly')"
                    :active="$period === 'weekly'"
                    icon="heroicon-o-calendar-days"
                >
                    Weekly
                </x-filament::tabs.item>
                <x-filament::tabs.item
                    wire:click="$set('period', 'monthly')"
                    :active="$period === 'monthly'"
                    icon="heroicon-o-calendar-days"
                >
                    Monthly
                </x-filament::tabs.item>
            </x-filament::tabs>

            <div class="qac-chips">
                <span class="qac-chip">
                    <x-filament::icon name="heroicon-o-signal" class="qac-chip-icon" style="color: #FF9000" />
                    {{ $periodLabel }} reporting
                </span>
                <span class="qac-chip">{{ $period === 'overview' ? 'Last 14 days' : $this->chartSubtitle() }}</span>
                <span class="qac-chip">
                    <span class="qac-dot" style="background: {{ $this->getTodayVisits() > 0 ? '#10b981' : '#9ca3af' }}"></span>
                    {{ number_format($this->getTodayVisits()) }} visits today
                </span>
            </div>
        </div>

        {{-- Stat cards --}}
        @include('filament.pages.analytics.partials.stat-cards', ['cards' => $cards])

        {{-- Charts --}}
        <div class="qac-grid-charts">
            <div class="qac-panel">
                <div class="qac-panel-head">
                    <div>
                        <h3 class="qac-title">Traffic Overview</h3>
                        <p class="qac-subtitle">{{ $period === 'overview' ? 'Last 14 days' : $this->chartSubtitle() }}</p>
                    </div>
                    <div class="qac-legend">
                        <span>
                            <span class="qac-swatch" style="background: #FF9000"></span>
                            Visits <span class="qac-legend-num">{{ number_format(array_sum($trafficChart['visits'])) }}</span>
                        </span>
                        <span>
                            <span class="qac-swatch qac-swatch-dark"></span>
                            Unique <span class="qac-legend-num">{{ number_format(array_sum($trafficChart['uniques'])) }}</span>
                        </span>
                    </div>
                </div>

                <div wire:key="traffic-chart-{{ $period }}" wire:ignore style="position: relative; height: 300px;">
                    <canvas x-data="initTrafficChart(@js($trafficChart))" x-init="init()"></canvas>
                </div>
            </div>

            <div class="qac-panel">
                <div class="qac-panel-head">
                    <div>
                        <h3 class="qac-title">Device Breakdown</h3>
                        <p class="qac-subtitle">User agents in this period</p>
                    </div>
                </div>

                <div class="qac-device-grid">
                    <div wire:key="device-chart-{{ $period }}" wire:ignore style="position: relative; height: 190px; min-width: 180px;">
                        <canvas x-data="initDeviceChart(@js($device))" x-init="init()"></canvas>
                    </div>

                    <div>
                        @foreach ($device['labels'] as $deviceIndex => $deviceLabel)
                            @php
                                $deviceValue = $device['values'][$deviceIndex];
                                $devicePct = $deviceTotal > 0 ? (int) round($deviceValue / $deviceTotal * 100) : 0;
                            @endphp
                            <div class="qac-row">
                                <span class="qac-swatch-lg" style="background: {{ $device['colors'][$deviceIndex] }}"></span>
                                <span class="qac-device-name">{{ $deviceLabel }}</span>
                                <div class="qac-bar-track">
                                    <div class="qac-bar-fill" style="width: {{ $devicePct }}%; background: {{ $device['colors'][$deviceIndex] }}"></div>
                                </div>
                                <span class="qac-bar-num">
                                    {{ number_format($deviceValue) }}
                                    <small>({{ $devicePct }}%)</small>
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Top pages & referrers --}}
        <div class="qac-grid-2">
            <div class="qac-panel">
                <div class="qac-panel-head">
                    <div>
                        <h3 class="qac-title">Top Pages</h3>
                        <p class="qac-subtitle">Most visited paths in period</p>
                    </div>
                    <x-filament::icon name="heroicon-o-document-text" class="qac-line-icon" style="color: #d1d5db" />
                </div>

                @if (count($topPages))
                    @foreach ($topPages as $pageRank => $page)
                        @php $pagePct = (int) round($page['count'] / $topPagesMax * 100); @endphp
                        <div class="qac-row">
                            <span class="qac-rank">{{ $pageRank + 1 }}</span>
                            <div class="qac-url-pill">/{{ ltrim($page['url'], '/') }}</div>
                            <span class="qac-small-num">{{ number_format((int) $page['count']) }}</span>
                        </div>
                        <div class="qac-bar-track" style="margin: 6px 0 0 28px;">
                            <div class="qac-bar-fill" style="width: {{ $pagePct }}%; background: #FF9000"></div>
                        </div>
                    @endforeach
                @else
                    <p class="qac-empty">No page data yet.</p>
                @endif
            </div>

            <div class="qac-panel">
                <div class="qac-panel-head">
                    <div>
                        <h3 class="qac-title">Top Referrers</h3>
                        <p class="qac-subtitle">Where traffic comes from</p>
                    </div>
                    <x-filament::icon name="heroicon-o-globe-alt" class="qac-line-icon" style="color: #d1d5db" />
                </div>

                @if (count($referrers))
                    @php $referrerMax = max($referrers); @endphp
                    @foreach ($referrers as $host => $count)
                        @php $referrerPct = (int) round($count / $referrerMax * 100); @endphp
                        <div class="qac-row">
                            <span class="qac-host qac-host-truncate">{{ $host }}</span>
                            <span class="qac-small-num">{{ number_format($count) }}</span>
                        </div>
                        <div class="qac-bar-track" style="margin: 6px 0 0 0;">
                            <div class="qac-bar-fill" style="width: {{ $referrerPct }}%; background: #10b981"></div>
                        </div>
                    @endforeach
                @else
                    <p class="qac-empty">No external referrals yet.</p>
                @endif
            </div>
        </div>

        {{-- Top locations --}}
        @if (count($topLocations))
            <div class="qac-panel">
                <div class="qac-panel-head">
                    <div>
                        <h3 class="qac-title">Top Locations</h3>
                        <p class="qac-subtitle">Clicks grouped by region</p>
                    </div>
                    <x-filament::icon name="heroicon-o-map" class="qac-line-icon" style="color: #d1d5db" />
                </div>

                <div class="qac-grid-locs">
                    @foreach ($topLocations as $location)
                        <div class="qac-loc">
                            <div class="qac-loc-idx">
                                <span class="qac-loc-icon">
                                    <x-filament::icon name="heroicon-o-map-pin" />
                                </span>
                                <div class="qac-card-text">
                                    <p class="qac-loc-title">{{ $location['city'] ?? 'Unknown' }}</p>
                                    <p class="qac-loc-sub">
                                        @if (! empty($location['zip_code'])){{ $location['zip_code'] }}&middot;
                                        @endif @if (! empty($location['region'])){{ $location['region'] }}&middot;
                                        @endif {{ $location['country'] ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <span class="qac-count-pill">{{ number_format((int) $location['count']) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Recent visits --}}
        <div class="qac-panel">
            {{ $this->table }}
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('initTrafficChart', (data) => ({
                    init() {
                        const ctx = this.$el.getContext('2d')
                        const bodyColor = getComputedStyle(document.body).color

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [
                                    {
                                        label: 'Visits',
                                        data: data.visits,
                                        backgroundColor: (context) => {
                                            const { chartArea } = context.chart
                                            if (!chartArea) return 'rgba(255,144,0,0.85)'
                                            const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top)
                                            gradient.addColorStop(0, 'rgba(255,144,0,0.25)')
                                            gradient.addColorStop(1, 'rgba(255,144,0,0.92)')
                                            return gradient
                                        },
                                        hoverBackgroundColor: '#d97706',
                                        borderRadius: 5,
                                        borderSkipped: false,
                                        maxBarThickness: 26,
                                    },
                                    {
                                        label: 'Unique',
                                        data: data.uniques,
                                        backgroundColor: 'rgba(49,54,57,0.72)',
                                        hoverBackgroundColor: 'rgba(49,54,57,1)',
                                        borderRadius: 5,
                                        borderSkipped: false,
                                        maxBarThickness: 26,
                                    },
                                ],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: { mode: 'index', intersect: false },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        backgroundColor: 'rgba(15,23,42,0.92)',
                                        titleColor: '#ffffff',
                                        bodyColor: '#e2e8f0',
                                        padding: 10,
                                        cornerRadius: 8,
                                        boxPadding: 4,
                                        callbacks: {
                                            label: (item) => ` ${item.dataset.label}: ${item.parsed.y.toLocaleString()}`,
                                        },
                                    },
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        border: { display: false },
                                        ticks: { color: bodyColor, font: { size: 11 }, maxRotation: 0 },
                                    },
                                    y: {
                                        beginAtZero: true,
                                        border: { display: false },
                                        grid: { color: 'rgba(128,128,128,0.12)' },
                                        ticks: {
                                            color: bodyColor,
                                            font: { size: 11 },
                                            precision: 0,
                                            callback: (value) => value >= 1000 ? `${Math.round(value / 1000)}k` : value,
                                        },
                                    },
                                },
                            },
                        })
                    },
                }))

                Alpine.data('initDeviceChart', (data) => ({
                    init() {
                        const ctx = this.$el.getContext('2d')
                        const bodyColor = getComputedStyle(document.body).color

                        const centerTextPlugin = {
                            id: 'centerText',
                            afterDraw(chart) {
                                const meta = chart.getDatasetMeta(0)
                                if (!meta.data.length) return
                                const { x, y } = meta.data[0]
                                const total = chart.data.datasets[0].data.reduce((sum, item) => sum + item, 0)
                                const c = chart.ctx
                                c.save()
                                c.textAlign = 'center'
                                c.textBaseline = 'middle'
                                c.font = '700 20px system-ui, sans-serif'
                                c.fillStyle = bodyColor
                                c.fillText(total.toLocaleString(), x, y - 6)
                                c.font = '500 10px system-ui, sans-serif'
                                c.fillStyle = 'rgba(128,128,128,0.9)'
                                c.fillText('tracked records', x, y + 14)
                                c.restore()
                            },
                        }

                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    data: data.values,
                                    backgroundColor: data.colors,
                                    borderWidth: 0,
                                    hoverOffset: 4,
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '68%',
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: (item) => ` ${item.label}: ${item.parsed.toLocaleString()}`,
                                        },
                                    },
                                },
                            },
                            plugins: [centerTextPlugin],
                        })
                    },
                }))
            })
        </script>
    @endpush
</x-filament-panels::page>