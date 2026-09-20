@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'help' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'size' => null,
    'advanced' => false,
    'errorBag' => 'default',
])

@php
    $hasError = $errors->{$errorBag}->has($name);

    $classes = ['form-select'];

    if ($size) {
        $classes[] = "form-select-{$size}";
    }

    if ($hasError) {
        $classes[] = 'is-invalid';
    }

    $selected = old($name, $value);
    $selectedValues = $multiple ? (array) $selected : [$selected];

    // A plain "please choose" <option> works for the native control, but
    // Tom Select (advanced) shows its own ghost placeholder text instead —
    // an extra selectable placeholder row would just be redundant there.
    $showPlaceholderOption = $placeholder && ! $multiple && ! $advanced;
@endphp

<div class="mb-3">
    @if ($label)
        <label class="form-label {{ $required ? 'required' : '' }}" for="{{ $name }}">{{ $label }}</label>
    @endif

    <select
        {{ $attributes->class($classes) }}
        id="{{ $name }}"
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        @if ($advanced) data-advanced-select @if ($placeholder) data-placeholder="{{ $placeholder }}" @endif @endif
        @if ($required) required @endif
        @if ($disabled) disabled @endif
        @if ($multiple) multiple @endif
    >
        @if ($showPlaceholderOption)
            <option value="" disabled {{ in_array(null, $selectedValues, true) || $selected === null ? 'selected' : '' }}>{{ $placeholder }}</option>
        @endif

        @foreach ($options as $optionValue => $option)
            @php
                $optionLabel = is_array($option) ? ($option['label'] ?? '') : $option;
                $optionHtml = is_array($option) ? ($option['html'] ?? null) : null;
            @endphp

            <option
                value="{{ $optionValue }}"
                {{-- Only meaningful to Tom Select, which reads this when it
                     enhances the control; a native <select> can't render
                     markup inside an option, so emitting it there is dead
                     weight. --}}
                @if ($optionHtml && $advanced) data-custom-properties="{{ $optionHtml }}" @endif
                {{ in_array((string) $optionValue, array_map('strval', $selectedValues), true) ? 'selected' : '' }}
            >
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @if ($hasError)
        <div class="invalid-feedback">{{ $errors->{$errorBag}->first($name) }}</div>
    @elseif ($help)
        <div class="form-hint">{{ $help }}</div>
    @endif
</div>
