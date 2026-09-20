@props([
    'id',
    'slides' => [],
    'indicators' => 'numbered',
    'vertical' => false,
    'controls' => true,
    'fade' => false,
    'ride' => 'carousel',
])

@php
    $carouselClasses = ['carousel', 'slide'];

    if ($fade) {
        $carouselClasses[] = 'carousel-fade';
    }

    $indicatorClasses = ['carousel-indicators'];

    if ($indicators === 'dot') {
        $indicatorClasses[] = 'carousel-indicators-dot';
    } elseif ($indicators === 'thumb') {
        $indicatorClasses[] = 'carousel-indicators-thumb';
    }

    if ($vertical) {
        $indicatorClasses[] = 'carousel-indicators-vertical';
    }
@endphp

<div id="{{ $id }}" {{ $attributes->class($carouselClasses) }} @if ($ride) data-bs-ride="{{ $ride }}" @endif>
    @if ($indicators)
        <div class="{{ implode(' ', $indicatorClasses) }}">
            @foreach ($slides as $index => $slide)
                <button
                    type="button"
                    data-bs-target="#{{ $id }}"
                    data-bs-slide-to="{{ $index }}"
                    class="{{ $index === 0 ? 'active' : '' }} {{ $indicators === 'thumb' ? 'ratio ratio-4x3' : '' }}"
                    @if ($indicators === 'thumb' && ! empty($slide['image'])) style="background-image: url({{ $slide['image'] }})" @endif
                ></button>
            @endforeach
        </div>
    @endif

    <div class="carousel-inner">
        @foreach ($slides as $index => $slide)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img class="d-block w-100" alt="{{ $slide['alt'] ?? '' }}" src="{{ $slide['image'] }}" />

                @if (! empty($slide['title']) || ! empty($slide['caption']))
                    <div class="carousel-caption-background d-none d-md-block"></div>
                    <div class="carousel-caption d-none d-md-block">
                        @if (! empty($slide['title']))
                            <h3>{{ $slide['title'] }}</h3>
                        @endif
                        @if (! empty($slide['caption']))
                            <p>{{ $slide['caption'] }}</p>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    @if ($controls)
        <a class="carousel-control-prev" data-bs-target="#{{ $id }}" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </a>
        <a class="carousel-control-next" data-bs-target="#{{ $id }}" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </a>
    @endif
</div>
