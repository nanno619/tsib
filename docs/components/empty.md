# Empty state

`<x-empty>` wraps Tabler's [Empty states component](https://docs.tabler.io/ui/components/empty) — a placeholder screen for first-use, no-data, or error states.

Source: `resources/views/components/empty.blade.php`

## Basic usage

```blade
<x-empty icon="mood-empty" title="No results found" subtitle="Try adjusting your search or filter to find what you're looking for.">
    <x-slot:actions>
        <x-button color="primary" icon="search">Search again</x-button>
    </x-slot:actions>
</x-empty>
```

## Props

| Prop       | Type         | Default | Description |
|------------|--------------|---------|--------------|
| `title`    | string       | —       | Required. The main message (`empty-title`). |
| `subtitle` | string\|null | `null`  | Supporting text (`empty-subtitle`). |
| `icon`     | string\|null | `null`  | An `<x-icon>` name shown above the title (`empty-icon`). |
| `header`   | string\|null | `null`  | Large text (e.g. an error code like `"404"`) shown instead of an icon (`empty-header`). Ignored if `icon` is set. |
| `headingLevel` | string   | `'p'`   | Tag for the title. Use `h1` for a full-page empty state so the page has a heading; leave as `p` when nested in a card that already supplies one. |

## Slots

| Slot      | Description |
|-----------|--------------|
| `image`   | An illustration (`empty-img`) instead of an icon or header — pass raw SVG/`<img>` markup. Ignored if `icon` or `header` is set. |
| `actions` | Buttons/links below the text (`empty-action`), typically an `<x-button>`. |

## Variants

### Error page

Full-page error screens are `<x-empty>` with an illustration and `heading-level="h1"`
(the `p` default would leave the page with no heading at all). All eight of them
live in `resources/views/errors/` — see `errors/404.blade.php`:

```blade
<x-layouts.guest title="Page not found">
    <x-empty heading-level="h1" title="Page not found" subtitle="…">
        <x-slot:image>
            @include('errors.illustrations.client-error')
        </x-slot:image>
    </x-empty>
</x-layouts.guest>
```

### Illustration instead of an icon

```blade
<x-empty title="Invoices are managed from here" subtitle="Create your first invoice to get started.">
    <x-slot:image>
        {{-- raw SVG illustration, e.g. from tabler.io/illustrations --}}
    </x-slot:image>

    <x-slot:actions>
        <x-button color="primary" icon="plus">New invoice</x-button>
    </x-slot:actions>
</x-empty>
```

### No action

```blade
<x-empty icon="mood-empty" title="Nothing here yet" />
```

## See it live

The Starter Kit page's "Empty state" card shows the icon-led variant with an action button.
