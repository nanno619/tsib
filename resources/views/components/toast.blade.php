@props([
    'title' => null,
    'time' => null,
    'avatar' => null,
    'autohide' => false,
    'show' => true,
])

<div
    {{ $attributes->class(['toast', $show ? 'show' : '']) }}
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    data-bs-toggle="toast"
    data-bs-autohide="{{ $autohide ? 'true' : 'false' }}"
>
    @if ($title || $time || $avatar)
        <div class="toast-header">
            @if ($avatar)
                <x-avatar size="xs" :src="$avatar" class="me-2" />
            @endif

            @if ($title)
                <strong class="me-auto">{{ $title }}</strong>
            @endif

            @if ($time)
                <small>{{ $time }}</small>
            @endif

            <button type="button" class="ms-2 btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    @endif

    <div class="toast-body">
        {{ $slot }}
    </div>
</div>
