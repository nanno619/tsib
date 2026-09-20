# Tooltip

Wraps Tabler's [Tooltips component](https://docs.tabler.io/ui/components/tooltips) (Bootstrap 5's tooltip JS).

Source: `resources/views/components/tooltip.blade.php`

**Read this before reaching for `<x-tooltip>`.** A tooltip is 3 attributes (`data-bs-toggle="tooltip"`, `data-bs-placement`, `title`) added to an element that already exists — it isn't its own piece of markup like an alert or a badge. If you're adding a tooltip to something already built with a component in this library (`<x-button>`, `<x-badge>`, `<x-dropdown-item>`, ...), **just pass the attributes directly** — every component here merges unrecognized attributes onto its root element:

```blade
<x-button data-bs-toggle="tooltip" data-bs-placement="top" title="Save your changes">Save</x-button>
```

Reach for `<x-tooltip>` only when there's no existing element to attach to — wrapping plain text, an icon, or inline content.

## Basic usage

```blade
Status:
<x-tooltip text="Deployed 3 minutes ago" placement="top">
    <x-icon name="check" class="text-success" />
</x-tooltip>
```

## Props

| Prop        | Type   | Default | Description |
|-------------|--------|---------|--------------|
| `text`      | string | —       | Required. The tooltip content (becomes `title`). |
| `placement` | string | `top`   | `top`, `right`, `bottom`, or `left`. |
| `html`      | bool   | `false` | Allows HTML in `text` (`data-bs-html="true"`). |
| `as`        | string | `span`  | The wrapping element. |

Any other attribute is merged onto the wrapping element as-is.

## Initialization

Tooltips (and popovers) are **not** auto-initialized by Bootstrap's data-api the way dropdowns/modals/tabs are — they need an explicit JS pass. `@tabler/core/js/tabler` (imported in `resources/js/app.js`) already does this for you on page load, scanning for every `[data-bs-toggle="tooltip"]` element. If you add tooltip triggers to the DOM dynamically *after* that script has already run (e.g. via Livewire or your own JS), you'll need to initialize them yourself with `new bootstrap.Tooltip(el)`.

## See it live

The navbar's theme toggle icons (`resources/views/layouts/partials/navbar.blade.php`) use direct attribute passthrough on the `<a>`. The Starter Kit page's "Tooltip & popover" card shows both patterns.
