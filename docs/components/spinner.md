# Spinner

`<x-spinner>` wraps Tabler's [Spinners component](https://docs.tabler.io/ui/components/spinners).

Source: `resources/views/components/spinner.blade.php`

For a loading *button*, prefer `<x-button loading>` ([button.md](./button.md#loading)) — it handles `btn-loading`, `aria-busy`, and disabling the button for you. Reach for `<x-spinner>` directly when you need a bare loading indicator (inline text, a custom button layout, a page section).

## Basic usage

```blade
<x-spinner />
```

## Props

| Prop    | Type         | Default        | Description |
|---------|--------------|----------------|--------------|
| `grow`  | bool         | `false`        | Pulsing-dot style (`spinner-grow`) instead of the default ring (`spinner-border`). |
| `size`  | string\|null | `null`         | `sm` for a smaller spinner. |
| `color` | string\|null | `null`         | Any Tabler color, applied as `text-{color}`. |
| `label` | string\|null | `Loading...`   | `visually-hidden` text for screen readers. Pass `""` to omit it when adjacent visible text already says the same thing (e.g. a "Loading…" label next to the spinner). |

Any other attribute (e.g. `class="me-2"`) is merged onto the outer element.

## Variants

### Color and size

```blade
<x-spinner size="sm" color="primary" />
<x-spinner grow color="green" />
```

### Inside a button

```blade
<button type="button" class="btn btn-primary" disabled>
    <x-spinner size="sm" class="me-2" label="" />
    Loading&hellip;
</button>
```

(Or just use `<x-button loading>Loading&hellip;</x-button>` — see [button.md](./button.md).)

### Animated dots (text, not a spinner)

Not a variant of this component — a separate utility class for a "…" animation after text:

```blade
<h1>Loading<span class="animated-dots"></span></h1>
```

## See it live

The Starter Kit page's "Spinners & statuses" card.
