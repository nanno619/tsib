# Card

`<x-card>` wraps Tabler's [Card component](https://docs.tabler.io/ui/components/card). It covers the common structure — header with a real heading + actions, body, footer, status strip, size/variant modifiers, and clickable cards — used throughout the starter kit.

Source: `resources/views/components/card.blade.php`

Tabler's card has a lot of specialized layouts (image top, tabs, tables, decorative stamps, scrollable body). Rather than growing this component to cover every one, it handles the common 80% via props and lets you write anything else directly in the default slot — see [Variants this component doesn't wrap](#variants-this-component-doesnt-wrap) below.

## Basic usage

```blade
<x-card title="Team members">
    <p class="text-secondary mb-0">Invite a teammate to this project.</p>
</x-card>
```

## Props

| Prop             | Type         | Default | Description |
|------------------|--------------|---------|--------------|
| `title`          | string\|null | `null`  | Renders a `card-header` with a real heading element (see `headingLevel`) — not just a styled `<div>`, per Tabler's accessibility guidance. |
| `headingLevel`   | string       | `h3`    | The heading tag for `title` (`h2`–`h4`). Match it to where the card sits in your page outline. |
| `subtitle`       | string\|null | `null`  | Small text (`card-subtitle`) rendered at the top of the body. |
| `status`         | string\|null | `null`  | Any Tabler color. Renders a `card-status-{position}` strip. |
| `statusPosition` | string       | `top`   | `top` or `start` (the side edge). Only used with `status`. |
| `size`           | string\|null | `null`  | `sm`, `md`, or `lg` (`card-{size}`) — padding variants. Omit for the default. |
| `variant`        | string\|null | `null`  | `borderless`, `dashed`, or `transparent` (`card-{variant}`). |
| `stacked`        | bool         | `false` | `card-stacked`, a subtle layered look. |
| `href`           | string\|null | `null`  | Renders the whole card as a single `<a>` (`card-link`) instead of a `<div>` — see [Accessibility](#accessibility). |
| `lift`           | bool         | `false` | Adds `card-link-pop`, a hover-lift effect. Only meaningful with `href`. |

## Slots

| Slot      | Description |
|-----------|--------------|
| (default) | Main content, wrapped in `card-body`. |
| `actions` | Rendered in the header (`card-actions`), next to the title. Only shows a header if `title` and/or `actions` is set. |
| `footer`  | Rendered in a `card-footer` below the body. |

Any other attribute (e.g. `class="mb-0"`) is merged onto the outer element.

## Variants

### Header with actions

```blade
<x-card title="Team members">
    <x-slot:actions>
        <x-button color="primary" icon="plus">Invite</x-button>
    </x-slot:actions>

    <p class="mb-0">Invite a teammate to this project.</p>
</x-card>
```

### Footer

```blade
<x-card title="Invoice #4321">
    <p class="mb-0">Paid on 12 May.</p>

    <x-slot:footer>
        <a href="#" class="link-primary">Download PDF</a>
    </x-slot:footer>
</x-card>
```

### Status strip

```blade
<x-card title="Status strip" status="primary">...</x-card>
<x-card title="Side status" status="orange" status-position="start">...</x-card>
```

### Subtitle

```blade
<x-card subtitle="Reports" title="Monthly summary">
    <p class="text-secondary mb-0">Every report from the last 30 days.</p>
</x-card>
```

### Sizes

```blade
<x-card size="sm">Small padding</x-card>
<x-card>Default padding</x-card>
<x-card size="lg">Large padding</x-card>
```

### Borderless / dashed / transparent

```blade
<x-card variant="borderless">...</x-card>
<x-card variant="dashed">...</x-card>
<x-card variant="transparent">...</x-card>
```

### Clickable card

Wrap the whole card in one link rather than only linking the title, so it's a single tab stop — that's what `href` does for you.

```blade
<x-card title="Clickable card" href="{{ route('starter-kit') }}" lift>
    <p class="text-secondary mb-0">The whole card is one link.</p>
</x-card>
```

## Variants this component doesn't wrap

For these, write the markup directly (copy from [Tabler's docs](https://docs.tabler.io/ui/components/card)) — a prop wouldn't pull its weight for how rarely they're combined with the rest:

- **Image top** (`card-img-top`) — a background-image div before `card-body`.
- **Tabs in the header** (`card-tabs`, `nav-tabs`, `tab-pane`) — several `.card`s driven by one tab nav.
- **Table inside a card** (`card-table`) — `<table class="table card-table">` as a direct child, replacing `card-body`.
- **Decorative stamp** (`card-stamp`) — a faded icon overlay; put it inside the card before `card-body` (it's `position: absolute`).
- **Scrollable body** (`card-body-scrollable`) — add that class + an inline `height` directly where you'd otherwise rely on the default slot; not exposed as a prop since the height is always page-specific.

## Accessibility

- `title` always renders as a real heading element (`headingLevel`, default `h3`), never just a styled `<div>` — screen readers rely on the heading, not the `card-title` class.
- For a clickable card, use `href` (not a link only on the title) so the whole card is one focusable, tabbable element — that's what `card-link` is for.
- A `status` strip is decorative color only; it doesn't replace stating the same thing in text (e.g. pair it with a `<x-badge>` or plain text elsewhere in the card).

## See it live

The Dashboard page (stat cards, a plain welcome card, a titled card) and the Starter Kit page (every other card on the page, including a dedicated row for status/footer/clickable variants) all use `<x-card>`.
