# Project Rules Index

Before creating or editing files, read the rule file(s) whose glob matches the path being changed.

| Applies to | Rule file |
| --- | --- |
| `**` | `.ai/rules/general.md` |
| `app/**` | `.ai/rules/app.md` |
| `app/Actions/**` | `.ai/rules/actions.md` |
| `database/migrations/**` | `.ai/rules/migrations.md` |

Ported from `tadika` (the reference KMS project) at the user's request, minus
Livewire and `/impeccable` (not in use here yet) and its `resources.md` /
`views.md` — those assume a view directory layout (`views/dashboard/`,
`components/ui/`, `components/block/`, ...) that doesn't match this starter
kit's actual structure. See root `CLAUDE.md` → "Component library" / "Layouts
& pages" for the real conventions until a KMS-specific views rule is written.
