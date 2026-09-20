# Segmented control

`<x-segmented-control>` wraps Tabler's [Segmented control component](https://docs.tabler.io/ui/components/segmented-control) — a row of mutually-exclusive options. Give an item a `target` and it works as Bootstrap tab buttons driving tab panes; give it an `href` and the same row becomes plain navigation. See [Accessibility](#accessibility) — the two modes emit different markup on purpose.

Source: `resources/views/components/segmented-control.blade.php`

## Basic usage

```blade
<x-segmented-control
    label="Report period"
    selected="week"
    :items="[
        ['label' => 'Day', 'value' => 'day', 'href' => '?period=day'],
        ['label' => 'Week', 'value' => 'week', 'href' => '?period=week'],
        ['label' => 'Month', 'value' => 'month', 'href' => '?period=month'],
    ]"
/>
```

## Props

| Prop       | Type          | Default | Description |
|------------|---------------|---------|--------------|
| `items`    | array         | `[]`    | See [item shape](#item-shape) below. |
| `selected` | mixed\|null   | `null`  | Compared against each item's `value` (loosely, `==`) to mark it `active`. Ignored for an item that sets `active` itself. |
| `size`     | string\|null  | `null`  | `sm` or `lg` (`nav-sm` / `nav-lg`). Omit for the default size. |
| `vertical` | bool          | `false` | Stacks the segments vertically (`nav-segmented-vertical`). |
| `block`    | bool          | `false` | Full width / justified (`w-100`). |
| `label`    | string\|null  | `null`  | `aria-label` on the `<nav>` — set this when there's no visible heading identifying what the control is choosing between. |

Any other attribute (e.g. `class="mb-3"`) is merged onto the outer `<nav>`.

## Item shape

Each entry in `items` is an array:

| Key        | Description |
|------------|--------------|
| `label`    | Required. The visible text. |
| `value`    | Compared against the `selected` prop. Falls back to the item's array index if omitted. |
| `href`     | Renders the segment as a link. Omit for a plain `<button type="button">` (e.g. a JS-driven toggle). |
| `target`   | A `data-bs-target` (e.g. `#pane-id`) when the control is switching real Bootstrap tab panes rather than navigating. |
| `icon`     | An `<x-icon>` name, shown before the label. |
| `active`   | Force this item active, bypassing the `selected` comparison — useful for `request()->routeIs(...)` checks. |
| `disabled` | Disables the segment. A disabled item with `href` still renders as a `<button disabled>`, never a clickable link. |

## Variants

### Sizes

```blade
<x-segmented-control size="sm" :items="$items" selected="list" />
<x-segmented-control :items="$items" selected="list" />
<x-segmented-control size="lg" :items="$items" selected="list" />
```

### Full width

```blade
<x-segmented-control block :items="$items" selected="daily" />
```

### With icons

```blade
<x-segmented-control
    label="Section"
    selected="dashboard"
    :items="[
        ['label' => 'Dashboard', 'value' => 'dashboard', 'href' => route('dashboard'), 'icon' => 'home'],
        ['label' => 'Starter Kit', 'value' => 'kit', 'href' => route('starter-kit'), 'icon' => 'rocket'],
    ]"
/>
```

### Vertical

```blade
<x-segmented-control vertical :items="$items" selected="list" />
```

### Switching real tab panes

Pair `target` on each item with actual `.tab-pane` elements, the same way Bootstrap's `nav-tabs` works:

```blade
<x-segmented-control :items="[
    ['label' => 'List', 'value' => 'list', 'target' => '#pane-list', 'active' => true],
    ['label' => 'Kanban', 'value' => 'kanban', 'target' => '#pane-kanban'],
]" />

<div class="tab-content">
    <div class="tab-pane active" id="pane-list">...</div>
    <div class="tab-pane" id="pane-kanban">...</div>
</div>
```

### Active via route check instead of `selected`

```blade
<x-segmented-control :items="[
    ['label' => 'Profile', 'href' => route('settings.profile'), 'active' => request()->routeIs('settings.profile')],
    ['label' => 'Password', 'href' => route('settings.password'), 'active' => request()->routeIs('settings.password')],
]" />
```

## Accessibility

There are two modes, and the difference is not cosmetic:

- **Tab mode** — any item carries `target`. The `<nav>` gets `role="tablist"`, each segment gets `role="tab"` and `data-bs-toggle="tab"`, and inactive segments get `tabindex="-1"` (Bootstrap's roving tab index).
- **Navigation mode** — no item carries `target`. The segments are ordinary links, with none of the above. Only `aria-current="page"` marks the active one.

This split exists because Bootstrap's tab data-API calls `event.preventDefault()` on `<a>` elements. Emitting `data-bs-toggle="tab"` on a plain navigation link therefore **stopped it navigating at all**, and the roving `tabindex="-1"` dropped the inactive links out of the tab order. If you add a mode or a new item shape, keep the toggle off anything that isn't driving a pane.

- Set `label` whenever the control isn't already described by a visible heading right next to it.

## See it live

The Starter Kit page's "Segmented control" card shows the default size (with a disabled segment) and a small icon-based variant.
