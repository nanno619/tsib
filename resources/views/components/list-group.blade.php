@props([
    'as' => 'div',
    'transparent' => false,
    'flush' => false,
    'hoverable' => false,
])

@php
    $classes = ['list-group'];

    if ($transparent) {
        $classes[] = 'list-group-transparent';
    }

    if ($flush) {
        $classes[] = 'list-group-flush';
    }

    if ($hoverable) {
        $classes[] = 'list-group-hoverable';
    }
@endphp

<{{ $as }} {{ $attributes->class($classes) }}>
    {{ $slot }}
</{{ $as }}>
