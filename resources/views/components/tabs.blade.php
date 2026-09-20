@props([
    'items' => [],
    'selected' => null,
    'cardHeader' => false,
    'fill' => false,
    'label' => 'Tabs',
])

@php
    $classes = ['nav', 'nav-tabs'];

    if ($cardHeader) {
        $classes[] = 'card-header-tabs';
    }

    if ($fill) {
        $classes[] = 'nav-fill';
    }
@endphp

<ul {{ $attributes->class($classes) }} data-bs-toggle="tabs" role="tablist" aria-label="{{ $label }}">
    @foreach ($items as $index => $item)
        @php
            $isDisabled = $item['disabled'] ?? false;
            $isActive = $item['active'] ?? ($selected !== null && ($item['value'] ?? $index) == $selected);
            $tag = ! empty($item['href']) && ! $isDisabled ? 'a' : 'button';
        @endphp

        <li class="nav-item {{ ! empty($item['end']) ? 'ms-auto' : '' }}">
            <{{ $tag }}
                class="nav-link{{ $isActive ? ' active' : '' }}{{ $isDisabled ? ' disabled' : '' }}"
                role="tab"
                data-bs-toggle="tab"
                @if (! empty($item['target'])) data-bs-target="{{ $item['target'] }}" @endif
                aria-selected="{{ $isActive ? 'true' : 'false' }}"
                @if ($tag === 'a')
                    href="{{ $item['href'] }}"
                @else
                    type="button"
                    @if ($isDisabled) disabled @endif
                @endif
            >
                @if (! empty($item['icon']))
                    <x-icon :name="$item['icon']" class="{{ empty($item['label']) ? '' : 'me-2' }}" />
                @endif
                {{ $item['label'] ?? '' }}
            </{{ $tag }}>
        </li>
    @endforeach
</ul>
