@props([
    'responsive' => true,
    'vcenter' => true,
    'nowrap' => false,
    'card' => false,
])

@php
    $classes = ['table'];

    if ($vcenter) {
        $classes[] = 'table-vcenter';
    }

    if ($nowrap) {
        $classes[] = 'table-nowrap';
    }

    if ($card) {
        $classes[] = 'card-table';
    }
@endphp

@if ($responsive)
    <div class="table-responsive">
        <table {{ $attributes->class($classes) }}>
            {{ $slot }}
        </table>
    </div>
@else
    <table {{ $attributes->class($classes) }}>
        {{ $slot }}
    </table>
@endif
