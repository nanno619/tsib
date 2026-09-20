# List group

`<x-list-group>` + `<x-list-group-item>` wrap Tabler's [List group component](https://docs.tabler.io/ui/components/list-group) — a vertical list of linked or static rows.

Source: `resources/views/components/list-group.blade.php`, `resources/views/components/list-group-item.blade.php`

## Basic usage

```blade
<x-list-group>
    <x-list-group-item href="#" active>Profile</x-list-group-item>
    <x-list-group-item href="#">Notifications</x-list-group-item>
    <x-list-group-item href="#">Security</x-list-group-item>
</x-list-group>
```

## `<x-list-group>` props

| Prop          | Type    | Default | Description |
|---------------|---------|---------|--------------|
| `as`          | string  | `div`   | The wrapper element — use `nav` for a labeled navigation list (pair with `aria-label`, passed straight through as an attribute). |
| `transparent` | bool    | `false` | Drops the border/background (`list-group-transparent`) — reads as a plain nav inside a card or sidebar section. |
| `flush`       | bool    | `false` | Removes outer borders/rounding (`list-group-flush`) so it sits flush inside a container, typically a `card-body` with no padding. |
| `hoverable`   | bool    | `false` | Adds a hover background per row (`list-group-hoverable`). |

## `<x-list-group-item>` props

| Prop        | Type         | Default | Description |
|-------------|--------------|---------|--------------|
| `href`      | string\|null | `null`  | Renders an `<a>`. Omit for a static, non-clickable `<div>` row (e.g. one with its own nested action link). |
| `action`    | bool\|null   | `href`  | Adds `list-group-item-action` (hover/active styling). Defaults to whether `href` is set — override to force it on a static row, or off on a link that shouldn't look interactive. |
| `active`    | bool         | `false` | Marks the current row. |
| `flushEdge` | bool         | `false` | `border-0 rounded-0` — strips the item's own border/corners when it's the sole item in a `card-body p-0` and the card's own border should show through instead. |

Any other attribute (e.g. `class="d-flex align-items-center"`) is merged onto the item.

## Variants

### Transparent (settings sub-nav)

```blade
<x-list-group as="nav" aria-label="Account settings" transparent>
    <x-list-group-item href="{{ route('settings.profile') }}" class="d-flex align-items-center" :active="request()->routeIs('settings.profile')">Profile</x-list-group-item>
    <x-list-group-item href="{{ route('settings.password') }}" class="d-flex align-items-center" :active="request()->routeIs('settings.password')">Password</x-list-group-item>
</x-list-group>
```

### Flush + hoverable (inside a card, e.g. a notification list)

```blade
<x-card title="Notifications">
    <x-list-group flush hoverable>
        <x-list-group-item>
            <div class="text-secondary">You're all caught up.</div>
        </x-list-group-item>
    </x-list-group>
</x-card>
```

### Rich row content

The item is just a styled container — compose any content inside it, including other components:

```blade
<x-list-group-item>
    <div class="d-flex align-items-center">
        <x-avatar size="sm" color="warning" icon="alert-triangle" class="me-3" />
        <div class="flex-fill">
            <div class="fw-semibold">Users need verification</div>
            <div class="text-secondary small">3 users pending verification</div>
        </div>
        <x-badge color="warning">3</x-badge>
    </div>
</x-list-group-item>
```

## See it live

`resources/views/components/settings/nav.blade.php` (the Settings sub-nav on `/settings/profile` and `/settings/password`) uses the transparent variant. The Starter Kit page's "List group" card shows a transparent nav and a flush/hoverable notification-style list.
