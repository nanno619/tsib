# Badge

`<x-badge>` wraps Tabler's [Badge component](https://docs.tabler.io/ui/components/badge). It renders the `badge` markup for you — solid, light or outline colors, pills, dots, notifications and icon-only badges — while accepting any Tabler color.

Source: `resources/views/components/badge.blade.php`

## Basic usage

```blade
<x-badge>First</x-badge>
<x-badge color="azure">Azure</x-badge>
<x-badge color="green" variant="light">Green</x-badge>
```

## Props

| Prop           | Type             | Default   | Description |
|----------------|------------------|-----------|-------------|
| `color`        | string\|null     | `null`    | Any Tabler color: base colors (`blue`, `azure`, `indigo`, `lime`, ...) or semantic aliases (`primary`, `success`, `danger`, ...). `null` renders a plain gray badge. |
| `variant`      | string           | `solid`   | `solid` → `bg-{color} text-{color}-fg`. `light` → `bg-{color}-lt`. `outline` → `badge-outline text-{color}`. Ignored when `color` is `null`. |
| `pill`         | bool             | `false`   | Fully rounded ends (`badge-pill`). |
| `size`         | string\|null     | `null`    | `sm` or `lg` (`badge-sm` / `badge-lg`). Omit for the default size. |
| `dot`          | bool             | `false`   | Renders a small dot indicator (`badge-dot`) instead of text/icon content. Pair with `label` for accessibility. |
| `href`         | string\|null     | `null`    | Renders an `<a>` instead of a `<span>` — makes the badge clickable. |
| `icon`         | string\|null     | `null`    | An `<x-icon>` name to render before the slot content. If the slot is empty, the badge becomes icon-only (`badge-icononly`). |
| `notification` | bool             | `false`   | Adds `badge-notification`, for pinning a badge to the corner of a button/icon. |
| `blink`        | bool             | `false`   | Adds `badge-blink`, a CSS pulse animation for live status. |
| `label`        | string\|null     | `null`    | Only used with `dot` — renders a `visually-hidden` span so screen readers announce the state. |

Any other attribute (e.g. `class="me-1"`) is merged onto the outer element.

## Variants

### Solid, light, outline

```blade
<x-badge color="azure">Azure</x-badge>
<x-badge color="azure" variant="light">Azure light</x-badge>
<x-badge color="azure" variant="outline">Azure outline</x-badge>
```

### Pill

```blade
<x-badge color="primary" pill>New</x-badge>
```

### Sizes

```blade
<x-badge color="primary" size="sm">New</x-badge>
<x-badge color="primary">New</x-badge>
<x-badge color="primary" size="lg">New</x-badge>
```

### Dot indicator

Always pass `label` so the state isn't color-only for screen readers.

```blade
<x-badge color="green" dot label="Online" />
<x-badge color="red" dot label="Offline" />
```

### As a link

```blade
<x-badge color="blue" variant="light" href="{{ route('settings.profile') }}">Blue</x-badge>
```

### Icon-only

Omit the slot content — the component adds `badge-icononly` for you.

```blade
<x-badge color="primary" icon="bell" />
```

### Icon with text

```blade
<x-badge color="primary" icon="bell">Notifications</x-badge>
```

### Notification badge (pinned to a button)

```blade
<button type="button" class="btn">
    Inbox
    <x-badge color="red" notification>4</x-badge>
</button>
```

### Blinking live-status badge

```blade
<button type="button" class="btn">
    Profile
    <x-badge color="red" notification blink :dot="true" />
</button>
```

### In a heading

Badges are plain inline elements, so they scale automatically inside headings — no special prop needed.

```blade
<h1>Example heading <x-badge>New</x-badge></h1>
```

### Grouped list

Wrap multiple badges in `badge-list` for consistent spacing/wrapping.

```blade
<div class="badge-list">
    <x-badge>First</x-badge>
    <x-badge>Second</x-badge>
</div>
```

## Accessibility

Tabler's own guidance: *"A badge that only shows a color means nothing to a screen reader. Put the state in text next to it, or add an `aria-label`."*

- For text badges, the slot content already satisfies this.
- For `dot` badges (no visible text), always pass `label` — it renders a `visually-hidden` span so the state is announced.
- Alternatively pass your own `aria-label="..."` attribute, which is merged onto the element like any other attribute.

## See it live

The `/starter-kit` page (`resources/views/starter-kit.blade.php`) renders light, solid, outline, pill and dot variants together.
