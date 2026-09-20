@props([
    'id',
    'title' => null,
    'size' => null,
    'fullWidth' => false,
    // Centred by default. Bootstrap's own default parks a dialog near the top
    // of the viewport, which reads as unanchored on tall screens; pass
    // :centered="false" for the old behaviour.
    'centered' => true,
    'scrollable' => false,
    'static' => false,
    'blur' => true,
    'variant' => 'default',
    'status' => null,
    'icon' => null,
    'iconColor' => null,
])

@php
    $dialogClasses = ['modal-dialog'];

    if ($size) {
        $dialogClasses[] = "modal-{$size}";
    }

    if ($fullWidth) {
        $dialogClasses[] = 'modal-full-width';
    }

    if ($centered) {
        $dialogClasses[] = 'modal-dialog-centered';
    }

    if ($scrollable) {
        $dialogClasses[] = 'modal-dialog-scrollable';
    }

    $modalClasses = ['modal', 'fade'];

    if ($blur) {
        $modalClasses[] = 'modal-blur';
    }

    $titleId = "{$id}-title";
@endphp

<div
    {{ $attributes->class($modalClasses) }}
    id="{{ $id }}"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
    @if ($title) aria-labelledby="{{ $titleId }}" @endif
    @if ($static) data-bs-backdrop="static" @endif
>
    <div class="{{ implode(' ', $dialogClasses) }}" role="document">
        <div class="modal-content">
            @if ($variant === 'confirm')
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                @if ($status)
                    <div class="modal-status bg-{{ $status }}"></div>
                @endif

                <div class="modal-body text-center py-4">
                    @if ($icon)
                        <x-icon :name="$icon" class="mb-2 icon-lg text-{{ $iconColor ?? $status ?? 'primary' }}" />
                    @endif

                    @if ($title)
                        <h3 id="{{ $titleId }}">{{ $title }}</h3>
                    @endif

                    <div class="text-secondary">
                        {{ $slot }}
                    </div>
                </div>

                @isset($footer)
                    <div class="modal-footer">
                        <div class="w-100">
                            <div class="row">
                                {{ $footer }}
                            </div>
                        </div>
                    </div>
                @endisset
            @else
                <div class="modal-header">
                    @if ($title)
                        <h5 class="modal-title" id="{{ $titleId }}">{{ $title }}</h5>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{ $slot }}
                </div>

                @isset($footer)
                    <div class="modal-footer">
                        {{ $footer }}
                    </div>
                @endisset
            @endif
        </div>
    </div>
</div>
