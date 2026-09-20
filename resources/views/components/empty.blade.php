@props([
    'icon' => null,
    'header' => null,
    'title',
    'subtitle' => null,
    // A full-page empty state (an error page) needs a real heading so the page
    // has an h1; nested inside a card it would fight the card's own title.
    'headingLevel' => 'p',
])

<div {{ $attributes->class(['empty']) }}>
    @if ($icon)
        <div class="empty-icon">
            <x-icon :name="$icon" />
        </div>
    @elseif ($header)
        <div class="empty-header">{{ $header }}</div>
    @elseif (isset($image))
        <div class="empty-img">
            {{ $image }}
        </div>
    @endif

    <{{ $headingLevel }} class="empty-title">{{ $title }}</{{ $headingLevel }}>

    @if ($subtitle)
        <p class="empty-subtitle text-secondary">{{ $subtitle }}</p>
    @endif

    @isset($actions)
        <div class="empty-action">
            {{ $actions }}
        </div>
    @endisset
</div>
