# Button

`<x-button>` wraps Tabler's [Button component](https://docs.tabler.io/ui/components/button). It renders either a `<button>` or an `<a>` (when you pass `href`), with color, size, and shape variants — and **icon support is the default way to use it** in this starter kit: pass `icon="..."` and it's placed correctly, including the accessibility bits for icon-only buttons.

Source: `resources/views/components/button.blade.php`

## Basic usage

```blade
<x-button color="primary" icon="device-floppy">Save changes</x-button>
```

```blade
{{-- as a link --}}
<x-button href="{{ route('dashboard') }}" color="primary" icon="home">Dashboard</x-button>
```

```blade
{{-- icon-only: pass no slot content, and always give it a label --}}
<x-button icon="plus" label="Add" />
```

## Props

| Prop           | Type          | Default   | Description |
|----------------|---------------|-----------|--------------|
| `color`        | string\|null  | `null`    | Any Tabler color or semantic alias: `primary`, `secondary`, `azure`, `success`, `danger`, ... `null` renders the plain default button. |
| `variant`      | string\|null  | `null`    | `outline` (`btn-outline-{color}`) or `ghost` (`btn-ghost-{color}`). Requires `color`. Omit for a solid button. |
| `size`         | string\|null  | `null`    | `sm`, `lg`, or `xl` (`btn-{size}`). Omit for the default size. |
| `icon`         | string\|null  | `null`    | An `<x-icon>` name. Placed before the label by default. If the slot is empty, the button becomes icon-only (`btn-icon`) automatically. |
| `iconPosition` | string        | `start`   | `start` or `end` (adds `icon-end` so it sits after the label). |
| `pill`         | bool          | `false`   | Fully rounded ends (`btn-pill`). |
| `square`       | bool          | `false`   | Square corners (`btn-square`). |
| `block`        | bool          | `false`   | Full width (`w-100`). |
| `loading`      | bool          | `false`   | Adds `btn-loading`, `aria-busy="true"`, and disables the button. |
| `disabled`     | bool          | `false`   | Disables a `<button>` natively, or adds `disabled`/`aria-disabled="true"`/`tabindex="-1"` to an `<a>` (links can't use the native `disabled` attribute). |
| `href`         | string\|null  | `null`    | Renders an `<a>` instead of a `<button>`. |
| `type`         | string        | `button`  | The `<button>` `type` attribute (`button`, `submit`, `reset`). Ignored when `href` is set. |
| `label`        | string\|null  | `null`    | Sets `aria-label`. **Required whenever the button is icon-only** — there's no visible text for assistive tech to read otherwise. |

Any other attribute (e.g. `class="mb-2"`, `wire:click="..."`) is merged onto the outer element.

## Variants

### Colors

```blade
<x-button color="primary">Primary</x-button>
<x-button color="secondary">Secondary</x-button>
<x-button color="azure">Azure</x-button>
```

### Outline / ghost

```blade
<x-button color="primary" variant="outline" icon="check">Outline</x-button>
<x-button color="primary" variant="ghost" icon="bell">Ghost</x-button>
```

### Icon before or after the label

```blade
<x-button color="primary" icon="device-floppy">Save</x-button>
<x-button color="primary" icon="chevron-right" icon-position="end">Next</x-button>
```

### Icon-only

Omit the slot content — `btn-icon` is added for you. Always pass `label`.

```blade
<x-button icon="plus" label="Add" />
<x-button color="primary" icon="settings" label="Settings" href="{{ route('settings.profile') }}" />
```

### Sizes

```blade
<x-button size="sm" color="primary">Small</x-button>
<x-button color="primary">Default</x-button>
<x-button size="lg" color="primary">Large</x-button>
<x-button size="xl" color="primary">Extra large</x-button>
```

### Pill / square

```blade
<x-button color="primary" pill>Pill</x-button>
<x-button color="primary" square icon="settings" label="Settings" />
```

### Full width

```blade
<x-button color="primary" icon="login" block>Sign in</x-button>
```

### Loading

```blade
<x-button color="primary" loading>Saving&hellip;</x-button>
```

### Disabled

```blade
<x-button disabled>Disabled</x-button>
<x-button href="#" disabled>Disabled link</x-button>
```

### As a submit button in a form

```blade
<form method="POST" action="{{ route('user-password.update') }}">
    @csrf
    @method('PUT')
    {{-- fields --}}
    <x-button type="submit" color="primary" icon="lock">Update password</x-button>
</form>
```

## Accessibility

- Icon-only buttons (`icon` set, no slot content) get `btn-icon` automatically — you're responsible for passing `label` so it gets an `aria-label`.
- `loading` sets `aria-busy="true"` and disables the button, matching Tabler's documented loading-button pattern.
- Disabled links (`href` + `disabled`) get `aria-disabled="true"` and `tabindex="-1"`, since `<a>` has no native `disabled` attribute.

## See it live

The Starter Kit page's "Buttons" card shows solid/outline/ghost/icon-only/loading/disabled together. Every auth form (login, register, forgot/reset password) and the Settings → Profile/Password forms use `<x-button>` for their submit buttons.
