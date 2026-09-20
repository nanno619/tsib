@props([
    'color' => null,
    'variant' => 'solid',
    'pill' => false,
    'size' => null,
    'dot' => false,
    'href' => null,
    'icon' => null,
    'notification' => false,
    'blink' => false,
    'label' => null,
])

@php
    $classes = ['badge'];

    if ($size === 'sm') {
        $classes[] = 'badge-sm';
    } elseif ($size === 'lg') {
        $classes[] = 'badge-lg';
    }

    if ($pill) {
        $classes[] = 'badge-pill';
    }

    if ($notification) {
        $classes[] = 'badge-notification';
    }

    if ($blink) {
        $classes[] = 'badge-blink';
    }

    if ($dot) {
        $classes[] = 'badge-dot';
    }

    if ($color) {
        if ($dot) {
            $classes[] = "bg-{$color}";
        } else {
            $classes[] = match ($variant) {
                'light' => "bg-{$color}-lt",
                'outline' => "badge-outline text-{$color}",
                default => "bg-{$color} text-{$color}-fg",
            };
        }
    }

    $iconOnly = $icon && $slot->isEmpty() && ! $dot;

    if ($iconOnly) {
        $classes[] = 'badge-icononly';
    }

    $tag = $href ? 'a' : 'span';
@endphp

<{{ $tag }}
    {{ $attributes->class($classes) }}
    @if ($href) href="{{ $href }}" @endif
>
    @if ($dot)
        @if ($label)
            <span class="visually-hidden">{{ $label }}</span>
        @endif
    @else
        @if ($icon)
            <x-icon :name="$icon" />
        @endif
        {{ $slot }}
    @endif
</{{ $tag }}>
