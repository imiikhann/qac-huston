<div class="qac-cards">
    @foreach ($cards as $card)
        <div class="qac-card">
            <span class="qac-card-accent" style="background: {{ $card['color'] }}"></span>
            <div>
                <div class="qac-card-body">
                    <div class="qac-card-idx">
                        <span class="qac-icon-box" style="background: {{ $card['bg'] }}; color: {{ $card['color'] }}">
                            <x-filament::icon :name="$card['icon']" class="qac-line-icon" />
                        </span>
                        <div class="qac-card-text">
                            <p class="qac-card-label">{{ $card['label'] }}</p>
                            <p class="qac-card-value">{{ $card['value'] }}</p>
                        </div>
                    </div>

                    @isset($card['badge'])
                        <span class="qac-badge" style="background: {{ $card['badge']['bg'] }}; color: {{ $card['badge']['color'] }}">
                            @if (($card['badge']['direction'] ?? '') === 'up')
                                <x-filament::icon name="heroicon-o-arrow-trending-up" />
                            @elseif (($card['badge']['direction'] ?? '') === 'down')
                                <x-filament::icon name="heroicon-o-arrow-trending-down" />
                            @endif
                            {{ $card['badge']['text'] }}
                        </span>
                    @endisset
                </div>

                @isset($card['footer'])
                    <p class="qac-card-footer">{!! $card['footer'] !!}</p>
                @endisset
            </div>
        </div>
    @endforeach
</div>