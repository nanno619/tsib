@props([
    'paginator',
    'variant' => null,
    'onEachSide' => 1,
    'withLabels' => false,
    'label' => 'Pagination',
])

@php
    $isLengthAware = method_exists($paginator, 'lastPage');
    $current = $paginator->currentPage();
    $last = $isLengthAware ? $paginator->lastPage() : null;

    $classes = ['pagination'];

    if ($variant === 'outline' || $variant === 'circle-outline') {
        $classes[] = 'pagination-outline';
    }

    if ($variant === 'circle' || $variant === 'circle-outline') {
        $classes[] = 'pagination-circle';
    }

    $pages = [];

    if ($isLengthAware && $last > 1) {
        $start = max($current - $onEachSide, 1);
        $end = min($current + $onEachSide, $last);

        if ($start > 1) {
            $pages[] = 1;

            if ($start > 2) {
                $pages[] = '...';
            }
        }

        for ($page = $start; $page <= $end; $page++) {
            $pages[] = $page;
        }

        if ($end < $last) {
            if ($end < $last - 1) {
                $pages[] = '...';
            }

            $pages[] = $last;
        }
    }
@endphp

<nav aria-label="{{ $label }}" {{ $attributes }}>
    <ul class="{{ implode(' ', $classes) }}">
        <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
            @if ($paginator->onFirstPage())
                <span class="page-link {{ $withLabels ? 'page-text' : '' }}" tabindex="-1" aria-disabled="true">
                    @if ($withLabels)
                        Previous
                    @else
                        <x-icon name="chevron-left" />
                    @endif
                </span>
            @else
                <a class="page-link {{ $withLabels ? 'page-text' : '' }}" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    @if ($withLabels)
                        Previous
                    @else
                        <x-icon name="chevron-left" />
                    @endif
                </a>
            @endif
        </li>

        @foreach ($pages as $page)
            @if ($page === '...')
                <li class="page-item"><span class="page-link disabled">&hellip;</span></li>
            @else
                <li class="page-item {{ $page === $current ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a>
                </li>
            @endif
        @endforeach

        <li class="page-item {{ ! $paginator->hasMorePages() ? 'disabled' : '' }}">
            @if (! $paginator->hasMorePages())
                <span class="page-link {{ $withLabels ? 'page-text' : '' }}" aria-disabled="true">
                    @if ($withLabels)
                        Next
                    @else
                        <x-icon name="chevron-right" />
                    @endif
                </span>
            @else
                <a class="page-link {{ $withLabels ? 'page-text' : '' }}" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    @if ($withLabels)
                        Next
                    @else
                        <x-icon name="chevron-right" />
                    @endif
                </a>
            @endif
        </li>
    </ul>
</nav>
