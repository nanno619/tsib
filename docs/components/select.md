# Select

`<x-select>` wraps Tabler's [Form elements component](https://docs.tabler.io/ui/components/form-elements) `form-select`. Same label/error/help conventions as [`<x-input>`](./input.md).

Source: `resources/views/components/select.blade.php`

## Basic usage

```blade
<x-select name="role" label="Role" :options="['admin' => 'Admin', 'editor' => 'Editor', 'viewer' => 'Viewer']" />
```

## Props

| Prop          | Type          | Default   | Description |
|---------------|---------------|-----------|--------------|
| `name`        | string        | —         | Required. |
| `label`       | string\|null  | `null`    | |
| `options`     | array         | `[]`      | `value => label` pairs, or `value => ['label' => ..., 'html' => ...]` for [rich options](#rich-options-avatars-flags-badges) (only rendered when `advanced`). |
| `value`       | mixed\|null   | `null`    | Fallback selected value(s), fed through `old($name, $value)`. |
| `placeholder` | string\|null  | `null`    | Plain select: an initial disabled option (ignored when `multiple`). Advanced select: ghost placeholder text in the search box (via `data-placeholder`) instead of an extra option row. |
| `help`        | string\|null  | `null`    | Same as `<x-input>` — only shown without a validation error. |
| `required`    | bool          | `false`   | |
| `disabled`    | bool          | `false`   | |
| `multiple`    | bool          | `false`   | Renders a multi-select; `name` is submitted as `name[]`, and `value`/`old()` are read as an array. Combined with `advanced`, this is Tom Select's tag-input mode — see below. |
| `size`        | string\|null  | `null`    | `sm` or `lg` (`form-select-{size}`). |
| `advanced`    | bool          | `false`   | Progressively enhances the same `<select>` with [Tom Select](https://tom-select.js.org) — search, tag input, and rich option rendering. This is what Tabler's docs call "Advanced select." See [Architecture](#architecture). |
| `errorBag`    | string        | `default` | Same as `<x-input>`. |

Any other attribute is merged onto the `<select>`.

## Variants

### Multiple (plain)

```blade
<x-select name="tags" label="Tags" multiple :options="['php' => 'PHP', 'js' => 'JavaScript']" :value="['php']" />
```

### With a placeholder option

```blade
<x-select name="country" label="Country" placeholder="Choose a country…" :options="$countries" />
```

### Advanced (searchable)

Same markup, one extra prop — a plain `<select>` becomes a searchable Tom Select control:

```blade
<x-select name="assignee" label="Assignee" advanced placeholder="Search people…" :options="$users->pluck('name', 'id')" />
```

### Advanced + multiple (tags)

```blade
<x-select name="tags" label="Tags" advanced multiple placeholder="Add tags…" :options="['php' => 'PHP', 'laravel' => 'Laravel']" :value="['php']" />
```

### Rich options (avatars, flags, badges)

Give an option `html` instead of a plain string label — only used when `advanced` (a native `<select>` can't render markup inside its own options, so it's ignored otherwise, falling back to the plain text label):

```blade
<x-select
    name="assignee"
    label="Assignee"
    advanced
    :options="$users->mapWithKeys(fn ($user) => [
        $user->id => [
            'label' => $user->name,
            'html' => '<span class="avatar avatar-xs" style="background-image: url('.$user->avatar_url.')"></span>',
        ],
    ])"
/>
```

## What this doesn't cover

- **`<optgroup>`** — write the `<select>` (with `data-advanced-select` if you want it enhanced) by hand for grouped options; the `options` prop is a flat list.

## Architecture

Like [`<x-datepicker>`](./datepicker.md), `advanced` is backed by a real npm dependency (Tom Select) rather than just Tabler CSS/markup:

- **`resources/js/select.js`** auto-initializes every `[data-advanced-select]` element (the attribute `advanced` adds), reading `data-placeholder` for the ghost placeholder text. It always applies the same custom render function Tabler's own demo uses — a dropdown row that shows an option's `data-custom-properties` HTML (set from `html` in the `options` array) before its label, falling back to plain text when there isn't one. Imported once from `resources/js/app.js`, so it's part of the single app bundle.
- **`postcss.config.js`** already excludes `--ts-*` (Tom Select's own custom properties) from the `--tblr-` prefixing pass — set up when the project first moved to Vite, before this component existed.
- Tom Select copies every `data-*` attribute from a source `<option>` onto that option's data object automatically (camelCased) — that's how `data-custom-properties` becomes `data.customProperties` in the render function without any extra wiring on our side.
- **`resources/css/app.scss`** bridges the plain Bootstrap 5 custom properties (`--bs-body-bg`, `--bs-border-radius`, etc.) Tom Select's `tom-select.bootstrap5.css` theme is written against to their Tabler equivalents (`--tblr-*`) — Tabler's own build never defines the `--bs-*` names at all. Without that bridge, `.ts-dropdown`'s `background: var(--bs-body-bg)` resolves to nothing and the whole dropdown panel renders fully transparent. A second, narrower rule fixes option text color specifically: the render function above wraps each row in Tabler's own `.dropdown-item` class to match Tabler's look, but that class's color depends on a custom property only ever defined on `.dropdown-menu` — not on `.ts-dropdown` — so it silently fell back to a stale, non-theme-aware color instead of tracking light/dark mode.

If you ever remove the advanced mode entirely, `npm uninstall tom-select`, delete `resources/js/select.js`, drop the `import './select'` line from `resources/js/app.js`, remove the `advanced` prop from `select.blade.php`, and remove the two Tom Select rules from `resources/css/app.scss`.

## See it live

The Starter Kit page's "Form elements" card (plain select/checkbox/switch) and "Advanced select" card (searchable with avatars, tag input) — the searchable one verified interactively in a real browser: the dropdown opens with a search box, every option shows its avatar, and selecting one updates the control with no console errors.
