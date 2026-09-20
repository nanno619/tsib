# Tabler Laravel Starter Kit

[![CI](https://github.com/nanno619/tabler-laravel-starter/actions/workflows/ci.yml/badge.svg)](https://github.com/nanno619/tabler-laravel-starter/actions/workflows/ci.yml)

A Laravel 13 starter kit built on the [Tabler](https://tabler.io) admin UI — authentication, a themed app shell, and a small library of reusable Blade components. Meant to be cloned as the base for new projects, not run as-is.

- **Auth**: [Laravel Fortify](https://laravel.com/docs/fortify) — login, registration, password reset, profile update, password update.
- **UI**: [Tabler](https://tabler.io) (Bootstrap 5), compiled from `@tabler/core`'s Sass/JS source via Vite — not precompiled CSS, so the theme is customizable at the Sass level, not just via runtime attributes.
- **Components**: a documented library of 25+ Blade components (alerts, badges, avatars, buttons, cards, forms, dropdowns, modals, tables, tabs, and more) wrapping real Tabler markup — see [`docs/components/README.md`](docs/components/README.md).
- **Error pages**: Tabler-styled `403`, `404`, `419`, `429`, `500` and `503` screens (plus `4xx`/`5xx` fallbacks) with Tabler's illustrations — so nothing falls back to Laravel's unstyled defaults.
- **DB**: MySQL by default.

## Included packages

All six are installed, configured, and demonstrated on `/starter-kit` (or, for the
activity log, on `/settings/activity`):

| Package | What it powers |
|---|---|
| [spatie/laravel-permission](https://spatie.be/docs/laravel-permission) | Roles & permissions; an `admin`/`editor` starter set is seeded and gates work via `@can`. `App\Policies\UserPolicy` + the `/admin/users` area are a worked example of the whole pattern. |
| [spatie/laravel-medialibrary](https://spatie.be/docs/laravel-medialibrary) | Avatar uploads on Settings → Profile, rendered through `<x-avatar>`. |
| [spatie/laravel-activitylog](https://spatie.be/docs/laravel-activitylog) | A read-only "Recent activity" log at Settings → Activity, tracking `name`/`email` changes. |
| [spatie/laravel-backup](https://spatie.be/docs/laravel-backup) | `backup:run` / `backup:clean` scheduled daily — see `routes/console.php`. |
| [mpdf/mpdf](https://mpdf.github.io) | PDF export of the users table (`/starter-kit/users.pdf`) from a standalone print view. |
| [albertoarena/laravel-truss](https://github.com/albertoarena/laravel-truss) | Dev-only schema/DDL generation. |

## Requirements

- PHP 8.4.1+ (Symfony 8 and spatie/laravel-activitylog require it)
- Composer
- Node.js 20+ and npm
- MySQL (or adjust `.env` for another driver)

## Setup

```bash
git clone <this-repo> your-project-name
cd your-project-name

composer setup
```

`composer setup` runs everything needed for a first install: `composer install`, copies `.env.example` to `.env`, generates the app key, runs migrations, links `storage` (required for avatar uploads), and installs + builds the frontend (`npm install && npm run build`).

Then create the database it expects (defaults in `.env.example`: database `tabler_laravel_starter`, user `root`, no password — edit `.env` first if yours differs):

```bash
mysql -u root -e "CREATE DATABASE tabler_laravel_starter"
```

Seed a test user (`test@example.com` / `password`) plus 10 random users:

```bash
php artisan db:seed
```

Serve the app with `php artisan serve`, or point [Herd](https://herd.laravel.com) / your web server at `public/`.

## Using this as a template for a new project

A few things to change per-project after cloning:

- Your git identity, if the email you use elsewhere isn't the one on your GitHub account:
  ```bash
  git config user.email "you@example.com"
  ```
  GitHub links commits to accounts by author email, so a mismatch leaves every commit unattributed and the repo showing "No contributors". Repo-local config doesn't survive a clone — a fresh clone falls back to your global setting — so set this before your first commit.
- `APP_NAME`, `APP_URL`, and the DB name/credentials in `.env`.
- The CI badge at the top of this README. It's an absolute URL pointing at this
  template's own repository, so in a new project it reports *this* repo's status
  — green even when your CI is red. Point it at your repository or delete it.
- The CI branch trigger. `.github/workflows/ci.yml` runs on `main` only; a repo
  initialised with `master` will never run CI, silently and with no failure.
  Rename the branch or edit the trigger.
- The theme defaults (color scheme, accent color, base palette, corner radius, navbar behavior) — set as `data-bs-*` attributes on `<html>` in `resources/views/components/layouts/app.blade.php` and `layouts/guest.blade.php`.
- Delete `/starter-kit` (`resources/views/starter-kit.blade.php` and its route in `routes/web.php`) once you no longer need the live component reference. It's woven through the test suite, so remove those too: `tests/Feature/StarterKitPageTest.php` (delete outright), the starter-kit case in `PermissionTest`, `PdfExportTest` (the `/starter-kit/users.pdf` route and `ExportUsersPdfController`), and the `/starter-kit` entry in `AuthenticationTest`'s guest-redirect loop.

## Everyday commands

```bash
npm run dev              # Vite dev server (HMR) — pair with `php artisan serve`
composer dev              # serve + queue listener + logs + Vite dev server, all at once
npm run build             # production frontend build — required after editing resources/css or resources/js
vendor/bin/pint --test    # PHP code style check (drop --test to auto-fix)
composer analyse          # static analysis — PHPStan + Larastan, level 8
php artisan test          # test suite
```

## Component library

Every reusable Blade component wraps real Tabler markup behind a small set of props. The full list, with a usage doc per component (props, variants, accessibility notes), lives in [`docs/components/README.md`](docs/components/README.md) — that's the source of truth, not this file, so it doesn't go stale as components are added.

The `/starter-kit` page in the running app renders every component together as a live reference. When adding a new one, follow the same pattern: wrap Tabler's markup in a prop-driven component, document it in `docs/components/`, and add it to that page.

## Project-specific guidance for AI agents

This repo has [Laravel Boost](https://laravel.com/docs/boost) installed. Project-specific architecture notes (theme defaults, the Vite/Sass build, auth setup, component conventions) live in `.ai/guidelines/project.md`, which Boost folds into `CLAUDE.md`/`AGENTS.md` automatically — edit that file, not the generated ones, since `boost:install`/`boost:update` regenerate them.
