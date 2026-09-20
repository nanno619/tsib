# Popover

`<x-popover>` wraps Tabler's [Popovers component](https://docs.tabler.io/ui/components/popover) — a click/hover overlay with richer content than a tooltip.

Source: `resources/views/components/popover.blade.php`

Unlike [`<x-tooltip>`](./tooltip.md), a popover more often *is* its own small trigger (a `?` help icon, a button whose only job is opening the popover), so this component defaults to rendering a `<button>` rather than assuming you already have one. If you're adding a popover to an existing component instead, the same rule applies as tooltips — pass the `data-bs-toggle="popover"` attributes directly rather than wrapping it.

## Basic usage

```blade
<x-popover content="And here's some amazing content. It's very engaging. Right?" title="Popover title">
    Click to toggle popover
</x-popover>
```

## Props

| Prop        | Type         | Default  | Description |
|-------------|--------------|----------|--------------|
| `content`   | string       | —        | Required. The popover body (`data-bs-content`). |
| `title`     | string\|null | `null`   | Optional popover heading. |
| `placement` | string       | `top`    | `top`, `right`, `bottom`, or `left`. |
| `trigger`   | string\|null | `null`   | `hover`, `click` (Bootstrap's default when omitted), `focus`, or `manual`. |
| `html`      | bool         | `false`  | Allows HTML in `content`/`title` (`data-bs-html="true"`). |
| `container` | string\|null | `null`   | `data-bs-container` — e.g. `"body"`, to escape a `card` or `modal`'s overflow clipping. |
| `as`        | string       | `button` | The rendered element. |

Any other attribute (e.g. `class="form-help"`) is merged onto the element as-is — this component doesn't apply a default class.

## Variants

### The `?` help icon pattern

```blade
<label class="form-label mb-0">
    ZIP Code
    <x-popover as="span" class="form-help" content="<p>ZIP Code must be US or CDN format.</p>" html>?</x-popover>
</label>
```

### On hover instead of click

```blade
<x-popover trigger="hover" content="..." title="Popover title">Hover to toggle popover</x-popover>
```

## Initialization

Same as tooltips — see [tooltip.md](./tooltip.md#initialization). Popovers are auto-initialized on page load by `@tabler/core/js/tabler`, not by Bootstrap's data-api.

## See it live

The Starter Kit page's "Tooltip & popover" card shows both the default button and the `form-help` pattern.
