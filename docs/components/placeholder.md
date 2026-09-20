# Placeholder

`<x-placeholder>` + `<x-skeleton>` wrap Tabler's [Placeholder component](https://docs.tabler.io/ui/components/placeholder) — skeleton-loading lines.

Source: `resources/views/components/placeholder.blade.php`, `resources/views/components/skeleton.blade.php`

`<x-placeholder>` renders one line; `<x-skeleton>` is the animated parent that groups several lines together — the `placeholder-glow`/`placeholder-wave` animation class lives on the parent, not each line, so a skeleton block is always both pieces together.

## Basic usage

```blade
<x-skeleton>
    <x-placeholder :width="9" class="mb-2" />
    <x-placeholder :width="11" />
</x-skeleton>
```

## `<x-skeleton>` props

| Prop        | Type   | Default | Description |
|-------------|--------|---------|--------------|
| `animation` | string | `glow`  | `glow` or `wave`. |
| `as`        | string | `div`   | The wrapping element (use `p` for a text-only skeleton, per Tabler's own examples). |

## `<x-placeholder>` props

| Prop    | Type         | Default | Description |
|---------|--------------|---------|--------------|
| `width` | int          | `12`    | Grid width, 1–12 (`col-{width}`) — how "full" the line looks. |
| `size`  | string\|null | `null`  | `xs`, `sm`, or `lg` (`placeholder-{size}`). Omit for the default (heading-sized) line — mix a full-size line with `xs` lines for a heading + body skeleton. |
| `color` | string\|null | `null`  | Any Tabler color, replacing the default `currentColor` fill (`bg-{color}`). |

Any other attribute (e.g. `class="mb-2"`) is merged onto the line.

## Variants

### Heading + body text

```blade
<x-skeleton>
    <x-placeholder :width="9" class="mb-3" />
    <x-placeholder :width="10" size="xs" />
    <x-placeholder :width="11" size="xs" />
</x-skeleton>
```

### Avatar + lines

An avatar placeholder is just `<x-avatar>` with the `placeholder` class added — no separate component needed:

```blade
<x-skeleton as="div" class="d-flex align-items-center">
    <x-avatar size="lg" class="placeholder me-3" />
    <div class="flex-fill">
        <x-placeholder :width="9" class="mb-2" />
        <x-placeholder :width="7" size="xs" />
    </div>
</x-skeleton>
```

### Colored

```blade
<x-placeholder :width="12" color="primary" />
```

## What this doesn't wrap

- **Placeholder image** (`ratio` + `placeholder-image`) — write it directly per [Tabler's docs](https://docs.tabler.io/ui/components/placeholder#placeholder-image); too tied to the specific `ratio-*` class you need.
- **A full skeleton card** (image + lines + a disabled placeholder button) — compose it from `<x-skeleton>`, `<x-placeholder>`, and a `<x-button disabled class="placeholder">` inside a `<x-card>`.

## See it live

The Starter Kit page's "Placeholder" card (avatar + heading/body skeleton).
