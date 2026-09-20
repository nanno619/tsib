# Ribbon

`<x-ribbon>` wraps Tabler's [Ribbons component](https://docs.tabler.io/ui/components/ribbons) — a decorative corner label, usually inside a `card`.

Source: `resources/views/components/ribbon.blade.php`

## Basic usage

```blade
<div class="card">
    <div class="card-body">...</div>
    <x-ribbon icon="star" />
</div>
```

## Props

| Prop         | Type         | Default | Description |
|--------------|--------------|---------|--------------|
| `vertical`   | string\|null | `null`  | `top` or `bottom`. Omit for the default (top). |
| `horizontal` | string\|null | `null`  | `start` or `end`. Omit for the default (end). |
| `color`      | string\|null | `null`  | Any Tabler color (`bg-{color}`). |
| `bookmark`   | bool         | `false` | Notched bookmark style (`ribbon-bookmark`). |
| `icon`       | string\|null | `null`  | An `<x-icon>` name. Takes priority over the slot if both are given. |

The slot is the ribbon content when `icon` isn't used (e.g. short text like `"NEW"`).

## Variants

### Position

```blade
<x-ribbon vertical="top" horizontal="start" icon="star" />   {{-- top-left --}}
<x-ribbon vertical="bottom" horizontal="end" icon="star" />  {{-- bottom-right --}}
```

### Text + color

```blade
<x-ribbon color="green">NEW</x-ribbon>
```

### Bookmark style

```blade
<x-ribbon bookmark color="orange" icon="star" />
```

## See it live

The Starter Kit page's "Ribbon" card.
