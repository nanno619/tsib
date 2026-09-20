# Datepicker

`<x-datepicker>` wraps Tabler's datepicker pattern — a `form-control` (or an inline container) driven by [Litepicker](https://github.com/wakirin/Litepicker), the JS library Tabler itself uses. Unlike every other component in this library, this one needed a real npm dependency, not just Tabler markup — see [Architecture](#architecture) below.

Source: `resources/views/components/datepicker.blade.php`, `resources/js/datepicker.js`

## Basic usage

```blade
<x-datepicker name="starts_at" label="Start date" />
```

## Props

| Prop        | Type          | Default        | Description |
|-------------|---------------|----------------|--------------|
| `name`      | string        | —              | Required. |
| `label`     | string\|null  | `null`         | |
| `value`     | string\|null  | `null`         | Fallback value, fed through `old($name, $value)`. |
| `placeholder` | string      | `Select a date`| Ignored when `inline`. |
| `help`      | string\|null  | `null`         | Same as `<x-input>` — only shown without a validation error. |
| `required`  | bool          | `false`        | |
| `disabled`  | bool          | `false`        | |
| `icon`      | string\|null  | `null`         | An `<x-icon>` name, shown as a leading addon. Ignored when `inline`. |
| `inline`    | bool          | `false`        | Renders the calendar directly on the page instead of a popup-triggering input — see [Inline mode](#inline-mode). |
| `range`     | bool          | `false`        | Two-date range selection instead of a single date. |
| `format`    | string        | `YYYY-MM-DD`   | Litepicker's date format tokens (not PHP's) — passed to both the picker and the value written back to the field. |
| `minDate`   | string\|null  | `null`         | |
| `maxDate`   | string\|null  | `null`         | |
| `errorBag`  | string        | `default`      | Same as `<x-input>`. |

Any other attribute is merged onto the visible `<input>` (non-inline mode only).

## Inline mode

Litepicker needs a real container to render a calendar *into* — for the default (popup) mode that container is the `<input>` itself, but for `inline` there's no input for the picked date to live in. The component handles this: it renders a hidden `<input name="...">` alongside the calendar `<div>`, and `resources/js/datepicker.js` writes the picked date into that hidden input on selection. You still just read `request('starts_at')` server-side either way.

```blade
<x-datepicker name="event_date" inline />
```

## Variants

### With a leading icon

```blade
<x-datepicker name="starts_at" label="Start date" icon="calendar" />
```

### Date range

```blade
<x-datepicker name="stay_dates" label="Check-in / check-out" range />
```

### Min/max date, custom format

```blade
<x-datepicker name="dob" label="Date of birth" format="DD/MM/YYYY" :max-date="now()->format('Y-m-d')" />
```

## Architecture

Every other component in this library only wraps Tabler's CSS classes and markup — no new dependency, nothing to compile beyond what `@tabler/core` already provides. A datepicker needs actual calendar JS, so this one pulls in Litepicker (the same library Tabler's own admin template demos use) as an npm dependency:

- **`resources/js/datepicker.js`** imports `Litepicker` and its CSS, then auto-initializes every `[data-datepicker]` element on the page — reading its config from `data-datepicker-*` attributes rather than requiring a bespoke `<script>` block per field (Tabler's own demo copy-pastes a full init script per input; this component doesn't need that). It's imported once from `resources/js/app.js`, so it's part of the single app bundle — no extra `@vite()` entry needed.
- **`resources/css/app.scss`** imports `@tabler/core/scss/tabler-vendors`, which themes Litepicker (among other bundled third-party plugins) to match Tabler's colors on top of Litepicker's own CSS — see the comment above that import and [calendar.md](./calendar.md#architecture) for the same pattern applied to FullCalendar.
- **`postcss.config.js`** already excludes `--litepicker-*` custom properties from the `--tblr-` prefixing pass (set up when the project first moved to Vite — see the Sass/PostCSS notes in [card.md](./card.md) and `.ai/guidelines/project.md`), so Litepicker's own theme variables aren't touched.
- Month navigation buttons get `aria-label="Previous/Next month"` applied on every render — Litepicker's own `buttonText` option only accepts HTML for the button content, and an icon-only button with no label has no accessible name otherwise.

If you ever remove this component, `npm uninstall litepicker`, delete `resources/js/datepicker.js`, and drop the `import './datepicker'` line from `resources/js/app.js`.

## See it live

The Starter Kit page's "Datepicker" and "Inline datepicker" cards — verified interactively in a real browser (the popup calendar opens, the correct month/day renders, no console errors).
