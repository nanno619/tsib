# Avatar

`<x-avatar>` wraps Tabler's [Avatar component](https://docs.tabler.io/ui/components/avatar). It renders an image, initials, or an icon avatar, with size, shape, color and status-dot variants.

Source: `resources/views/components/avatar.blade.php`

## Basic usage

```blade
<x-avatar src="{{ asset('vendor/tabler/static/avatars/001m.jpg') }}" label="Jane Doe" />
<x-avatar initials="JD" color="blue" label="Jane Doe" />
<x-avatar icon="user" />
```

## Props

| Prop       | Type         | Default | Description |
|------------|--------------|---------|--------------|
| `src`      | string\|null | `null`  | Image URL, set as `background-image`. Takes priority over `initials`/slot content. |
| `initials` | string\|null | `null`  | Text shown when there's no `src` (e.g. `"JD"`, or `"+8"` for an overflow count). |
| `icon`     | string\|null | `null`  | An `<x-icon>` name, shown instead of initials/slot content. |
| `size`     | string\|null | `null`  | `xs`, `sm`, `lg`, `xl`, or `2xl` (`avatar-{size}`). Omit for the default (medium) size. |
| `shape`    | string\|null | `null`  | `square` (`avatar-square`) for a rounded-square avatar, or pass a raw utility class like `rounded-0` / `rounded-3`. Omit for the default circle. |
| `color`    | string\|null | `null`  | Any Tabler color, used as a light background for initials/icon avatars (`bg-{color}-lt`). Ignored when `src` is set. |
| `status`   | string\|null | `null`  | `online`, `away`, `busy`, `offline` (mapped to `success`/`warning`/`danger`/`secondary`), or any raw Tabler color. Renders a small `badge` dot in the corner. |
| `label`    | string\|null | `null`  | Sets `aria-label` on the avatar — there's no `alt` text on a background-image avatar, so this is how you give it an accessible name. |

You can also pass content as the default slot instead of `initials` — useful if you want markup rather than plain text:

```blade
<x-avatar>JD</x-avatar>
```

Any other attribute (e.g. `class="me-2"`) is merged onto the outer `<span>`.

## Variants

### Sizes

```blade
<x-avatar size="xs" initials="AB" />
<x-avatar size="sm" initials="AB" />
<x-avatar initials="AB" />
<x-avatar size="lg" initials="AB" />
<x-avatar size="xl" initials="AB" />
```

### Shape

```blade
<x-avatar src="{{ $url }}" />                {{-- circle (default) --}}
<x-avatar initials="TB" shape="square" />     {{-- avatar-square --}}
<x-avatar src="{{ $url }}" shape="rounded-0" /> {{-- sharp corners --}}
```

### Color (initials/icon only)

```blade
<x-avatar initials="AB" color="green" />
<x-avatar initials="CD" color="red" />
<x-avatar icon="user" color="primary" />
```

### Status dot

```blade
<x-avatar src="{{ $url }}" status="online" label="Jane Doe, online" />
<x-avatar src="{{ $url }}" status="busy" label="Jane Doe, busy" />
<x-avatar src="{{ $url }}" status="blue" label="Jane Doe" /> {{-- any raw Tabler color also works --}}
```

### Icon avatar

```blade
<x-avatar icon="user" />
```

### List / stacked list

```blade
<div class="avatar-list">
    <x-avatar src="{{ $url1 }}" />
    <x-avatar initials="JL" />
</div>

<div class="avatar-list avatar-list-stacked">
    <x-avatar src="{{ $url1 }}" />
    <x-avatar src="{{ $url2 }}" />
    <x-avatar initials="+8" />
</div>
```

## Accessibility

Tabler's own guidance: image avatars have no `alt` text (they're a `background-image`, not an `<img>`). *"Show the user name next to it, or add an `aria-label` to the avatar."*

- Pass `label` whenever the avatar isn't already paired with visible text (e.g. a name next to it in a list row).
- The navbar's user-menu avatar (`resources/views/layouts/partials/navbar.blade.php`) is a real example: `<x-avatar size="sm" :initials="..." :label="auth()->user()->name" />`.

## See it live

The `/starter-kit` page renders sizes, colors, an image avatar, a status dot, a square avatar, an icon avatar, and a stacked list. The navbar's user menu also uses `<x-avatar>`.
