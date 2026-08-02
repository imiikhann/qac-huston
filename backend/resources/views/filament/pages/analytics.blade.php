<x-filament-panels::page>
    <div x-data="{ tab: 'overview' }" class="space-y-6">
        {{-- Tabs Navigation --}}
        <x-filament::tabs>
            <x-filament::tabs.item
                x-on:click="tab = 'overview'"
                x-bind:active="tab === 'overview'"
            >
                Overview
            </x-filament::tabs.item>
            <x-filament::tabs.item
                x-on:click="tab = 'daily'; $wire.$set('period', 'daily')"
                x-bind:active="tab === 'daily'"
            >
                Daily
            </x-filament::tabs.item>
            <x-filament::tabs.item
                x-on:click="tab = 'weekly'; $wire.$set('period', 'weekly')"
                x-bind:active="tab === 'weekly'"
            >
                Weekly
            </x-filament::tabs.item>
            <x-filament::tabs.item
                x-on:click="tab = 'monthly'; $wire.$set('period', 'monthly')"
                x-bind:active="tab === 'monthly'"
            >
                Monthly
            </x-filament::tabs.item>
        </x-filament::tabs>

        {{-- Overview Tab --}}
        <div x-show="tab === 'overview'" x-transition>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900">
                            <x-filament::icon name="heroicon-o-eye" class="w-6 h-6 text-blue-600 dark:text-blue-300" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Visits</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($this->getTotalVisits()) }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 dark:bg-green-900">
                            <x-filament::icon name="heroicon-o-users" class="w-6 h-6 text-green-600 dark:text-green-300" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Unique Visitors</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($this->getUniqueVisitors()) }}</p>
                        </div>
                    </div>
                </div>
                @php $topLoc = collect($this->getTopLocations())->first(); @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900">
                            <x-filament::icon name="heroicon-o-map-pin" class="w-6 h-6 text-purple-600 dark:text-purple-300" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Top City</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $topLoc['city'] ?? '-' }}</p>
                            @if ($topLoc)
                                <p class="text-xs text-gray-400">{{ $topLoc['count'] }} visits &middot; {{ $topLoc['zip_code'] ?? '' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daily Tab --}}
        <div x-show="tab === 'daily'" x-transition>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900">
                            <x-filament::icon name="heroicon-o-calendar" class="w-6 h-6 text-amber-600 dark:text-amber-300" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Daily Visits</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getPeriodVisits() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900">
                            <x-filament::icon name="heroicon-o-user-group" class="w-6 h-6 text-purple-600 dark:text-purple-300" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Daily Unique</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getPeriodUnique() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Daily Visitors</h3>
                <div style="position: relative; height: 280px;">
                    <canvas
                        x-data="initBarChart(@js($this->getBarChartData()))"
                        x-init="init()"
                        wire:ignore
                    ></canvas>
                </div>
            </div>
        </div>

        {{-- Weekly Tab --}}
        <div x-show="tab === 'weekly'" x-transition>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900">
                            <x-filament::icon name="heroicon-o-calendar" class="w-6 h-6 text-amber-600 dark:text-amber-300" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Weekly Visits</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getPeriodVisits() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900">
                            <x-filament::icon name="heroicon-o-user-group" class="w-6 h-6 text-purple-600 dark:text-purple-300" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Weekly Unique</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getPeriodUnique() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Weekly Visitors</h3>
                <div style="position: relative; height: 280px;">
                    <canvas
                        x-data="initBarChart(@js($this->getBarChartData()))"
                        x-init="init()"
                        wire:ignore
                    ></canvas>
                </div>
            </div>
        </div>

        {{-- Monthly Tab --}}
        <div x-show="tab === 'monthly'" x-transition>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900">
                            <x-filament::icon name="heroicon-o-calendar" class="w-6 h-6 text-amber-600 dark:text-amber-300" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Monthly Visits</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getPeriodVisits() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-x-4">
                        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900">
                            <x-filament::icon name="heroicon-o-user-group" class="w-6 h-6 text-purple-600 dark:text-purple-300" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Monthly Unique</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->getPeriodUnique() }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Monthly Visitors</h3>
                <div style="position: relative; height: 280px;">
                    <canvas
                        x-data="initBarChart(@js($this->getBarChartData()))"
                        x-init="init()"
                        wire:ignore
                    ></canvas>
                </div>
            </div>
        </div>

        {{-- Pie Chart & Top Pages - shown in all tabs --}}
        <div class="grid gap-6 lg:grid-cols-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Breakdown</h3>
                <div style="position: relative; height: 280px;" class="flex items-center justify-center">
                    <canvas
                        x-data="initPieChart(@js($this->getPieChartData()))"
                        x-init="init()"
                        wire:ignore
                        style="max-width: 260px; max-height: 260px;"
                    ></canvas>
                </div>
            </div>

            @php $topPages = $this->getTopPages(); @endphp
            @if (count($topPages))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Top Pages</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-2 pr-4 font-medium text-gray-500 dark:text-gray-400">Page</th>
                                    <th class="text-right py-2 px-4 font-medium text-gray-500 dark:text-gray-400">Visits</th>
                                    <th class="text-right py-2 pl-4 font-medium text-gray-500 dark:text-gray-400">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalVisits = $this->getTotalVisits(); @endphp
                                @foreach ($topPages as $page)
                                    <tr class="border-b border-gray-100 dark:border-gray-700/50">
                                        <td class="py-2 pr-4 text-gray-900 dark:text-white">
                                            <span class="text-xs font-mono bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">/{{ ltrim($page['url'], '/') }}</span>
                                        </td>
                                        <td class="py-2 px-4 text-right text-gray-700 dark:text-gray-300">{{ $page['count'] }}</td>
                                        <td class="py-2 pl-4 text-right text-gray-500">
                                            @php $pct = $totalVisits > 0 ? round(($page['count'] / $totalVisits) * 100, 1) : 0; @endphp
                                            <div class="flex items-center justify-end gap-2">
                                                <div class="w-16 h-1.5 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                                    <div class="h-full bg-amber-500 rounded-full" style="width: {{ $pct }}%"></div>
                                                </div>
                                                <span class="text-xs w-8 text-right">{{ $pct }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        {{-- Top Locations --}}
        @php $topLocations = $this->getTopLocations(); @endphp
        @if (count($topLocations))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 dark:bg-gray-800 dark:border-gray-700">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4">Top Locations</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <th class="text-left py-2 pr-4 font-medium text-gray-500 dark:text-gray-400">City</th>
                                <th class="text-left py-2 px-4 font-medium text-gray-500 dark:text-gray-400">Zip</th>
                                <th class="text-left py-2 px-4 font-medium text-gray-500 dark:text-gray-400">State</th>
                                <th class="text-left py-2 px-4 font-medium text-gray-500 dark:text-gray-400">Country</th>
                                <th class="text-right py-2 pl-4 font-medium text-gray-500 dark:text-gray-400">Visits</th>
                                <th class="text-right py-2 pl-4 font-medium text-gray-500 dark:text-gray-400">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalVisits = $this->getTotalVisits(); @endphp
                            @foreach ($topLocations as $loc)
                                <tr class="border-b border-gray-100 dark:border-gray-700/50">
                                    <td class="py-2 pr-4 text-gray-900 dark:text-white">{{ $loc['city'] ?? '-' }}</td>
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">{{ $loc['zip_code'] ?? '-' }}</td>
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">{{ $loc['region'] ?? '-' }}</td>
                                    <td class="py-2 px-4 text-gray-700 dark:text-gray-300">{{ $loc['country'] ?? '-' }}</td>
                                    <td class="py-2 pl-4 text-right text-gray-700 dark:text-gray-300">{{ $loc['count'] }}</td>
                                    <td class="py-2 pl-4 text-right text-gray-500">
                                        @php $pct = $totalVisits > 0 ? round(($loc['count'] / $totalVisits) * 100, 1) : 0; @endphp
                                        <div class="flex items-center justify-end gap-2">
                                            <div class="w-16 h-1.5 bg-gray-200 dark:bg-gray-600 rounded-full overflow-hidden">
                                                <div class="h-full bg-blue-500 rounded-full" style="width: {{ $pct }}%"></div>
                                            </div>
                                            <span class="text-xs w-8 text-right">{{ $pct }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- Recent Visits --}}
        {{ $this->table }}
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('initBarChart', (data) => ({
                    init() {
                        const ctx = this.$el.getContext('2d')
                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [
                                    { label: 'Visits', data: data.visits, backgroundColor: '#FF9000', borderColor: '#FF9000', borderWidth: 1, borderRadius: 4 },
                                    { label: 'Unique', data: data.uniques, backgroundColor: '#313639', borderColor: '#313639', borderWidth: 1, borderRadius: 4 },
                                ],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { position: 'top', labels: { boxWidth: 12, usePointStyle: true, pointStyle: 'circle' } } },
                                scales: {
                                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                                    x: { grid: { display: false } },
                                },
                            },
                        })
                    },
                }))

                Alpine.data('initPieChart', (data) => ({
                    init() {
                        const ctx = this.$el.getContext('2d')
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    data: data.values,
                                    backgroundColor: data.colors,
                                    borderWidth: 3,
                                    borderColor: '#ffffff',
                                    hoverOffset: 6,
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '55%',
                                plugins: {
                                    legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true, padding: 16 } },
                                },
                            },
                        })
                    },
                }))
            })
        </script>
    @endpush
</x-filament-panels::page>
