@props([
    'as' => 'div',
    'href' => null,
    'triggerClass' => 'btn',
    'caret' => true,
    'label' => null,
    'direction' => 'down',
    'align' => null,
    'arrow' => false,
    'dark' => false,
    'card' => false,
    'autoClose' => true,
])

@php
    $wrapperClass = match ($direction) {
        'up' => 'dropup',
        'end' => 'dropend',
        'start' => 'dropstart',
        default => 'dropdown',
    };

    $triggerClasses = trim($triggerClass.($caret ? ' dropdown-toggle' : ''));

    $menuClasses = ['dropdown-menu'];

    if ($align === 'end') {
        $menuClasses[] = 'dropdown-menu-end';
    }

    if ($arrow) {
        $menuClasses[] = 'dropdown-menu-arrow';
    }

    if ($dark) {
        $menuClasses[] = 'bg-dark text-white';
    }

    if ($card) {
        $menuClasses[] = 'dropdown-menu-card';
    }

    $autoCloseValue = is_bool($autoClose) ? ($autoClose ? 'true' : 'false') : $autoClose;

    $tag = $href ? 'a' : 'button';
@endphp

<{{ $as }} {{ $attributes->class([$wrapperClass]) }}>
    <{{ $tag }}
        class="{{ $triggerClasses }}"
        data-bs-toggle="dropdown"
        data-bs-auto-close="{{ $autoCloseValue }}"
        @if ($tag === 'a') href="{{ $href }}" @else type="button" @endif
        @if ($label) aria-label="{{ $label }}" @endif
    >
        {{ $trigger }}
    </{{ $tag }}>

    <div class="{{ implode(' ', $menuClasses) }}">
        {{ $slot }}
    </div>
</{{ $as }}>
