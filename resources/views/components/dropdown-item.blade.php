@props([
    'href' => null,
    'type' => 'button',
    'icon' => null,
    'badge' => null,
    'badgeColor' => 'primary',
    'active' => false,
    'disabled' => false,
])

@php
    $classes = ['dropdown-item'];

    if ($active) {
        $classes[] = 'active';
    }

    if ($disabled) {
        $classes[] = 'disabled';
    }

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    {{ $attributes->class($classes) }}
    @if ($tag === 'a')
        href="{{ $href }}"
        @if ($disabled) aria-disabled="true" tabindex="-1" @endif
    @else
        type="{{ $type }}"
        @if ($disabled) disabled @endif
    @endif
>
    @if ($icon)
        <x-icon :name="$icon" class="dropdown-item-icon" />
    @endif

    {{ $slot }}

    @if ($badge)
        <x-badge :color="$badgeColor" class="ms-auto">{{ $badge }}</x-badge>
    @endif
</{{ $tag }}>
