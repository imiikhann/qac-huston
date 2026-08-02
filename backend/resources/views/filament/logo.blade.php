@php
    $settings = \App\Models\SiteSetting::first();
    $lightLogo = $settings?->logo_light ? asset('storage/' . $settings->logo_light) : null;
    $darkLogo = $settings?->logo_dark ? asset('storage/' . $settings->logo_dark) : null;
    $fallback = $settings?->logo ? asset('storage/' . $settings->logo) : asset('storage/qac-logo.png');
    $maxWidth = $settings?->logo_max_width ?? '180px';
    $maxHeight = $settings?->logo_max_height ?? '40px';
@endphp

@if ($lightLogo)
    <img src="{{ $lightLogo }}"
         alt="QAC Houston"
         style="max-width: {{ $maxWidth }}; max-height: {{ $maxHeight }}; width: auto; height: auto;"
         class="block dark:hidden"
         onerror="this.style.display='none'">
    @if ($darkLogo)
        <img src="{{ $darkLogo }}"
             alt="QAC Houston"
             style="max-width: {{ $maxWidth }}; max-height: {{ $maxHeight }}; width: auto; height: auto;"
             class="hidden dark:block"
             onerror="this.style.display='none'">
    @else
        <img src="{{ $lightLogo }}"
             alt="QAC Houston"
             style="max-width: {{ $maxWidth }}; max-height: {{ $maxHeight }}; width: auto; height: auto;"
             class="hidden dark:block"
             onerror="this.style.display='none'">
    @endif
@else
    <img src="{{ $fallback }}"
         alt="QAC Houston"
         style="max-width: {{ $maxWidth }}; max-height: {{ $maxHeight }}; width: auto; height: auto;"
         onerror="this.style.display='none'">
@endif
