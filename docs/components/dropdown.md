# Dropdown

`<x-dropdown>` + `<x-dropdown-item>` wrap Tabler's [Dropdown component](https://docs.tabler.io/ui/components/dropdown) (Bootstrap 5's dropdown JS). `<x-dropdown>` owns the toggle button/link and the menu container — trigger *content* is yours via the `trigger` slot, everything else (`data-bs-toggle`, alignment, direction, auto-close) is handled for you.

Source: `resources/views/components/dropdown.blade.php`, `resources/views/components/dropdown-item.blade.php`

## Basic usage

```blade
<x-dropdown trigger-class="btn">
    <x-slot:trigger>Open dropdown</x-slot:trigger>

    <x-dropdown-item href="#">Action</x-dropdown-item>
    <x-dropdown-item href="#">Another action</x-dropdown-item>
    <div class="dropdown-divider"></div>
    <x-dropdown-item href="#">Separated link</x-dropdown-item>
</x-dropdown>
```

## `<x-dropdown>` props

| Prop           | Type         | Default | Description |
|----------------|--------------|---------|--------------|
| `as`           | string       | `div`   | The wrapper element. Use `li` when the dropdown is a `<ul class="navbar-nav">` item — see the Settings menu in `resources/views/layouts/partials/navbar.blade.php`. |
| `href`         | string\|null | `null`  | Renders the trigger as `<a href="...">` instead of `<button type="button">`. |
| `triggerClass` | string       | `btn`   | Classes for the trigger element. Override for a `nav-link`-style trigger (e.g. `"nav-link px-0"`). |
| `caret`        | bool         | `true`  | Adds `dropdown-toggle` (the caret arrow) to the trigger. Set `false` for icon-only triggers where a caret looks wrong (see the notification/user-menu triggers in the navbar). |
| `label`        | string\|null | `null`  | `aria-label` on the trigger — required whenever the trigger has no visible text (icon-only). |
| `direction`    | string       | `down`  | `down`, `up`, `end`, or `start` — maps to `dropdown`/`dropup`/`dropend`/`dropstart`. Use `end` for a nested submenu (see Preferences in the navbar). |
| `align`        | string\|null | `null`  | `end` right-aligns the menu (`dropdown-menu-end`). |
| `arrow`        | bool         | `false` | Adds a pointer toward the trigger (`dropdown-menu-arrow`). |
| `dark`         | bool         | `false` | Dark menu background (`bg-dark text-white`). |
| `card`         | bool         | `false` | `dropdown-menu-card`, for a menu whose content is a `<div class="card">` (see the notifications dropdown in the navbar). |
| `autoClose`    | bool\|string | `true`  | `true` (default: closes on any click), `false` (only the toggle or Escape closes it), `outside`, or `inside` — passed straight to `data-bs-auto-close`. |

## Slots

| Slot      | Description |
|-----------|--------------|
| `trigger` | Content inside the trigger element — text, an `<x-icon>`, an `<x-avatar>` + name block, anything. |
| (default) | Menu content: `<x-dropdown-item>`, `<div class="dropdown-divider">`, `<span class="dropdown-header">`, or a nested `<x-dropdown>` for a submenu. |

## `<x-dropdown-item>` props

| Prop         | Type         | Default   | Description |
|--------------|--------------|-----------|--------------|
| `href`       | string\|null | `null`    | Renders an `<a>`. Omit for a `<button>` (e.g. a JS action, or a form submit button). |
| `type`       | string       | `button`  | The `<button>` `type` when there's no `href` — set to `submit` inside a `<form>` (see "Sign out" in the navbar). |
| `icon`       | string\|null | `null`    | An `<x-icon>` name, shown before the label. |
| `badge`      | string\|null | `null`    | Renders an `<x-badge>` pushed to the right (`ms-auto`) — e.g. a count. |
| `badgeColor` | string       | `primary` | Color passed to the badge. |
| `active`     | bool         | `false`   | Marks the current item (`active`). |
| `disabled`   | bool         | `false`   | Disables the item — `disabled` attribute for a button, `aria-disabled`/`tabindex="-1"` for a link. |

## Variants

### Icon-only trigger (no caret)

```blade
<x-dropdown trigger-class="nav-link px-0" :caret="false" label="Show notifications" align="end" arrow>
    <x-slot:trigger><x-icon name="bell" /></x-slot:trigger>

    <x-dropdown-item href="#">Notification</x-dropdown-item>
</x-dropdown>
```

### Header, icons, badge, active/disabled

```blade
<x-dropdown trigger-class="btn btn-primary" align="end" arrow>
    <x-slot:trigger>Account</x-slot:trigger>

    <span class="dropdown-header">Section</span>
    <x-dropdown-item href="#" icon="user" active>Profile</x-dropdown-item>
    <x-dropdown-item href="#" icon="lock" badge="3">Password</x-dropdown-item>
    <x-dropdown-item href="#" icon="settings" disabled>Settings</x-dropdown-item>
</x-dropdown>
```

### Card content

```blade
<x-dropdown trigger-class="nav-link px-0" :caret="false" label="Show notifications" align="end" arrow card auto-close="outside">
    <x-slot:trigger><x-icon name="bell" /></x-slot:trigger>

    <div class="card">
        <div class="card-header"><h3 class="card-title">Notifications</h3></div>
        <div class="list-group list-group-flush">...</div>
    </div>
</x-dropdown>
```

### Dropup / nested submenu (dropend)

```blade
<x-dropdown direction="up" trigger-class="btn">
    <x-slot:trigger>Dropup</x-slot:trigger>
    <x-dropdown-item href="#">Action</x-dropdown-item>
</x-dropdown>
```

Nest a second `<x-dropdown direction="end">` inside the parent's default slot for a multi-level submenu — see "Preferences" under Settings in `resources/views/layouts/partials/navbar.blade.php`.

### As a `<ul>` navbar item

Pass `as="li"` so the dropdown is a valid direct child of `<ul class="navbar-nav">`:

```blade
<ul class="navbar-nav">
    <x-dropdown as="li" trigger-class="nav-link" class="nav-item">
        <x-slot:trigger>Settings</x-slot:trigger>
        <x-dropdown-item href="#">Profile</x-dropdown-item>
    </x-dropdown>
</ul>
```

## What this doesn't wrap

- **Split button** (a separate action button next to the dropdown toggle) — two independent actions don't fit this component's single-trigger design. Compose it directly with `<x-button>` + Bootstrap's `btn-group`/`dropdown-toggle-split` markup from [Tabler's docs](https://docs.tabler.io/ui/components/dropdown).
- **Multi-column menus** (`dropdown-menu-columns`) — wrap `<x-dropdown-item>` calls in the raw `dropdown-menu-columns`/`dropdown-menu-column` markup inside the default slot.
- **Checkbox/radio items** — use `<label class="dropdown-item">` with a form input directly, per Tabler's docs; not a variant of `<x-dropdown-item>`.

## Accessibility

- The trigger is always a real `button` or an `a` with `href` — never a bare `<div>` — and `data-bs-toggle="dropdown"` is set for you. Bootstrap manages `aria-expanded` on it automatically; don't set it by hand.
- Always pass `label` when the trigger has no visible text (icon-only).
- Disabled `<x-dropdown-item>` links get `aria-disabled="true"` and `tabindex="-1"`, since `<a>` has no native `disabled` attribute.

## See it live

The Starter Kit page's "Dropdown" card shows the default, end-aligned-with-arrow, and dropup variants. The navbar (notifications, user menu, Settings with a nested Preferences submenu) uses every other variant in real layout code.
