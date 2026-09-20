# Radio

`<x-radio>` wraps Tabler's [Form elements component](https://docs.tabler.io/ui/components/form-elements) radio pattern (`form-check`). Sibling of [`<x-checkbox>`](./checkbox.md) — same shape, `type="radio"` instead of `type="checkbox"`.

Source: `resources/views/components/radio.blade.php`

## Basic usage

A radio group is multiple `<x-radio>` sharing one `name`, each with a different `value` — the browser enforces "only one selected" for you.

```blade
<x-radio name="status" value="online" checked>Online</x-radio>
<x-radio name="status" value="under_maintenance">Under Maintenance</x-radio>
```

## Props

| Prop       | Type   | Default | Description |
|------------|--------|---------|--------------|
| `name`     | string | —       | Required. Shared across every radio in the same group. |
| `value`    | string | `1`     | The submitted value when this option is selected. |
| `checked`  | bool   | `false` | Whether this option starts selected. |
| `inline`   | bool   | `false` | `form-check-inline`, for a row of radios. |
| `disabled` | bool   | `false` | |

Any other attribute (e.g. `wire:model`) is merged onto the actual `<input type="radio">`.

The slot is the label text.

## Variants

### Inline group

```blade
<x-radio name="days" value="mon" inline>Mon</x-radio>
<x-radio name="days" value="tue" inline>Tue</x-radio>
```

## See it live

`resources/views/admin/system-setting/edit.blade.php` (Web App Status) and the Starter Kit page's "Form elements" card.
