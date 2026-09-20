@props([
    'id' => 'calendar',
    'view' => 'dayGridMonth',
    'height' => 'auto',
    'selectable' => false,
    'events' => [],
])

<div
    {{ $attributes }}
    id="{{ $id }}"
    data-calendar
    data-calendar-view="{{ $view }}"
    data-calendar-height="{{ $height }}"
    @if ($selectable) data-calendar-selectable @endif
    data-calendar-events="{{ json_encode($events) }}"
></div>
