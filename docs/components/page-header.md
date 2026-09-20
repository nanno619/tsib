# Page header

`<x-page-header>` renders the title bar every page uses at the top of its content, matching Tabler's `page-header` markup. It optionally renders a breadcrumb trail above the title (via [`<x-breadcrumb>`](./breadcrumb.md)) and a right-aligned action slot.

Source: `resources/views/components/page-header.blade.php`

## Basic usage

It's placed in the `header` slot of `<x-layouts.app>`:

```blade
<x-layouts.app title="Dashboard">
    <x-slot:header>
        <x-page-header title="Dashboard" />
    </x-slot:header>

    {{-- page content --}}
</x-layouts.app>
```

## Props

| Prop          | Type         | Default | Description |
|---------------|--------------|---------|--------------|
| `title`       | string       | —       | Required. Rendered as `<h2 class="page-title">`. |
| `subtitle`    | string\|null | `null`  | Small text above the title (`page-pretitle`). Rarely used together with `breadcrumbs` — pick one. |
| `breadcrumbs` | array        | `[]`    | Passed straight to `<x-breadcrumb :items="...">` and rendered above the title. See [breadcrumb.md](./breadcrumb.md) for the item shape. |

## Slots

| Slot      | Description |
|-----------|--------------|
| `actions` | Right-aligned buttons, wrapped in a `btn-list`. |

## With breadcrumbs

The recommended pattern for any page that isn't the top-level Dashboard: use `breadcrumbs` instead of `subtitle` to show where the page sits in the hierarchy.

```blade
<x-page-header title="Profile" :breadcrumbs="[
    ['label' => 'Dashboard', 'url' => route('dashboard')],
    ['label' => 'Settings'],
    ['label' => 'Profile'],
]" />
```

The last breadcrumb item is always the current page (plain text, not a link) — see [breadcrumb.md](./breadcrumb.md#how-items-are-rendered) for the full rule.

## With actions

```blade
<x-page-header title="Users">
    <x-slot:actions>
        <x-button href="#" color="primary" variant="outline" icon="mail">Export</x-button>
        <x-button href="{{ route('users.create') }}" color="primary" icon="plus">Add user</x-button>
    </x-slot:actions>
</x-page-header>
```

## With a subtitle instead of breadcrumbs

For a flat page with no meaningful hierarchy, a short subtitle still works:

```blade
<x-page-header title="Starter Kit" subtitle="Reference" />
```

## See it live

Every authenticated page in this starter kit (`dashboard`, `settings/profile`, `settings/password`, `starter-kit`) builds its header with this component — see `resources/views/layouts/partials/navbar.blade.php`'s sibling pages for real examples.
