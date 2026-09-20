# Breadcrumb

`<x-breadcrumb>` wraps Tabler's [Breadcrumb component](https://docs.tabler.io/ui/components/breadcrumb). It's also built into `<x-page-header>`, so most pages never need to reach for it directly — see [page-header.md](./page-header.md).

Source: `resources/views/components/breadcrumb.blade.php`

## Basic usage

```blade
<x-breadcrumb :items="[
    ['label' => 'Dashboard', 'url' => route('dashboard')],
    ['label' => 'Settings'],
    ['label' => 'Profile'],
]" />
```

Renders:

```html
<nav aria-label="Breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item">Settings</li>
        <li class="breadcrumb-item active" aria-current="page">Profile</li>
    </ol>
</nav>
```

## Props

| Prop        | Type          | Default | Description |
|-------------|---------------|---------|--------------|
| `items`     | array         | `[]`    | Ordered list of crumbs. Each item is `['label' => string, 'url' => string|null, 'icon' => string|null]`. |
| `variant`   | string\|null  | `null`  | Separator style: `arrows`, `dots`, or `bullets` (`breadcrumb-{variant}`). Omit for the default slash separator. |
| `muted`     | bool          | `false` | Lower-emphasis style (`breadcrumb-muted`). |

Any other attribute (e.g. `class="mb-2"`) is merged onto the `<ol>`.

## How items are rendered

- **The last item is always the current page**: rendered as plain text with `class="breadcrumb-item active"` and `aria-current="page"` — never a link, even if you pass a `url` for it. This follows Tabler's own guidance that the current page shouldn't be a link.
- **Any other item with a `url`** renders as a link (`<a href="...">`).
- **Any other item without a `url`** (e.g. a "Settings" dropdown that isn't itself a page) renders as plain, unlinked text — useful for a middle crumb that only exists to describe a section.
- **`icon`** (optional, any `<x-icon>` name) renders before the label, inside the link or text.

```blade
<x-breadcrumb :items="[
    ['label' => 'Dashboard', 'url' => route('dashboard'), 'icon' => 'home'],
    ['label' => 'Library'],
    ['label' => 'Data'],
]" />
```

## Separator variants

```blade
<x-breadcrumb variant="arrows" :items="$items" />
<x-breadcrumb variant="dots" :items="$items" />
<x-breadcrumb variant="bullets" :items="$items" />
```

## Muted

```blade
<x-breadcrumb muted :items="$items" />
```

## Used inside the page header

`<x-page-header>` accepts a `breadcrumbs` prop and renders an `<x-breadcrumb>` above the title for you:

```blade
<x-page-header title="Profile" :breadcrumbs="[
    ['label' => 'Dashboard', 'url' => route('dashboard')],
    ['label' => 'Settings'],
    ['label' => 'Profile'],
]" />
```

See [page-header.md](./page-header.md) for the full prop list.

## Accessibility

- The separator is drawn with CSS (`::before` on `.breadcrumb-item`), never typed into the markup — a literal `/` or `>` character between items would be read aloud by screen readers between every crumb.
- `aria-label="Breadcrumb"` is always set on the `<nav>`.
- `aria-current="page"` is always set on the last item, and it's never a link.

## See it live

The `/starter-kit` page shows default, `arrows`, and `muted dots` variants together. Every page's header (`/settings/profile`, `/settings/password`, `/starter-kit`) uses `<x-page-header :breadcrumbs="...">`.
