@props([
    'src' => null,
    'initials' => null,
    'icon' => null,
    'size' => null,
    'shape' => null,
    'color' => null,
    'status' => null,
    'label' => null,
])

@php
    $classes = ['avatar'];

    if ($size) {
        $classes[] = "avatar-{$size}";
    }

    if ($shape === 'square') {
        $classes[] = 'avatar-square';
    } elseif ($shape) {
        $classes[] = $shape;
    }

    if ($color && ! $src) {
        $classes[] = "bg-{$color}-lt";
    }

    $statusColors = [
        'online' => 'success',
        'away' => 'warning',
        'busy' => 'danger',
        'offline' => 'secondary',
    ];

    $statusClass = $status ? 'bg-'.($statusColors[$status] ?? $status) : null;
@endphp

<span
    {{ $attributes->class($classes) }}
    @if ($src) style="background-image: url({{ $src }})" @endif
    @if ($label) aria-label="{{ $label }}" @endif
>
    @if ($icon)
        <x-icon :name="$icon" />
    @elseif ($initials)
        {{ $initials }}
    @elseif (! $src)
        {{ $slot }}
    @endif

    @if ($status)
        <span class="badge {{ $statusClass }}"></span>
    @endif
</span>
