# Alert

`<x-alert>` wraps Tabler's [Alert component](https://docs.tabler.io/ui/components/alert). It renders the `alert` markup (icon, heading, description, dismiss button) for you and picks sensible defaults, while still accepting any Tabler color or utility class.

Source: `resources/views/components/alert.blade.php`

## Basic usage

```blade
<x-alert type="success" title="Wow! Everything worked!">
    Your account has been saved.
</x-alert>
```

```blade
<x-alert type="danger">
    Sorry, there was a problem with your request.
</x-alert>
```

## Props

| Prop          | Type             | Default  | Description |
|---------------|------------------|----------|-------------|
| `type`        | string           | `info`   | Any Tabler color: `success`, `info`, `warning`, `danger`, or a base/social color like `lime`, `cyan`, `azure`, `facebook`. Maps to `alert-{type}`. |
| `title`       | string\|null     | `null`   | Renders an `alert-heading` above the slot content, which becomes the `alert-description`. Omit it for a single-line alert. |
| `icon`        | bool\|string     | `true`   | `true` auto-picks an icon for `success`/`info`/`warning`/`danger` (`check`, `info-circle`, `alert-triangle`, `alert-circle`). Pass a string to use a specific `<x-icon>` name, or `false` to hide the icon (also used for custom colors, which have no default icon). |
| `variant`     | string\|null     | `null`   | `important` for a solid background (`alert-important`), `minor` for a quieter, border-only style (`alert-minor`). Omit for the default style. |
| `dismissible` | bool             | `false`  | Adds `alert-dismissible` and a close button (`data-bs-dismiss="alert"`). Requires Tabler's JS (already loaded in `layouts.app` / `layouts.guest`). |
| `role`        | string\|null     | auto     | Defaults to `alert` for `warning`/`danger` (interrupts screen readers) and `status` for everything else (waits for a pause). Override if needed. |

Any other attribute (e.g. `class="mb-0"`) is merged onto the outer `<div>`.

## Variants

### Dismissible

```blade
<x-alert type="danger" dismissible>
    Sorry, there was a problem with your request.
</x-alert>
```

### Important (solid background)

Automatically swaps to a white close icon (`btn-close-white`) when combined with `dismissible`.

```blade
<x-alert type="warning" variant="important" dismissible>
    This action can't be undone.
</x-alert>
```

### Minor (quiet, border-only)

```blade
<x-alert type="info" variant="minor">
    Here is something that you might like to know.
</x-alert>
```

### Custom color, no icon

```blade
<x-alert type="lime" :icon="false">
    Wow! Everything worked!
</x-alert>
```

### Custom icon

```blade
<x-alert type="info" icon="bell">
    You have new notifications.
</x-alert>
```

### With action buttons

The `actions` slot renders inside a `btn-list` under the alert content.

```blade
<x-alert type="success" title="Some title" dismissible>
    Lorem ipsum dolor sit amet, consectetur adipisicing elit.

    <x-slot:actions>
        <a href="#" class="btn btn-success">Okay</a>
        <a href="#" class="btn">Cancel</a>
    </x-slot:actions>
</x-alert>
```

### With a link inside the text

Use Tabler's `alert-link` class directly in the slot content — it's plain markup, not a prop.

```blade
<x-alert type="danger">
    This is a danger alert. <a href="#" class="alert-link">Check it out</a>
</x-alert>
```

### With a list

```blade
<x-alert type="warning" title="Please fix the following">
    <ul class="alert-list">
        <li>Your password must be at least 8 characters.</li>
        <li>Your password must include a number.</li>
    </ul>
</x-alert>
```

### With an avatar

```blade
<x-alert type="info" :icon="false">
    <div class="d-flex">
        <span class="avatar me-3" style="background-image: url({{ asset('vendor/tabler/static/avatars/000m.jpg') }})"></span>
        <div>Lorena commented on your post.</div>
    </div>
</x-alert>
```

## Accessibility

- `role="alert"` interrupts screen readers immediately — used by default for `warning` and `danger`.
- `role="status"` waits for a pause before announcing — used by default for everything else (e.g. `success`, `info`).
- The dismiss button always carries `aria-label="close"`.

## See it live

The `/starter-kit` page (`resources/views/starter-kit.blade.php`) renders a few of these variants side by side.
