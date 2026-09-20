# Blade components

Reusable Blade components that wrap Tabler UI markup, built for this starter kit. Each one has a `.blade.php` file in `resources/views/components/` and a usage doc here.

| Component        | Source                                              | Docs                                | Tabler reference |
|-------------------|------------------------------------------------------|---------------------------------------|-------------------|
| `<x-alert>`       | `resources/views/components/alert.blade.php`         | [alert.md](./alert.md)                | [docs.tabler.io/ui/components/alert](https://docs.tabler.io/ui/components/alert) |
| `<x-badge>`       | `resources/views/components/badge.blade.php`         | [badge.md](./badge.md)                | [docs.tabler.io/ui/components/badge](https://docs.tabler.io/ui/components/badge) |
| `<x-avatar>`      | `resources/views/components/avatar.blade.php`        | [avatar.md](./avatar.md)              | [docs.tabler.io/ui/components/avatar](https://docs.tabler.io/ui/components/avatar) |
| `<x-button>`      | `resources/views/components/button.blade.php`        | [button.md](./button.md)              | [docs.tabler.io/ui/components/button](https://docs.tabler.io/ui/components/button) |
| `<x-card>`        | `resources/views/components/card.blade.php`          | [card.md](./card.md)                  | [docs.tabler.io/ui/components/card](https://docs.tabler.io/ui/components/card) |
| `<x-breadcrumb>`  | `resources/views/components/breadcrumb.blade.php`    | [breadcrumb.md](./breadcrumb.md)      | [docs.tabler.io/ui/components/breadcrumb](https://docs.tabler.io/ui/components/breadcrumb) |
| `<x-page-header>` | `resources/views/components/page-header.blade.php`   | [page-header.md](./page-header.md)    | — (composes `page-header` + `<x-breadcrumb>`) |
| `<x-dropdown>` + `<x-dropdown-item>` | `resources/views/components/dropdown.blade.php`, `dropdown-item.blade.php` | [dropdown.md](./dropdown.md) | [docs.tabler.io/ui/components/dropdown](https://docs.tabler.io/ui/components/dropdown) |
| `<x-modal>`       | `resources/views/components/modal.blade.php`          | [modal.md](./modal.md)                | [docs.tabler.io/ui/components/modal](https://docs.tabler.io/ui/components/modal) |
| `<x-segmented-control>` | `resources/views/components/segmented-control.blade.php` | [segmented-control.md](./segmented-control.md) | [docs.tabler.io/ui/components/segmented-control](https://docs.tabler.io/ui/components/segmented-control) |
| `<x-offcanvas>`   | `resources/views/components/offcanvas.blade.php`      | [offcanvas.md](./offcanvas.md)        | [docs.tabler.io/ui/components/offcanvas](https://docs.tabler.io/ui/components/offcanvas) |
| `<x-list-group>` + `<x-list-group-item>` | `resources/views/components/list-group.blade.php`, `list-group-item.blade.php` | [list-group.md](./list-group.md) | [docs.tabler.io/ui/components/list-group](https://docs.tabler.io/ui/components/list-group) |
| `<x-pagination>` | `resources/views/components/pagination.blade.php`     | [pagination.md](./pagination.md)      | [docs.tabler.io/ui/components/pagination](https://docs.tabler.io/ui/components/pagination) |
| `<x-table>`       | `resources/views/components/table.blade.php`          | [table.md](./table.md)                | [docs.tabler.io/ui/components/tables](https://docs.tabler.io/ui/components/tables) |
| `<x-progress>`    | `resources/views/components/progress.blade.php`       | [progress.md](./progress.md)          | [docs.tabler.io/ui/components/progress](https://docs.tabler.io/ui/components/progress) |
| `<x-empty>`       | `resources/views/components/empty.blade.php`          | [empty.md](./empty.md)                | [docs.tabler.io/ui/components/empty](https://docs.tabler.io/ui/components/empty) |
| `<x-tabs>`        | `resources/views/components/tabs.blade.php`           | [tabs.md](./tabs.md)                  | [docs.tabler.io/ui/components/tabs](https://docs.tabler.io/ui/components/tabs) |
| `<x-spinner>`     | `resources/views/components/spinner.blade.php`        | [spinner.md](./spinner.md)            | [docs.tabler.io/ui/components/spinners](https://docs.tabler.io/ui/components/spinners) |
| `<x-status>`      | `resources/views/components/status.blade.php`         | [status.md](./status.md)              | [docs.tabler.io/ui/components/statuses](https://docs.tabler.io/ui/components/statuses) |
| `<x-toast>`       | `resources/views/components/toast.blade.php`          | [toast.md](./toast.md)                | [docs.tabler.io/ui/components/toasts](https://docs.tabler.io/ui/components/toasts) |
| `<x-input>`       | `resources/views/components/input.blade.php`          | [input.md](./input.md)                | [docs.tabler.io/ui/components/form-elements](https://docs.tabler.io/ui/components/form-elements) |
| `<x-select>`      | `resources/views/components/select.blade.php`, `resources/js/select.js` | [select.md](./select.md) | [docs.tabler.io/ui/components/form-elements](https://docs.tabler.io/ui/components/form-elements) — `advanced` mode is [Tom Select](https://tom-select.js.org) |
| `<x-checkbox>`    | `resources/views/components/checkbox.blade.php`       | [checkbox.md](./checkbox.md)          | [docs.tabler.io/ui/components/form-elements](https://docs.tabler.io/ui/components/form-elements) |
| `<x-radio>`       | `resources/views/components/radio.blade.php`          | [radio.md](./radio.md)                | [docs.tabler.io/ui/components/form-elements](https://docs.tabler.io/ui/components/form-elements) |
| `<x-tooltip>`     | `resources/views/components/tooltip.blade.php`        | [tooltip.md](./tooltip.md)            | [docs.tabler.io/ui/components/tooltips](https://docs.tabler.io/ui/components/tooltips) |
| `<x-popover>`     | `resources/views/components/popover.blade.php`        | [popover.md](./popover.md)            | [docs.tabler.io/ui/components/popover](https://docs.tabler.io/ui/components/popover) |
| `<x-placeholder>` + `<x-skeleton>` | `resources/views/components/placeholder.blade.php`, `skeleton.blade.php` | [placeholder.md](./placeholder.md) | [docs.tabler.io/ui/components/placeholder](https://docs.tabler.io/ui/components/placeholder) |
| `<x-ribbon>`      | `resources/views/components/ribbon.blade.php`         | [ribbon.md](./ribbon.md)              | [docs.tabler.io/ui/components/ribbons](https://docs.tabler.io/ui/components/ribbons) |
| `<x-carousel>`    | `resources/views/components/carousel.blade.php`       | [carousel.md](./carousel.md)          | [docs.tabler.io/ui/components/carousel](https://docs.tabler.io/ui/components/carousel) |
| `<x-datepicker>`  | `resources/views/components/datepicker.blade.php`, `resources/js/datepicker.js` | [datepicker.md](./datepicker.md) | [Litepicker](https://github.com/wakirin/Litepicker) — backed by a real JS library, not just Tabler CSS/markup |
| `<x-calendar>`    | `resources/views/components/calendar.blade.php`, `resources/js/calendar.js` | [calendar.md](./calendar.md) | [FullCalendar](https://fullcalendar.io) — backed by a real JS library, not just Tabler CSS/markup |

Also available (undocumented, small enough to read directly):

- `<x-icon name="...">` — `resources/views/components/icon.blade.php`, a small set of inline Tabler SVG icons.
- `<x-brand>` — `resources/views/components/brand.blade.php`, the Tabler wordmark logo link.
