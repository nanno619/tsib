# Offcanvas

`<x-offcanvas>` wraps Tabler's [Offcanvas component](https://docs.tabler.io/ui/components/offcanvas) — a drawer that slides in from an edge, for filters, detail panels, or a secondary form that doesn't warrant leaving the page.

Source: `resources/views/components/offcanvas.blade.php`

## Basic usage

Open it from anywhere with Bootstrap's data-API — there's no trigger component, and no JavaScript of our own:

```blade
<x-button data-bs-toggle="offcanvas" data-bs-target="#my-panel" aria-controls="my-panel">
    Filters
</x-button>

<x-offcanvas id="my-panel" title="Filter results">
    <p>Panel body.</p>

    <x-slot:footer>
        <div class="btn-list justify-content-end">
            <x-button color="primary">Apply</x-button>
        </div>
    </x-slot:footer>
</x-offcanvas>
```

## Props

| Prop         | Type         | Default  | Description |
|--------------|--------------|----------|--------------|
| `id`         | string       | —        | Required. What `data-bs-target` points at. |
| `title`      | string\|null | `null`   | Heading, and the dialog's accessible name. Omit for a titleless panel — the close button stays. |
| `position`   | string       | `'end'`  | `start`, `end`, `top` or `bottom` (`offcanvas-{position}`). |
| `responsive` | string\|null | `null`   | `sm`/`md`/`lg`/`xl`/`xxl` — static above that breakpoint instead of sliding. |
| `narrow`     | bool         | `false`  | Tabler's narrower drawer width (`offcanvas-narrow`). |
| `static`     | bool         | `false`  | Clicking the backdrop no longer dismisses it (`data-bs-backdrop="static"`, `data-bs-keyboard="false"`). Note this also disables Escape, unlike `<x-modal static>`. |

## Slots

| Slot     | Description |
|----------|--------------|
| default  | The panel body (`.offcanvas-body`). |
| `footer` | Optional footer (`.offcanvas-footer`), typically `<x-button>`s. |

## Putting a form in one

The footer sits **outside** the body, so a submit button there can't be a descendant of the form. Point it back at the form by id instead:

```blade
<x-offcanvas id="filters" title="Filter users">
    <form method="GET" action="{{ route('admin.users.index') }}" id="filters-form">
        <x-input name="name" label="Name" />
    </form>

    <x-slot:footer>
        <x-button type="submit" form="filters-form" color="primary">Apply</x-button>
    </x-slot:footer>
</x-offcanvas>
```

That's the pattern `/admin/users` uses for its filter panel.

## Dropdowns inside the body

`.offcanvas-body` scrolls, so an absolutely-positioned dropdown rendered *inside* it would be clipped at its bottom edge. That doesn't bite here: `resources/js/select.js` sets Tom Select's `dropdownParent: 'body'`, which appends the menu to the document `<body>` and positions it over the drawer. **`<x-select advanced>` is safe inside an offcanvas.**

The roles filter on `/admin/users` is an `<x-select advanced multiple>` — a live example — and the sort control is a plain `<x-select>`, where search and custom rendering would be overkill.

## See it live

`/admin/users` — the search button in the card header opens the filter panel.
