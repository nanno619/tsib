# Input

`<x-input>` wraps Tabler's [Form elements component](https://docs.tabler.io/ui/components/form-elements) for text-like fields (including `textarea`). It's the highest-leverage component in this library — every form in the starter kit (login, register, forgot/reset password, Settings → Profile/Password) uses it — because it collapses the label + control + Laravel validation error boilerplate you'd otherwise repeat on every field.

Source: `resources/views/components/input.blade.php`

## Basic usage

```blade
<x-input name="email" type="email" label="Email address" placeholder="your@email.com" autocomplete="email" required />
```

This one line replaces the usual:

```blade
<div class="mb-3">
    <label class="form-label" for="email">Email address</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" value="{{ old('email') }}" placeholder="your@email.com" autocomplete="email" required />
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
```

## Props

| Prop        | Type          | Default   | Description |
|-------------|---------------|-----------|--------------|
| `name`      | string        | —         | Required. Used for `name`, `id`, `old($name, ...)`, and error lookup. |
| `label`     | string\|null  | `null`    | The `form-label`. Omit for a field with no visible label. |
| `type`      | string        | `text`    | Any input type (`email`, `password`, `number`, ...), or `textarea` for a `<textarea>`. |
| `value`     | mixed\|null   | `null`    | The fallback value, fed through `old($name, $value)`. **Never applied to `type="password"`** — password fields always render empty, even if `old('password')` has a (hashed-away) value. |
| `placeholder` | string\|null | `null`  | |
| `help`      | string\|null  | `null`    | Hint text below the field (`form-hint`). Only shown when there's no validation error — the error message takes its place. |
| `required`  | bool          | `false`   | Adds the `required` attribute and the red-asterisk `required` class on the label. |
| `disabled`  | bool          | `false`   | |
| `readonly`  | bool          | `false`   | |
| `size`      | string\|null  | `null`    | `sm` or `lg` (`form-control-{size}`). |
| `icon`      | string\|null  | `null`    | An `<x-icon>` name, shown as a leading addon (`input-icon`). |
| `errorBag`  | string        | `default` | Which Laravel error bag to check — `'default'` for a normal form, or a named bag like Fortify's `'updateProfileInformation'` / `'updatePassword'` for pages with multiple forms on one page. |

Any other attribute (`autocomplete`, `autofocus`, `min`, `max`, `step`, `wire:model`, `data-*`, ...) is merged onto the actual `<input>`/`<textarea>` — not a wrapper — so it behaves exactly like a plain HTML attribute would.

## Slots

| Slot         | Description |
|--------------|--------------|
| `labelAddon` | Extra content appended to the label (`form-label-description`) — e.g. a "forgot password?" link, or a character counter. |

## Variants

### With an icon

```blade
<x-input name="search" placeholder="Search…" icon="search" />
```

### With a label addon (e.g. a link)

```blade
<x-input type="password" name="password" label="Password" required>
    <x-slot:labelAddon>
        <a href="{{ route('password.request') }}">I forgot password</a>
    </x-slot:labelAddon>
</x-input>
```

### Named error bag (multiple forms on one page)

```blade
<x-input name="name" label="Name" :value="auth()->user()->name" error-bag="updateProfileInformation" required />
```

### Textarea

```blade
<x-input type="textarea" name="bio" label="Bio" help="Shown on your public profile." />
```

### Help text

Only rendered when the field has no validation error (the error message takes that spot instead):

```blade
<x-input type="email" name="email" label="Email address" help="We'll never share your email with anyone else." />
```

## Accessibility / correctness notes

- The `invalid-feedback` message and `form-hint` **must** stay inside the same inner wrapper as the control — Tabler shows/styles `.invalid-feedback` via the CSS sibling selector `.is-invalid ~ .invalid-feedback`, which wouldn't match if it lived outside that div. Don't "clean up" the markup by moving it out.
- `label`'s `for` attribute is always `$name`, matching the control's `id` — don't reuse the same `name` twice on one page.

## See it live

Every form in the app: `resources/views/auth/{login,register,forgot-password,reset-password}.blade.php` and `resources/views/settings/{profile,password}.blade.php`. The Starter Kit page's "Form elements" card shows the icon variant.
