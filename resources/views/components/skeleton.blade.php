@props([
    'animation' => 'glow',
    'as' => 'div',
])

<{{ $as }} {{ $attributes->class(["placeholder-{$animation}"]) }}>
    {{ $slot }}
</{{ $as }}>
