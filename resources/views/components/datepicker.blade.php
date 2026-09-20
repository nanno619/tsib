@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => 'Select a date',
    'help' => null,
    'required' => false,
    'disabled' => false,
    'icon' => null,
    'inline' => false,
    'range' => false,
    'format' => 'YYYY-MM-DD',
    'minDate' => null,
    'maxDate' => null,
    'errorBag' => 'default',
])

@php
    $hasError = $errors->{$errorBag}->has($name);

    $controlClasses = ['form-control'];

    if ($hasError) {
        $controlClasses[] = 'is-invalid';
    }

    $resolvedValue = old($name, $value);
@endphp

<div class="mb-3">
    @if ($label)
        <label class="form-label {{ $required ? 'required' : '' }}" for="{{ $name }}">{{ $label }}</label>
    @endif

    @if ($inline)
        {{-- No visible <input> here — Litepicker renders the calendar
        straight into this div, and the picked date is written to the
        paired hidden input by resources/js/datepicker.js. --}}
        <input type="hidden" name="{{ $name }}" id="{{ $name }}_value" value="{{ $resolvedValue }}" />
        <div
            {{ $attributes->class(['datepicker-inline']) }}
            id="{{ $name }}"
            data-datepicker
            data-datepicker-inline
            data-datepicker-hidden-input="{{ $name }}_value"
            data-datepicker-format="{{ $format }}"
            @if ($range) data-datepicker-range @endif
            @if ($minDate) data-datepicker-min="{{ $minDate }}" @endif
            @if ($maxDate) data-datepicker-max="{{ $maxDate }}" @endif
        ></div>
    @else
        <div class="{{ $icon ? 'input-icon' : '' }}">
            <input
                {{ $attributes->class($controlClasses) }}
                type="text"
                id="{{ $name }}"
                name="{{ $name }}"
                value="{{ $resolvedValue }}"
                placeholder="{{ $placeholder }}"
                autocomplete="off"
                data-datepicker
                data-datepicker-format="{{ $format }}"
                @if ($range) data-datepicker-range @endif
                @if ($minDate) data-datepicker-min="{{ $minDate }}" @endif
                @if ($maxDate) data-datepicker-max="{{ $maxDate }}" @endif
                @if ($required) required @endif
                @if ($disabled) disabled @endif
            />

            @if ($icon)
                <span class="input-icon-addon">
                    <x-icon :name="$icon" />
                </span>
            @endif

            @if ($hasError)
                <div class="invalid-feedback">{{ $errors->{$errorBag}->first($name) }}</div>
            @elseif ($help)
                <div class="form-hint">{{ $help }}</div>
            @endif
        </div>
    @endif
</div>
