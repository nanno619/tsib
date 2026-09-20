# Modal

`<x-modal>` wraps Tabler's [Modal component](https://docs.tabler.io/ui/components/modal) (Bootstrap 5's modal JS). It covers the two shapes Tabler's own docs show: a standard header/body/footer dialog, and a centered "confirm" dialog (status strip, icon, centered text, two-button footer) for destructive actions.

Source: `resources/views/components/modal.blade.php`

There's no dedicated trigger component — `<x-button>` already passes through arbitrary attributes, so the trigger is just:

```blade
<x-button color="primary" data-bs-toggle="modal" data-bs-target="#modal-report">New report</x-button>
```

## Basic usage

```blade
<x-modal id="modal-report" title="New report">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" class="form-control" />
    </div>

    <x-slot:footer>
        <x-button variant="ghost" data-bs-dismiss="modal">Cancel</x-button>
        <x-button color="primary" class="ms-auto" data-bs-dismiss="modal">Create new report</x-button>
    </x-slot:footer>
</x-modal>
```

## Props

| Prop         | Type         | Default   | Description |
|--------------|--------------|-----------|--------------|
| `id`         | string       | —         | Required. Matches the trigger's `data-bs-target="#{id}"`. |
| `title`      | string\|null | `null`    | `default` variant: the header title. `confirm` variant: the centered heading. Omit for a titleless header (still shows the close button). |
| `size`       | string\|null | `null`    | `sm`, `lg`, or `xl` (`modal-{size}`). |
| `fullWidth`  | bool         | `false`   | `modal-full-width` — usually paired with `centered`. |
| `centered`   | bool         | `true`    | Vertically centres the dialog (`modal-dialog-centered`). On by default — Bootstrap's own default parks a dialog near the top of the viewport, which reads as unanchored on tall screens. Pass `:centered="false"` for that. |
| `scrollable` | bool         | `false`   | Long content scrolls inside the dialog instead of the page (`modal-dialog-scrollable`). |
| `static`     | bool         | `false`   | Clicking outside the dialog no longer dismisses it (`data-bs-backdrop="static"`). The Escape key still works — Tabler's own accessibility guidance is to never remove that. |
| `blur`       | bool         | `true`    | Blurred backdrop (`modal-blur`) — Tabler's default look. Set `false` for a plain dark overlay. |
| `variant`    | string       | `default` | `default` (header + body + footer) or `confirm` (status strip, icon, centered title/text, no header). |
| `status`     | string\|null | `null`    | `confirm` variant: color for the `modal-status` strip (e.g. `danger`, `success`). |
| `icon`       | string\|null | `null`    | `confirm` variant: an `<x-icon>` name shown above the title. |
| `iconColor`  | string\|null | `status`  | `confirm` variant: icon color, if it should differ from `status`. |

## Slots

| Slot      | Description |
|-----------|--------------|
| (default) | `default` variant: the `modal-body`. `confirm` variant: the message text under the title. |
| `footer`  | `default` variant: rendered as-is in `modal-footer`. `confirm` variant: rendered inside `modal-footer > .w-100 > .row` — wrap each button in its own `<div class="col">` (see below). |

## Variants

### Default (header + body + footer)

```blade
<x-modal id="modal-report" title="New report" size="lg">
    {{-- form fields --}}

    <x-slot:footer>
        <x-button variant="ghost" data-bs-dismiss="modal">Cancel</x-button>
        <x-button color="primary" icon="plus" class="ms-auto" data-bs-dismiss="modal">Create new report</x-button>
    </x-slot:footer>
</x-modal>
```

### Confirm (destructive action)

```blade
<x-modal id="modal-delete" variant="confirm" status="danger" icon="alert-triangle" title="Are you sure?" size="sm">
    Do you really want to remove this item? What you've done cannot be undone.

    <x-slot:footer>
        <div class="col">
            <x-button block data-bs-dismiss="modal">Cancel</x-button>
        </div>
        <div class="col">
            <x-button color="danger" block data-bs-dismiss="modal">Delete</x-button>
        </div>
    </x-slot:footer>
</x-modal>
```

### Centered, scrollable, static backdrop

```blade
<x-modal id="modal-terms" title="Terms of service" centered scrollable static>
    {{-- long content --}}
</x-modal>
```

## Accessibility

- The trigger must be a real `button` or `a href`, with `data-bs-toggle="modal"` and `data-bs-target="#{id}"` — `<x-button>` and `<x-dropdown-item>` both support this via plain attribute passthrough.
- `aria-labelledby` is set automatically whenever `title` is given, pointing at the title element's `id`.
- The close button always carries `aria-label="Close"`.
- Never nest modals — only one dialog open at a time.
- `static` only blocks outside-click dismissal; don't also add `data-bs-keyboard="false"` unless you have a very good reason — keep the Escape key working.

## See it live

The Starter Kit page's "Modal" card renders both variants: a form-style "New report" modal and a danger "confirm" delete modal.
