@props([
    'text',
    'placement' => 'top',
    'html' => false,
    'as' => 'span',
])

<{{ $as }}
    {{ $attributes }}
    data-bs-toggle="tooltip"
    data-bs-placement="{{ $placement }}"
    @if ($html) data-bs-html="true" @endif
    title="{{ $text }}"
>
    {{ $slot }}
</{{ $as }}>
