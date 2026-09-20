# Pagination

`<x-pagination>` wraps Tabler's [Pagination component](https://docs.tabler.io/ui/components/pagination). Unlike the other components in this library, it isn't array-driven — it takes a real Laravel paginator directly and reads page numbers, URLs, and prev/next state straight from it.

Source: `resources/views/components/pagination.blade.php`

## Basic usage

```blade
<x-pagination :paginator="$users" />
```

```php
// in a controller or route closure
$users = User::orderBy('name')->paginate(15);
```

## Props

| Prop         | Type                              | Default        | Description |
|--------------|-----------------------------------|----------------|--------------|
| `paginator`  | `LengthAwarePaginator`\|`Paginator` | —            | Required. Any paginator Laravel gives you (`paginate()`, `simplePaginate()`, a resource collection's paginator, etc.). |
| `variant`    | string\|null                      | `null`         | `outline`, `circle`, or `circle-outline`. |
| `onEachSide` | int                                | `1`            | How many page numbers to show beside the current page before collapsing the rest into `…`. |
| `withLabels` | bool                               | `false`        | Shows "Previous"/"Next" text instead of chevron icons (`page-text`). |
| `label`      | string                            | `Pagination`   | `aria-label` on the wrapping `<nav>`. |

Any other attribute (e.g. `class="m-0 ms-auto"`) is merged onto the root `<nav>`,
not the inner `<ul>` — so positioning classes actually work. The pagination
classes themselves (`pagination`, `pagination-outline`, …) are computed and stay
on the `<ul>`.

## Numbered pages vs. prev/next only

If `$paginator` is a simple `Paginator` (from `simplePaginate()`, which doesn't know the total page count), the component renders **only** Previous/Next — there's no way to know how many numbered pages to show. Use `paginate()` (a `LengthAwarePaginator`) to get numbered pages with `…` gaps.

## Variants

### Outline / circle

```blade
<x-pagination :paginator="$users" variant="outline" />
<x-pagination :paginator="$users" variant="circle" />
<x-pagination :paginator="$users" variant="circle-outline" />
```

### With text labels

```blade
<x-pagination :paginator="$users" with-labels />
```

### Wider page-number window

```blade
<x-pagination :paginator="$users" :on-each-side="2" />
```

### Multiple paginators on one page

Give each query a distinct page name so they don't fight over the same `?page=` query parameter, and pass it straight to the component — no extra prop needed, since it reads whatever URL the paginator itself generates:

```php
$users = User::paginate(5, pageName: 'users_page');
```

```blade
<x-pagination :paginator="$users" />
```

### Hiding it when there's nothing to paginate

The component always renders (Previous/Next simply both end up disabled on a single page). Wrap it yourself if you'd rather hide it entirely:

```blade
@if ($users->hasPages())
    <x-pagination :paginator="$users" />
@endif
```

## See it live

The Starter Kit page's "Table + Pagination" card lists seeded users 5 at a time — the route (`routes/web.php`) passes a real `User::orderBy('name')->paginate(5, pageName: 'users_page')` to the view.
