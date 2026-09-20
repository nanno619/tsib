@props([
    'href' => null,
    'action' => null,
    'active' => false,
    'flushEdge' => false,
])

@php
    $isAction = $action ?? (bool) $href;

    $classes = ['list-group-item'];

    if ($isAction) {
        $classes[] = 'list-group-item-action';
    }

    if ($active) {
        $classes[] = 'active';
    }

    if ($flushEdge) {
        $classes[] = 'border-0 rounded-0';
    }

    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} {{ $attributes->class($classes) }} @if ($tag === 'a') href="{{ $href }}" @endif>
    {{ $slot }}
</{{ $tag }}>
