@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'help' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'size' => null,
    'icon' => null,
    'errorBag' => 'default',
])

@php
    $hasError = $errors->{$errorBag}->has($name);

    $controlClasses = ['form-control'];

    if ($size) {
        $controlClasses[] = "form-control-{$size}";
    }

    if ($hasError) {
        $controlClasses[] = 'is-invalid';
    }

    $resolvedValue = $type === 'password' ? '' : old($name, $value);
@endphp

{{--
    $attributes (autocomplete, autofocus, wire:model, x-model, data-*, ...)
    land on the actual <input>/<textarea>, not this wrapper — that's what
    callers mean when they pass an extra attribute to a form field.

    The invalid-feedback/form-hint line must stay a direct sibling of the
    control inside the inner div — Tabler's CSS shows it via
    `.is-invalid ~ .invalid-feedback`, a sibling selector that wouldn't
    match if the feedback lived outside that div instead.
--}}
<div class="mb-3">
    @if ($label)
        <label class="form-label {{ $required ? 'required' : '' }}" for="{{ $name }}">
            {{ $label }}
            @isset($labelAddon)
                <span class="form-label-description">{{ $labelAddon }}</span>
            @endisset
        </label>
    @endif

    <div class="{{ $icon ? 'input-icon' : '' }}">
        @if ($type === 'textarea')
            <textarea
                {{ $attributes->class($controlClasses) }}
                id="{{ $name }}"
                name="{{ $name }}"
                placeholder="{{ $placeholder }}"
                @if ($required) required @endif
                @if ($disabled) disabled @endif
                @if ($readonly) readonly @endif
            >{{ $resolvedValue }}</textarea>
        @else
            <input
                {{ $attributes->class($controlClasses) }}
                type="{{ $type }}"
                id="{{ $name }}"
                name="{{ $name }}"
                value="{{ $resolvedValue }}"
                placeholder="{{ $placeholder }}"
                @if ($required) required @endif
                @if ($disabled) disabled @endif
                @if ($readonly) readonly @endif
            />
        @endif

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
</div>
