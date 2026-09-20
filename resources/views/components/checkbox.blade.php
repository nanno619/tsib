@props([
    'name',
    'value' => '1',
    'checked' => false,
    'switch' => false,
    'inline' => false,
    'disabled' => false,
])

@php
    $classes = ['form-check'];

    if ($switch) {
        $classes[] = 'form-switch';
    }

    if ($inline) {
        $classes[] = 'form-check-inline';
    }
@endphp

<label class="{{ implode(' ', $classes) }}">
    <input
        {{ $attributes->class(['form-check-input']) }}
        type="checkbox"
        name="{{ $name }}"
        value="{{ $value }}"
        @if ($checked) checked @endif
        @if ($disabled) disabled @endif
    />
    <span class="form-check-label">{{ $slot }}</span>
</label>
