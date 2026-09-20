# Status

`<x-status>` wraps Tabler's [Statuses component](https://docs.tabler.io/ui/components/statuses) — labeled status pills, standalone dots, and pulsing indicator rings.

Source: `resources/views/components/status.blade.php`

## Basic usage

```blade
<x-status color="green">Active</x-status>
```

## Props

| Prop        | Type         | Default     | Description |
|-------------|--------------|-------------|--------------|
| `color`     | string       | `secondary` | Any Tabler color. |
| `dot`       | bool         | `true`      | Shows a leading dot next to the label. Set `false` for a plain colored pill with no dot. |
| `animated`  | bool         | `false`     | Pulses the dot (or the indicator rings, if `indicator` is set). |
| `lite`      | bool         | `false`     | Subtler pill style (`status-lite`). Ignored for a standalone dot or `indicator`. |
| `indicator` | bool         | `false`     | Renders the concentric-ring `status-indicator` instead of a pill or dot — ignores the slot entirely. |
| `label`     | string\|null | `null`      | Accessible name. For a **standalone dot** (see below), renders as `visually-hidden` text inside it. For `indicator`, sets `aria-label` directly (it has no text content to attach a hidden label to). |

The slot is the visible label. **Leave it empty for a standalone dot** — see below.

## Standalone dot vs. labeled pill

This is the one thing to get right: whether you pass slot content changes which Tabler pattern gets rendered.

- **Slot has content** → a labeled pill: `<span class="status status-{color}"><span class="status-dot"></span>Active</span>`.
- **Slot is empty** (and `dot` is `true`, the default) → a standalone dot with the color *on the dot itself*: `<span class="status-dot status-{color}">`. Always pass `label` here — there's no visible text, and Tabler's own guidance is that a color alone means nothing to a screen reader.

```blade
<x-status color="green">Active</x-status>        {{-- labeled pill --}}
<x-status color="green" dot label="Online" />    {{-- standalone dot, accessible name via visually-hidden text --}}
```

## Variants

### No dot

```blade
<x-status color="secondary" :dot="false">Draft</x-status>
```

### Animated (live status)

```blade
<x-status color="red" animated>Live</x-status>
```

### Lite

```blade
<x-status color="secondary" lite>Draft</x-status>
```

### Indicator (pulsing rings)

```blade
<x-status color="azure" indicator animated label="Syncing" />
```

## See it live

The Starter Kit page's "Spinners & statuses" card.
