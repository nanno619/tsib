@props([
    'grow' => false,
    'size' => null,
    'color' => null,
    'label' => 'Loading...',
])

@php
    $classes = [$grow ? 'spinner-grow' : 'spinner-border'];

    if ($size === 'sm') {
        $classes[] = $grow ? 'spinner-grow-sm' : 'spinner-border-sm';
    }

    if ($color) {
        $classes[] = "text-{$color}";
    }
@endphp

<div {{ $attributes->class($classes) }} role="status">
    @if ($label)
        <span class="visually-hidden">{{ $label }}</span>
    @endif
</div>
