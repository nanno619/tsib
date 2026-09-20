# Calendar

`<x-calendar>` wraps [FullCalendar](https://fullcalendar.io) (month/week/list views) styled to match Tabler via its official vendor theming.

Source: `resources/views/components/calendar.blade.php`

## Basic usage

```blade
<x-calendar
    height="600"
    :events="[
        ['title' => 'Design Sprint', 'start' => '2026-09-10T17:00:00', 'color' => '#ae3ec9'],
        ['title' => 'Offsite Retreat', 'start' => '2026-09-02', 'end' => '2026-09-04', 'color' => '#d63384'],
    ]"
/>
```

## Props

| Prop         | Type   | Default          | Description |
|--------------|--------|------------------|--------------|
| `id`         | string | `calendar`       | Element id; also the key `window.tabler_calendar` stores the FullCalendar instance under. Set an explicit id if you render more than one calendar on a page. |
| `view`       | string | `dayGridMonth`   | Initial FullCalendar view (`dayGridMonth`, `timeGridWeek`, `listWeek`, ...). Users can still switch views with the built-in toolbar buttons. |
| `height`     | string\|int | `auto`      | Pixel height (numeric) or a FullCalendar height keyword (`auto`, `parent`). See [Architecture](#architecture) for why this is coerced client-side. |
| `selectable` | bool   | `false`          | Lets users click/drag a date range on the grid (fires FullCalendar's own `select` callback — not wired to anything by default; extend `resources/js/calendar.js` if you need it). |
| `events`     | array  | `[]`             | Array of [FullCalendar event objects](https://fullcalendar.io/docs/event-object) — `title`, `start`, `end`, `color`, `allDay`, etc. JSON-encoded into a `data-*` attribute. |

Any other attribute is merged onto the root `<div>`.

## Architecture

Unlike most components in this library, `<x-calendar>` is backed by a real npm dependency (FullCalendar) rather than just Tabler CSS/markup — same category as [`<x-datepicker>`](./datepicker.md) and [`<x-select advanced>`](./select.md):

- **`resources/js/calendar.js`** auto-initializes every `[data-calendar]` element, reading its `data-calendar-*` attributes and constructing a `Calendar` instance from `@fullcalendar/core` with the `daygrid`, `timegrid`, `list`, and `interaction` plugins. Imported once from `resources/js/app.js`, so it's part of the single app bundle.
- **Version pinning**: `@fullcalendar/core` currently has a stable `7.x` release, but its plugin packages (`daygrid`, `timegrid`, `list`, `interaction`) are still only published as `7.x` release candidates. All five packages are pinned to `^6.1.21` — the last version where core and every plugin used here are simultaneously stable — to avoid mixing a stable core with prerelease plugins. Revisit this pin once the `7.x` plugin packages reach a stable release.
- **No separate CSS import**: FullCalendar injects its own base styles into a `<style data-fullcalendar>` tag at render time rather than shipping a stylesheet to import. Tabler's colors and spacing come entirely from `@tabler/core/scss/tabler-vendors` (imported in `resources/css/app.scss`), which overrides FullCalendar's own `--fc-*` custom properties and a handful of selectors (`.fc-button`, `.fc-toolbar-title`, ...) so the calendar matches the rest of the app in both light and dark mode — verified in a real browser.
- **Height must be coerced to a number**: `height` arrives in `calendar.js` via `element.dataset.calendarHeight`, which is always a string — FullCalendar's `height` option only accepts a real `Number` for pixel heights (a numeric *string* like `"600"` is silently misread and the calendar collapses to just its toolbar). `calendar.js` coerces any numeric-looking value with `Number(...)` before passing it to the `Calendar` constructor; non-numeric values (`auto`, `parent`) pass through unchanged.
- **`events`** is JSON-encoded into `data-calendar-events` and `JSON.parse`'d back out in `calendar.js` — pass plain arrays/objects from Blade, not model instances (map Eloquent collections to FullCalendar's event shape first, e.g. `$events->map(fn ($e) => ['title' => $e->title, 'start' => $e->starts_at->toIso8601String()])`).

If you ever remove this component, `npm uninstall @fullcalendar/core @fullcalendar/daygrid @fullcalendar/timegrid @fullcalendar/list @fullcalendar/interaction`, delete `resources/js/calendar.js`, drop the `import './calendar'` line from `resources/js/app.js`, and delete `calendar.blade.php`. Leave `@tabler/core/scss/tabler-vendors` in place — it also supplies the fix for the Tom Select transparent-dropdown bug documented in `app.scss`.

## See it live

The Starter Kit page's "Calendar" card — a 600px month view seeded with sample events, verified interactively in a real browser in both light and dark mode (day-grid renders at full height, toolbar/view-switcher buttons and event colors match the active theme, no console errors).
