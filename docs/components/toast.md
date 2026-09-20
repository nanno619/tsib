# Toast

`<x-toast>` wraps Tabler's [Toasts component](https://docs.tabler.io/ui/components/toasts) — a lightweight, dismissible notification.

Source: `resources/views/components/toast.blade.php`

## Basic usage

```blade
<x-toast title="Jane Doe" time="11 mins ago">
    Hello, world! This is a toast message.
</x-toast>
```

## Props

| Prop       | Type          | Default | Description |
|------------|---------------|---------|--------------|
| `title`    | string\|null  | `null`  | Bold text in the `toast-header`. |
| `time`     | string\|null  | `null`  | Small trailing timestamp in the header (e.g. `"11 mins ago"`). |
| `avatar`   | string\|null  | `null`  | An image URL, rendered as an `<x-avatar size="xs">` in the header. |
| `autohide` | bool          | `false` | `data-bs-autohide` — whether Bootstrap's JS dismisses it automatically after a delay. |
| `show`     | bool          | `true`  | Whether the toast starts visible (`show` class). Set `false` if you're triggering it entirely via JS after render. |

If none of `title`, `time`, or `avatar` are set, the `toast-header` is omitted entirely and the toast is just a `toast-body`.

Any other attribute (e.g. `class="mb-2"`) is merged onto the outer `.toast`.

## Stacking multiple toasts

Wrap them in `toast-container` — plain markup, not a dedicated component (it's a single class, and positioning utilities like `position-fixed top-0 end-0 p-3` vary per use case):

```blade
<div class="toast-container position-fixed top-0 end-0 p-3">
    <x-toast title="Jane Doe" time="11 mins ago">First message.</x-toast>
    <x-toast title="John Smith" time="7 mins ago">Second message.</x-toast>
</div>
```

## Dismissing / showing via JS

The close button already carries `data-bs-dismiss="toast"`. To show a toast that starts hidden (`show="false"`), trigger Bootstrap's Toast JS on it from your own script — this component only renders the markup, it doesn't manage a toast queue or auto-generate ones from server-side flash messages.

## See it live

The Starter Kit page's "Toast" card.
