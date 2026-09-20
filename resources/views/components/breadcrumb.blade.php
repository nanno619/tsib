@props([
    'items' => [],
    'variant' => null,
    'muted' => false,
])

@php
    $classes = ['breadcrumb'];

    if ($variant) {
        $classes[] = "breadcrumb-{$variant}";
    }

    if ($muted) {
        $classes[] = 'breadcrumb-muted';
    }
@endphp

<nav aria-label="Breadcrumb">
    <ol {{ $attributes->class($classes) }}>
        @foreach ($items as $item)
            @if ($loop->last)
                {{-- The current page is never a link. --}}
                <li class="breadcrumb-item active" aria-current="page">
                    @if (! empty($item['icon']))
                        <x-icon :name="$item['icon']" />
                    @endif
                    {{ $item['label'] }}
                </li>
            @elseif (! empty($item['url']))
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] }}">
                        @if (! empty($item['icon']))
                            <x-icon :name="$item['icon']" />
                        @endif
                        {{ $item['label'] }}
                    </a>
                </li>
            @else
                <li class="breadcrumb-item">
                    @if (! empty($item['icon']))
                        <x-icon :name="$item['icon']" />
                    @endif
                    {{ $item['label'] }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
