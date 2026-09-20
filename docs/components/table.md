# Table

`<x-table>` wraps Tabler's [Tables component](https://docs.tabler.io/ui/components/tables). It's a thin wrapper — it handles the `<table>` tag, its modifier classes, and the responsive scroll wrapper; `<thead>`/`<tbody>` content stays plain HTML since columns and cell content vary too much to be worth prop-ifying (compose `<x-avatar>`, `<x-badge>`, `<x-dropdown>`, etc. inside cells freely).

Source: `resources/views/components/table.blade.php`

## Basic usage

```blade
<x-table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th class="w-1"></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td class="text-secondary">{{ $user->email }}</td>
                <td><a href="#">Edit</a></td>
            </tr>
        @endforeach
    </tbody>
</x-table>
```

## Props

| Prop         | Type | Default | Description |
|--------------|------|---------|--------------|
| `responsive` | bool | `true`  | Wraps the table in `table-responsive` (horizontal scroll on overflow). Set `false` to omit the wrapper. |
| `vcenter`    | bool | `true`  | Vertically centers cell content (`table-vcenter`) — Tabler's usual default. |
| `nowrap`     | bool | `false` | Keeps cell content on one line (`table-nowrap`). |
| `card`       | bool | `false` | Adds `card-table`, for a table that sits directly inside a `.card` (see below) rather than in its own bordered box. |

Any other attribute (e.g. `class="text-nowrap"`) is merged onto the `<table>`.

## Row coloring

Tabler colors whole rows with `table-{color}` on the `<tr>` — this is plain markup, not a component prop:

```blade
<tr class="table-danger">
    <td>...</td>
</tr>
```

## Sticky header

Also plain markup — add `sticky-top` to your `<thead>`:

```blade
<x-table>
    <thead class="sticky-top">
        ...
    </thead>
</x-table>
```

## Inside a card (`card-table`)

A `card-table` sits as a **direct child of `.card`**, not inside `.card-body` — so build the surrounding card by hand rather than through `<x-card>` (which always wraps its slot in `card-body`; see [card.md](./card.md#variants-this-component-doesnt-wrap)):

```blade
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Users</h3>
    </div>

    <x-table card>
        <thead>...</thead>
        <tbody>...</tbody>
    </x-table>

    <div class="card-footer">
        <x-pagination :paginator="$users" />
    </div>
</div>
```

## See it live

The Starter Kit page's "Table + Pagination" card renders seeded users this way — `card-table` inside a hand-built card, with `<x-pagination>` in the footer. See [pagination.md](./pagination.md).
