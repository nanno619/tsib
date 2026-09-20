@props([
    'title' => null,
    'subtitle' => null,
    'headingLevel' => 'h3',
    'status' => null,
    'statusPosition' => 'top',
    'size' => null,
    'variant' => null,
    'stacked' => false,
    'href' => null,
    'lift' => false,
])

@php
    $classes = ['card'];

    if ($size) {
        $classes[] = "card-{$size}";
    }

    if ($variant) {
        $classes[] = "card-{$variant}";
    }

    if ($stacked) {
        $classes[] = 'card-stacked';
    }

    if ($href) {
        $classes[] = 'card-link';

        if ($lift) {
            $classes[] = 'card-link-pop';
        }
    }

    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }}
    {{ $attributes->class($classes) }}
    @if ($href) href="{{ $href }}" @endif
>
    @if ($status)
        <div class="card-status-{{ $statusPosition }} bg-{{ $status }}"></div>
    @endif

    @if ($title || isset($actions))
        <div class="card-header">
            @if ($title)
                <{{ $headingLevel }} class="card-title">{{ $title }}</{{ $headingLevel }}>
            @endif

            @isset($actions)
                <div class="card-actions">
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif

    <div class="card-body">
        @if ($subtitle)
            <p class="card-subtitle">{{ $subtitle }}</p>
        @endif

        {{ $slot }}
    </div>

    @isset($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endisset
</{{ $tag }}>
