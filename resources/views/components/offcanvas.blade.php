{{--
    A reusable offcanvas panel — a slide-in drawer for filters, details or
    secondary forms.

    Open it from anywhere with Bootstrap's data-API; no JS of our own is needed:

        <x-button data-bs-toggle="offcanvas" data-bs-target="#my-panel"
                  aria-controls="my-panel">Filters</x-button>

    The footer is outside the body, so a submit button there has to point back
    at the form with form="…" — see the filter panel on /admin/users.
--}}
@props([
    'id',
    'title' => null,
    // start | end | top | bottom
    'position' => 'end',
    // sm | md | lg | xl | xxl — static above that breakpoint instead of sliding
    'responsive' => null,
    // Tabler's narrower drawer width
    'narrow' => false,
    // Clicking the backdrop no longer dismisses it
    'static' => false,
])

@php
    $classes = ['offcanvas', "offcanvas-{$position}"];

    if ($responsive) {
        $classes[] = "offcanvas-{$responsive}";
    }

    if ($narrow) {
        $classes[] = 'offcanvas-narrow';
    }

    $titleId = "{$id}-title";
@endphp

<div
    {{ $attributes->class($classes) }}
    id="{{ $id }}"
    tabindex="-1"
    role="dialog"
    aria-modal="true"
    @if ($title) aria-labelledby="{{ $titleId }}" @endif
    @if ($static) data-bs-backdrop="static" data-bs-keyboard="false" @endif
>
    <div class="offcanvas-header">
        @if ($title)
            <h2 class="offcanvas-title" id="{{ $titleId }}">{{ $title }}</h2>
        @endif

        {{-- .offcanvas-header is justify-content: space-between, so a close
             button with no title beside it would sit hard left without this. --}}
        <button type="button" class="btn-close{{ $title ? '' : ' ms-auto' }}"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        {{ $slot }}
    </div>

    @isset($footer)
        <div class="offcanvas-footer">
            {{ $footer }}
        </div>
    @endisset
</div>
