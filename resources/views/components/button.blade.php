@props([
    'color' => null,
    'variant' => null,
    'size' => null,
    'pill' => false,
    'square' => false,
    'icon' => null,
    'iconPosition' => 'start',
    'loading' => false,
    'disabled' => false,
    'block' => false,
    'href' => null,
    'type' => 'button',
    'label' => null,
])

@php
    $classes = ['btn'];

    if ($color) {
        $classes[] = match ($variant) {
            'outline' => "btn-outline-{$color}",
            'ghost' => "btn-ghost-{$color}",
            default => "btn-{$color}",
        };
    }

    if ($size) {
        $classes[] = "btn-{$size}";
    }

    if ($pill) {
        $classes[] = 'btn-pill';
    }

    if ($square) {
        $classes[] = 'btn-square';
    }

    if ($loading) {
        $classes[] = 'btn-loading';
    }

    if ($block) {
        $classes[] = 'w-100';
    }

    $iconOnly = $icon && $slot->isEmpty();

    if ($iconOnly) {
        $classes[] = 'btn-icon';
    }

    $isDisabled = $disabled || $loading;

    if ($isDisabled) {
        $classes[] = 'disabled';
    }

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    {{ $attributes->class($classes) }}
    @if ($tag === 'a')
        href="{{ $href }}"
        role="button"
        @if ($isDisabled) aria-disabled="true" tabindex="-1" @endif
    @else
        type="{{ $type }}"
        @if ($isDisabled) disabled @endif
    @endif
    @if ($loading) aria-busy="true" @endif
    @if ($label) aria-label="{{ $label }}" @endif
>
    @if ($icon && $iconPosition === 'start')
        <x-icon :name="$icon" />
    @endif

    {{ $slot }}

    @if ($icon && $iconPosition === 'end')
        <x-icon :name="$icon" class="icon-end" />
    @endif
</{{ $tag }}>
