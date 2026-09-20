@props([
    'vertical' => null,
    'horizontal' => null,
    'color' => null,
    'bookmark' => false,
    'icon' => null,
])

@php
    $classes = ['ribbon'];

    if ($vertical) {
        $classes[] = "ribbon-{$vertical}";
    }

    if ($horizontal) {
        $classes[] = "ribbon-{$horizontal}";
    }

    if ($bookmark) {
        $classes[] = 'ribbon-bookmark';
    }

    if ($color) {
        $classes[] = "bg-{$color}";
    }
@endphp

<div {{ $attributes->class($classes) }}>
    @if ($icon)
        <x-icon :name="$icon" />
    @else
        {{ $slot }}
    @endif
</div>
