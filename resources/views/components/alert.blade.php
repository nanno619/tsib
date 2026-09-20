@props([
    'type' => 'info',
    'variant' => null,
    'title' => null,
    'icon' => true,
    'dismissible' => false,
    'role' => null,
])

@php
    $autoIcons = [
        'success' => 'check',
        'info' => 'info-circle',
        'warning' => 'alert-triangle',
        'danger' => 'alert-circle',
    ];

    $iconName = match (true) {
        $icon === false => null,
        is_string($icon) => $icon,
        default => $autoIcons[$type] ?? null,
    };

    $role ??= in_array($type, ['warning', 'danger']) ? 'alert' : 'status';

    $classes = ['alert', "alert-{$type}"];

    if ($variant === 'important') {
        $classes[] = 'alert-important';
    }

    if ($variant === 'minor') {
        $classes[] = 'alert-minor';
    }

    if ($dismissible) {
        $classes[] = 'alert-dismissible';
    }
@endphp

<div {{ $attributes->class($classes) }} role="{{ $role }}">
    @if ($iconName)
        <div class="alert-icon">
            <x-icon :name="$iconName" />
        </div>
    @endif

    <div>
        @if ($title)
            <h4 class="alert-heading">{{ $title }}</h4>
            <div class="alert-description">{{ $slot }}</div>
        @else
            {{ $slot }}
        @endif

        @isset($actions)
            <div class="btn-list mt-2">
                {{ $actions }}
            </div>
        @endisset
    </div>

    @if ($dismissible)
        <a class="btn-close{{ $variant === 'important' ? ' btn-close-white' : '' }}" data-bs-dismiss="alert" aria-label="close"></a>
    @endif
</div>
