@props([
    'color' => 'secondary',
    'dot' => true,
    'animated' => false,
    'lite' => false,
    'indicator' => false,
    'label' => null,
])

@php
    $hasVisibleLabel = trim((string) $slot) !== '';
@endphp

@if ($indicator)
    <span {{ $attributes->class(['status-indicator', "status-{$color}", $animated ? 'status-indicator-animated' : '']) }} @if ($label) aria-label="{{ $label }}" @endif>
        <span class="status-indicator-circle"></span>
        <span class="status-indicator-circle"></span>
        <span class="status-indicator-circle"></span>
    </span>
@elseif ($dot && ! $hasVisibleLabel)
    {{-- Standalone dot, no visible label: color lives on the dot itself. --}}
    <span {{ $attributes->class(['status-dot', "status-{$color}", $animated ? 'status-dot-animated' : '']) }}>
        @if ($label)
            <span class="visually-hidden">{{ $label }}</span>
        @endif
    </span>
@else
    <span {{ $attributes->class(['status', "status-{$color}", $lite ? 'status-lite' : '']) }}>
        @if ($dot)
            <span class="status-dot {{ $animated ? 'status-dot-animated' : '' }}"></span>
        @endif
        {{ $slot }}
    </span>
@endif
