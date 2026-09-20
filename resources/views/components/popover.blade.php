@props([
    'content',
    'title' => null,
    'placement' => 'top',
    'trigger' => null,
    'html' => false,
    'container' => null,
    'as' => 'button',
])

<{{ $as }}
    {{ $attributes }}
    @if ($as === 'button') type="button" @endif
    data-bs-toggle="popover"
    data-bs-placement="{{ $placement }}"
    @if ($title) title="{{ $title }}" @endif
    data-bs-content="{{ $content }}"
    @if ($html) data-bs-html="true" @endif
    @if ($trigger) data-bs-trigger="{{ $trigger }}" @endif
    @if ($container) data-bs-container="{{ $container }}" @endif
>
    {{ $slot }}
</{{ $as }}>
