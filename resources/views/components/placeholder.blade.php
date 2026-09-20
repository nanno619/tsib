@props([
    'width' => 12,
    'size' => null,
    'color' => null,
])

@php
    $classes = ['placeholder', "col-{$width}"];

    if ($size) {
        $classes[] = "placeholder-{$size}";
    }

    if ($color) {
        $classes[] = "bg-{$color}";
    }
@endphp

<span {{ $attributes->class($classes) }}></span>
