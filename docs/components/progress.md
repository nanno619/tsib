# Progress

`<x-progress>` wraps Tabler's [Progress bars component](https://docs.tabler.io/ui/components/progress).

Source: `resources/views/components/progress.blade.php`

## Basic usage

```blade
<x-progress value="38" />
```

## Props

| Prop            | Type         | Default | Description |
|-----------------|--------------|---------|--------------|
| `value`         | int\|string  | `0`     | The percentage filled (0–100), used as both the bar's inline `width` and `aria-valuenow`. |
| `max`           | int\|string  | `100`   | `aria-valuemax`. |
| `color`         | string\|null | `null`  | Any Tabler color, applied as `bg-{color}` on the bar. |
| `size`          | string\|null | `null`  | `sm` for a thinner bar (`progress-sm`). Omit for the default size. |
| `striped`       | bool         | `false` | Diagonal stripes (`progress-bar-striped`). |
| `animated`      | bool         | `false` | Animates the stripes (`progress-bar-animated`). Only visible combined with `striped`. |
| `indeterminate` | bool         | `false` | Unknown-progress animation (`progress-bar-indeterminate`) — omits `value`/`width` and the `role="progressbar"` attributes entirely, since there's no known value to report. |
| `label`         | string\|null | `null`  | Sets `aria-label` on the bar and a `visually-hidden` text label inside it (e.g. `"57% complete"`). Ignored when `indeterminate`. |

Any other attribute (e.g. `class="mb-3"`) is merged onto the outer `.progress` wrapper.

## Variants

### Color

```blade
<x-progress value="24" color="red" />
<x-progress value="45" color="green" />
```

### Small size, with an accessible label

```blade
<x-progress value="57" size="sm" color="green" label="57% complete" />
```

### Striped / animated

```blade
<x-progress value="60" striped />
<x-progress value="60" striped animated />
```

### Indeterminate

```blade
<x-progress indeterminate size="sm" />
```

## What this doesn't wrap

- **Stacked segments** (`progress-stacked`, multiple color-coded segments in one track) — the width lives on the *outer* `.progress` of each segment rather than the bar, which doesn't fit this component's single-bar shape. Compose it directly from [Tabler's docs](https://docs.tabler.io/ui/components/progress#multiple-progress-bars).
- **`progressbg`** (a track with an overlaid label/value, used in stat lists) — a distinct, narrower pattern; write it directly.

## See it live

The Starter Kit page's "Progress" card shows the default, small+labeled, striped+animated, and indeterminate variants.
