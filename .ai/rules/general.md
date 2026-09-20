---
paths:
  - '**'
---

# General

## Prefer superpowers-laravel skills over Laravel Boost bundled skills
For Laravel work on the KMS build-out, use the `superpowers-laravel:*` skill
family (laravel-tdd, laravel-form-requests, laravel-check, laravel-policies,
migrations-and-factories, etc.) rather than Boost's bundled
`laravel-best-practices` skill/rules as primary guidance. Boost's
`testing-best-practices` stays in use (see below).

Reason: ported from `tadika` (the reference KMS project) at the user's
request — same team standardizing on one skill family.

## Testing: PHPUnit, not Pest
This project was scaffolded with PHPUnit (`.ai/guidelines/project.md`), not
Pest — unlike `tadika`, which uses Pest. Keep writing PHPUnit-style test
classes (`php artisan make:test --phpunit`, `php artisan test`/`vendor/bin/phpunit`).
Don't introduce Pest here.

## JS package manager: npm, not Bun
This project uses npm (`package-lock.json`, `npm run build` / `npm run dev`),
not Bun. `tadika`'s Bun-specific rule does not carry over.

## No Livewire, no impeccable — yet
Per explicit instruction, skip Livewire and the `/impeccable:impeccable`
skill for now. Build views as plain Blade using this project's existing
`<x-*>` Tabler component library (see root `CLAUDE.md` and
`docs/components/*.md`). Revisit only if the user asks for Livewire.

## Always use the commit skill for git commits
Every git commit should be created via the `commit` skill (Conventional
Commits: `type(scope): description`), not a free-form `git commit -m`.
