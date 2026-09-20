# Checkbox

`<x-checkbox>` wraps Tabler's [Form elements component](https://docs.tabler.io/ui/components/form-elements) checkbox/switch pattern (`form-check`).

Source: `resources/views/components/checkbox.blade.php`

## Basic usage

```blade
<x-checkbox name="remember">Remember me on this device</x-checkbox>
```

## Props

| Prop       | Type   | Default | Description |
|------------|--------|---------|--------------|
| `name`     | string | —       | Required. |
| `value`    | string | `1`     | The submitted value when checked. |
| `checked`  | bool   | `false` | Whether the box starts checked. |
| `switch`   | bool   | `false` | Renders as a toggle switch (`form-switch`) instead of a checkbox. |
| `inline`   | bool   | `false` | `form-check-inline`, for a row of checkboxes. |
| `disabled` | bool   | `false` | |

Any other attribute (e.g. `wire:model`) is merged onto the actual `<input type="checkbox">`.

The slot is the label text.

## Variants

### Switch

```blade
<x-checkbox name="notifications" switch checked>Email notifications</x-checkbox>
```

### Inline group

```blade
<x-checkbox name="days[]" value="mon" inline>Mon</x-checkbox>
<x-checkbox name="days[]" value="tue" inline>Tue</x-checkbox>
```

## What this doesn't wrap

- **Radio buttons / radio groups** — a radio group needs a shared `name` across multiple inputs with different `value`s and a single "currently selected" concept, which doesn't fit this single-checkbox component. Write `<label class="form-check"><input type="radio" ...>` directly, per [Tabler's docs](https://docs.tabler.io/ui/components/form-elements#radios).

## See it live

`resources/views/auth/login.blade.php` ("Remember me") and the Starter Kit page's "Form elements" card (checkbox + switch).
