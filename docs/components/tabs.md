# Tabs

`<x-tabs>` wraps Tabler's [Tabs component](https://docs.tabler.io/ui/components/tabs) — `nav-tabs` triggers driving `tab-pane` panels. Same array-driven shape as [`<x-segmented-control>`](./segmented-control.md), rendering `nav-tabs` instead of `nav-segmented`.

Source: `resources/views/components/tabs.blade.php`

## Basic usage

```blade
<x-tabs selected="home" :items="[
    ['label' => 'Home', 'value' => 'home', 'target' => '#tab-home'],
    ['label' => 'Settings', 'value' => 'settings', 'target' => '#tab-settings'],
]" />

<div class="tab-content">
    <div class="tab-pane active show" id="tab-home">Home content.</div>
    <div class="tab-pane" id="tab-settings">Settings content.</div>
</div>
```

The `target` must match a `.tab-pane` `id` in your own markup — panel content stays plain HTML, same reasoning as [`<x-table>`](./table.md).

## Props

| Prop         | Type         | Default | Description |
|--------------|--------------|---------|--------------|
| `items`      | array        | `[]`    | See [item shape](#item-shape). |
| `selected`   | mixed\|null  | `null`  | Compared against each item's `value` to mark it active. Ignored for an item that sets `active` itself. |
| `cardHeader` | bool         | `false` | `card-header-tabs`, for tabs sitting inside a `card-header`. |
| `fill`       | bool         | `false` | Tabs split the full width (`nav-fill`). |
| `label`      | string       | `Tabs`  | `aria-label` on the `<ul>`. |

## Item shape

Same as [`<x-segmented-control>`](./segmented-control.md#item-shape): `label`, `value`, `href` (link instead of a tab-panel button) or `target` (a `data-bs-target` panel id), `icon`, `active`, `disabled`. Add `'end' => true` to push a single item to the right with `ms-auto` (e.g. a trailing settings icon tab).

## Variants

### Inside a card header

```blade
<div class="card">
    <div class="card-header">
        <x-tabs card-header selected="home" :items="[...]" />
    </div>
    <div class="card-body">
        <div class="tab-content">...</div>
    </div>
</div>
```

### Icon-only tabs

```blade
<x-tabs :items="[
    ['value' => 'home', 'target' => '#tab-home', 'icon' => 'home', 'active' => true],
    ['value' => 'settings', 'target' => '#tab-settings', 'icon' => 'settings'],
]" />
```

Omitting `label` on an item renders an icon with no text — pair with `label` (the component prop, for `aria-label` on the whole tablist) so the control is still identifiable.

### Full width

```blade
<x-tabs fill :items="$items" selected="home" />
```

## What this doesn't wrap

- **A tab that's a dropdown** (`nav-item dropdown` in place of a plain tab trigger) — doesn't fit the array item shape. Add it as a hand-written `<li>` alongside the rendered `<x-tabs>` output, or write the whole `<ul class="nav nav-tabs">` by hand for that one case.

## See it live

The Starter Kit page's "Tabs" card.
