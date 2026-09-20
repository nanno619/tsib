@props([
    'value' => 0,
    'max' => 100,
    'color' => null,
    'size' => null,
    'striped' => false,
    'animated' => false,
    'indeterminate' => false,
    'label' => null,
])

@php
    $wrapperClasses = ['progress'];

    if ($size === 'sm') {
        $wrapperClasses[] = 'progress-sm';
    }

    $barClasses = ['progress-bar'];

    if ($color) {
        $barClasses[] = "bg-{$color}";
    }

    if ($striped) {
        $barClasses[] = 'progress-bar-striped';
    }

    if ($animated) {
        $barClasses[] = 'progress-bar-animated';
    }

    if ($indeterminate) {
        $barClasses[] = 'progress-bar-indeterminate';
    }
@endphp

<div {{ $attributes->class($wrapperClasses) }}>
    <div
        class="{{ implode(' ', $barClasses) }}"
        @unless ($indeterminate)
            style="width: {{ $value }}%"
            role="progressbar"
            aria-valuenow="{{ $value }}"
            aria-valuemin="0"
            aria-valuemax="{{ $max }}"
            @if ($label) aria-label="{{ $label }}" @endif
        @endunless
    >
        @if ($label && ! $indeterminate)
            <span class="visually-hidden">{{ $label }}</span>
        @endif
    </div>
</div>
