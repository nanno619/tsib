@props([
    'items' => [],
    'selected' => null,
    'size' => null,
    'vertical' => false,
    'block' => false,
    'label' => null,
])

@php
    $classes = ['nav', 'nav-segmented'];

    if ($size) {
        $classes[] = "nav-{$size}";
    }

    if ($vertical) {
        $classes[] = 'nav-segmented-vertical';
    }

    if ($block) {
        $classes[] = 'w-100';
    }

    // Items carrying a `target` drive Bootstrap tab panes; items without one
    // are plain navigation. That distinction has to be made here, because
    // Bootstrap's tab data-API calls preventDefault() on <a> — emitting
    // data-bs-toggle="tab" on a navigation link stops it working entirely, and
    // the roving tabindex="-1" would drop the inactive links out of the tab
    // order too.
    $isTabList = collect($items)->contains(fn (array $item) => ! empty($item['target']));
@endphp

<nav @if ($isTabList) role="tablist" @endif {{ $attributes->class($classes) }} @if ($label) aria-label="{{ $label }}" @endif>
    @foreach ($items as $index => $item)
        @php
            $isDisabled = $item['disabled'] ?? false;
            $isActive = $item['active'] ?? ($selected !== null && ($item['value'] ?? $index) == $selected);
            $tag = ! empty($item['href']) && ! $isDisabled ? 'a' : 'button';
        @endphp

        <{{ $tag }}
            class="nav-link{{ $isActive ? ' active' : '' }}{{ $isDisabled ? ' disabled' : '' }}"
            @if ($isTabList)
                role="tab"
                data-bs-toggle="tab"
                aria-selected="{{ $isActive ? 'true' : 'false' }}"
                @if (! $isActive) tabindex="-1" @endif
            @endif
            @if (! empty($item['target'])) data-bs-target="{{ $item['target'] }}" @endif
            @if ($isActive) aria-current="page" @endif
            @if ($tag === 'a')
                href="{{ $item['href'] }}"
            @else
                type="button"
                @if ($isDisabled) disabled @endif
            @endif
        >
            @if (! empty($item['icon']))
                <x-icon :name="$item['icon']" />
            @endif
            {{ $item['label'] }}
        </{{ $tag }}>
    @endforeach
</nav>
