# Tabler Laravel Starter Kit

A reusable Laravel 13 starter kit template (not a single-purpose app) built on the [Tabler](https://tabler.io) admin UI, meant to be cloned as the base for future projects.

## Stack

- **Auth**: Laravel Fortify — registration, password reset, profile update, password update enabled; 2FA and passkeys deliberately disabled (see `config/fortify.php`, commented out).
- **DB**: MySQL (`tabler_laravel_starter` locally, via Herd).
- **Timezone**: `APP_TIMEZONE` (default `Asia/Kuala_Lumpur`), read in
  `config/app.php`. Note this is **not display-only** — Laravel writes
  `created_at`/`updated_at` in the app timezone, so changing it on a database
  that already has rows leaves every old row offset by the difference. A fresh
  clone has no legacy rows and is unaffected. Verified: with the app in +08, a
  new row's raw `created_at` is local time, not UTC.
- **Frontend build**: Vite, compiling `@tabler/core`'s actual Sass/TS source (not precompiled CSS) — `resources/css/app.scss` and `resources/js/app.js`. This means Tabler's Sass variables (`$primary`, `$border-radius`, etc.) are overridable via `@use ... with (...)`, not just the runtime `data-bs-*` attributes.
  - `postcss-prefix-custom-properties` (see `postcss.config.js`) restores the `--tblr-` prefix on custom properties — required, since raw `@tabler/core` Sass emits them unprefixed.
  - `public/js/tabler-theme.js` is the one script **excluded from the Vite bundle on purpose**: it must run as a plain blocking `<script>` before first paint to avoid a light-theme flash; `@vite()` always emits `type="module"`, which the HTML spec defers until after parsing.
  - Fonts (Inter) are self-hosted via `laravel-vite-plugin/fonts`' `bunny()` helper — see the `@fonts` Blade directive next to `@vite()` in both layouts.
- **Static demo assets** (avatars, logo, favicon) live in `public/vendor/tabler/static/` — copied once from the purchased Tabler admin template, not part of the `@tabler/core` npm package, so not Vite-managed.

## Packages

Beyond Fortify, six packages are installed and wired into the app. Each one is
demonstrated live on `/starter-kit` unless noted.

- **spatie/laravel-permission** — `HasRoles` on `User`; `RolePermissionSeeder`
  creates an `admin` (view admin panel, manage users) and `editor` (view admin
  panel) role and assigns `admin` to the seeded `test@example.com`. Gates work
  through `@can` with no extra wiring. Permissions are seeded by name via
  `findOrCreate()`, so re-running the seeder is idempotent.
- **`SuperAdminSeeder` adds a `superadmin` role holding every permission**, plus
  one `superadmin@example.com` account to log in with (password `password` —
  it's a demo account, delete or re-password it before production). It runs
  after `RolePermissionSeeder`, since it grants whatever permission rows exist,
  and it syncs against the table rather than a hard-coded list so a permission
  added later is picked up by re-seeding. It grants the rows instead of
  installing the usual `Gate::before` super-admin bypass: a `Gate::before`
  short-circuits *before* the policy method runs, so it also skips
  `UserPolicy::delete()`'s self-guard — verified, a superadmin holding that
  bypass can delete their own account. `PermissionTest` pins both halves.
- **spatie/laravel-medialibrary** — `User` implements `HasMedia` with a
  single-file `avatar` collection. Uploads go through
  `Settings\UpdateAvatarController` (`POST /settings/avatar`) and render via
  `<x-avatar :src="$user->getFirstMediaUrl('avatar')">`, which falls back to
  initials when no avatar exists. **Requires `php artisan storage:link`** —
  included in the `composer setup` script.
- **spatie/laravel-activitylog** — `LogsActivity` on `User`, configured in
  `getActivitylogOptions()` to log only `name`/`email` when dirty. Read-only UI
  at `/settings/activity`. Note this is **v5**: the diff lives on the
  `attribute_changes` attribute (`{old, attributes}`), *not* the v4
  `properties` / `changes()` API — older examples online will not work.
- **spatie/laravel-backup** — `config/backup.php`; `backup:run` and
  `backup:clean` are scheduled daily in `routes/console.php`. Ships to the
  default `local` disk, so point it at an off-server disk before relying on it
  in production.
- **mpdf/mpdf** — `ExportUsersPdfController` (`GET /starter-kit/users.pdf`)
  renders `resources/views/pdf/users.blade.php`. The view is standalone inline
  HTML/CSS, not a Blade layout — mPDF has no browser engine and doesn't
  understand Tabler's stylesheet.
- **albertoarena/laravel-truss** (dev-only) — schema/DDL generation; not used by
  app code.

## Authorization

`App\Policies\UserPolicy` plus the `/admin/users` area (index / create / store /
show / edit / update / destroy) is the worked example, and the pattern to copy
for new models:

- **A policy answers "may this user do this to this model?"; spatie's
  permissions answer "does this user hold this right?"** — the policy is the
  per-model rule, the permission is how roles grant it.
- The policy calls **`checkPermissionTo()`, not `hasPermissionTo()`**. The
  latter throws `PermissionDoesNotExist` when the permission row is absent, so
  an unseeded database would 500 on every check instead of denying access.
- **Route enforcement uses Laravel 13's `#[Authorize]` attribute**
  (`Illuminate\Routing\Attributes\Controllers\Authorize`), which applies the
  `can` middleware per action. As an attribute it can't be dropped when the
  method body is edited, and it appears in `route:list`. Note the base
  controller is bare in Laravel 11+ — `AuthorizesRequests` is **not** included,
  so `$this->authorize()` is unavailable unless you add the trait.
- **`@can` in views is presentation, not the boundary.** It hides actions that
  would 403 anyway. `UserManagementTest` asserts the route refuses even when no
  button was rendered — a hidden button proves nothing.
- **Permission names live in `App\Enums\PermissionName`**, shared by the seeder
  and the policies. A rename on one side only leaves every policy silently
  denying, which reads as a permissions bug rather than a code one.
- A failing check throws 403 and renders `resources/views/errors/403.blade.php`
  — the themed error pages make that a legible outcome rather than a bare one.
- **Two self-guards, one reason.** You cannot delete yourself (policy) *or*
  change your own roles (controller). Either revokes your own access
  mid-session, and for a sole admin would lock everyone out of user management.
  The edit form hides the roles field for yourself, but as with the delete
  button that's explanation, not enforcement — the controller ignores the field
  regardless of what the request contains.
- **Validate role names with `Rule::exists` before `syncRoles()`.** It *creates*
  roles that don't exist, so an unvalidated `roles` field would let a request
  invent one. `UserEditingTest` and `UserCreationTest` cover this on both the
  update and create paths.
- **Account creation reuses Fortify's `PasswordValidationRules`**, so an admin
  can't set a password the user couldn't have registered with. The password is
  passed to `User::create()` in plain text — the model casts it as `hashed`, so
  hashing it again first would be redundant.
- **`/admin/users/create` is registered before `/admin/users/{user}`.** Swap
  them and "create" binds as an id, and the page 404s. There's a test for it.
- Table row actions are bare icons (`.action-icon` in `resources/css/app.scss`),
  not buttons — plain glyphs with a hover colour, and a red one for destructive
  actions.
- **Confirmation dialogs are one shared component**, not per-row markup:
  `<x-confirm-modal />` rendered once in `layouts/app`, plus
  `resources/js/confirm.js`. Any element with `data-confirm-delete` and
  `data-delete-url` gets the dialog and the request;
  `window.confirmAction({ …, onConfirm })` does the same from JS.
- **Bootstrap's `Modal` class is not importable here.** `@tabler/core` vendors
  Bootstrap into its own dist instead of depending on the npm package, so
  `import … from 'bootstrap'` would bundle a second, conflicting copy. The
  confirm module drives the modal through Bootstrap's data-API instead, by
  dispatching a click on a throwaway `[data-bs-toggle="modal"]` trigger.
- **Confirming submits the form for real** — no fetch, no JSON branch, no CSRF
  meta tag. The controller is a plain redirect + flash, and the confirmation is
  that flash rendered on the page the redirect lands on. An earlier iteration
  used fetch with a toast and a client-side row removal; it was collapsed
  because the reload did the work anyway and the client-side row count drifted
  from the server's the moment the first row went.
- **Every confirm form carries an inline `confirm()` as a no-JS fallback**, and
  `confirm.js` strips it on load so the native prompt and the modal never both
  appear. Removing it would mean a JS failure turns the trash icon into a
  one-click permanent delete with no confirmation at all.
- **Users soft-delete.** `SoftDeletes` on the model and `deleted_at` on the
  table, so a deleted user disappears from the list and can no longer log in
  without the row actually being destroyed.
- **There is currently no UI for deleted rows.** The trashed list, its
  `?trashed=1` view and the restore route were all removed deliberately —
  restoring is `$user->restore()` in tinker until a UI comes back. The
  `UserPolicy::restore()` and `forceDelete()` methods are still defined and
  currently unused, ready for it. `SoftDeleteTest` pins this state, including
  that the old `?trashed=1` URL no longer reveals anyone.
- **A deleted user's email stays reserved.** Laravel's `unique` rule queries
  through the query builder, which has no soft-delete scope, and `users.email`
  carries a real unique index — so a deleted row still holds its address, and
  re-registering with it fails validation. Freeing it would mean dropping the
  index (MySQL has no partial indexes), so it's kept deliberately and pinned by
  a test rather than left as a surprise.
- **The paginator needs `withQueryString()`**, or paging past page 1 silently
  drops the active filters and the sort.
- `<x-modal>` is vertically **centred by default**. Bootstrap's own default
  parks a dialog near the top of the viewport, which reads as unanchored on
  tall screens; pass `:centered="false"` for that.
- **Tooltips are attribute-only — no wrapper needed.** `@tabler/core`'s JS
  scans `[data-bs-toggle="tooltip"]` at page load and initialises each with
  `delay: {show: 50, hide: 50}`, reading `data-bs-placement` and `data-bs-html`
  off the element. So put the attributes straight on the `<a>`/`<button>`
  (every component here merges unrecognised attributes). The `aria-label` stays
  descriptive while the `title` is short: the tooltip is a visual hint for
  someone already looking at the row, and Bootstrap removes `title` while the
  tooltip shows, so the accessible name must not depend on it.

## Theme defaults

Set as `data-bs-*` attributes on `<html>` in `resources/views/components/layouts/{app,guest}.blade.php`: color scheme Auto, base Slate, accent Purple (`#ae3ec9`), corner radius 1, navbar layout (not sidebar) with sticky behavior.

## Component library

Reusable Blade components in `resources/views/components/*.blade.php` (`<x-alert>`, `<x-badge>`, `<x-avatar>`, `<x-button>`, `<x-breadcrumb>`, `<x-card>`, `<x-page-header>`, `<x-dropdown>` + `<x-dropdown-item>`, `<x-modal>`, `<x-offcanvas>`, `<x-segmented-control>`, `<x-list-group>` + `<x-list-group-item>`, `<x-pagination>`, `<x-table>`, `<x-progress>`, `<x-empty>`, `<x-tabs>`, `<x-spinner>`, `<x-status>`, `<x-toast>`, `<x-input>`, `<x-select>`, `<x-checkbox>`, `<x-tooltip>`, `<x-popover>`, `<x-placeholder>` + `<x-skeleton>`, `<x-ribbon>`, `<x-carousel>`, `<x-icon>`, `<x-brand>`), each wrapping real Tabler markup/classes — except `<x-datepicker>` (backed by the Litepicker npm package), `<x-select advanced>` (backed by Tom Select), and `<x-calendar>` (backed by FullCalendar); see [datepicker.md](../../docs/components/datepicker.md), [select.md](../../docs/components/select.md#architecture), and [calendar.md](../../docs/components/calendar.md#architecture). Theming for all three of these (plus other bundled third-party plugins like ApexCharts and Dropzone) comes from `@tabler/core/scss/tabler-vendors`, imported in `resources/css/app.scss`. **Read `docs/components/README.md` first** — it indexes a usage doc per component (props, variants, accessibility notes, Tabler docs link) in `docs/components/*.md`. Follow that pattern (wrap Tabler markup in a prop-driven component, document it, use it on the Starter Kit page) when adding new components.

Every form field in the app (auth pages, Settings) uses `<x-input>`/`<x-select>`/`<x-checkbox>` instead of raw `form-control` markup — they handle the label, `old()` repopulation, and error display for you. Use them for any new form field; see [input.md](../../docs/components/input.md).

**They read the error bag directly, not via `@error`** — `$errors->{$errorBag}->has($name)` and `->first($name)`, because the `errorBag` prop makes the bag a variable, and the same `$hasError` drives three things in one pass: the `is-invalid` class, the `invalid-feedback` line, and suppressing the `form-hint`. `@error` would mean re-entering the lookup for each, and `@error`'s second argument only takes a literal bag name.

**Every `<form>` in the app carries `novalidate`, including the ones with no fields.** HTML's constraint validation runs in the browser *before* submission, so a `required` field left empty never reaches Laravel — the browser shows its own unstyled bubble instead of the app's `invalid-feedback`. `required` stays on the inputs: it drives the label's asterisk and maps to `aria-required`, and the server rules mirror it, so nothing is relying on the browser as the guard. `FormValidationTest` counts `<form>` against `novalidate` per page, because no HTTP test can catch this — they post directly and skip the browser entirely.

**`<x-segmented-control>` has two modes, and the difference is load-bearing.** Give an item a `target` and it's a Bootstrap tab control; give it an `href` and it's navigation. Bootstrap's tab data-API calls `preventDefault()` on `<a>`, so the `data-bs-toggle="tab"` and the roving `tabindex="-1"` are emitted *only* in tab mode — emitting them on navigation links stopped the links navigating and left the inactive ones unreachable by keyboard. Use `href` for navigation, `target` for panes.

## Layouts & pages

- `<x-layouts.app>` (authenticated shell: navbar + page-wrapper + footer) and `<x-layouts.guest>` (centered auth-page shell) in `resources/views/components/layouts/`.
- Navbar/footer partials in `resources/views/layouts/partials/`.
- Every page renders its title bar via `<x-page-header>` (optionally with `breadcrumbs`), passed in the `header` slot.
- **A page with a single card needs no `row`/`col-*` wrapper.** `.row > *` already sets `width:100%`, `max-width:100%` and the matching half-gutter padding, so a lone `col-12` adds nothing at any breakpoint — and the row's negative inline margins are exactly cancelled by that padding, so removing both divs renders identically. Tabler's own doc examples wrap everything, which is where the habit comes from; only reach for them when there are genuinely two or more columns (`row row-cards` + `col-md-*`), as `admin/users/show` does.
- `/starter-kit` (`resources/views/starter-kit.blade.php`) is a living showcase of every component — extend it when adding a new one.
- Error pages live in `resources/views/errors/`: `403`, `404`, `419`, `429`, `500`, `503`, plus `4xx`/`5xx` wildcards that catch every other code. Each is a `<x-empty heading-level="h1">` on the guest layout. Illustrations are in `errors/illustrations/` — inline, not `<img>`, because they style themselves through `[data-bs-theme=dark]` and an `<img>`-loaded SVG can't see the page's theme attribute. They're copied from the purchased Tabler template; note that Tabler's own files also carry a `prefers-color-scheme` media query, which was stripped on extraction because this app themes by attribute only and the media query would override a manual light/dark choice.

## Tests

PHPUnit (not Pest — the project was scaffolded that way). Feature tests under
`tests/Feature/`, grouped by area:

- `Auth/AuthenticationTest` — login, logout, registration, password reset,
  profile/password updates, and that the protected pages redirect guests.
- `Settings/ActivityLogTest` — dirty-only logging, causer attribution, and the
  `attribute_changes` diff rendering.
- `Settings/AvatarUploadTest` — upload, validation failures, and single-file
  replacement (fakes the `public` disk).
- `PermissionTest` — the role/permission seeder (including idempotency) and the
  `@can` demo on `/starter-kit`.
- `PdfExportTest` — the mPDF export returns a real PDF.
- `StarterKitPageTest` — the showcase page, which renders every component at
  once, so it doubles as a smoke test for the whole library. Also covers its
  pagination.
- `Components/` — direct tests of each component's props and guaranteed markup,
  split into display, navigation and form groups. These render Blade with
  `$this->blade()` rather than going through HTTP, so a failure points at the
  component rather than a route. Components read validation state from the
  shared `$errors` bag, which normally arrives via middleware — `FormComponentsTest`
  shares a `ViewErrorBag` in `setUp` so they render standalone.

`tests/Unit/` is intentionally empty (kept via `.gitkeep`, since `phpunit.xml`
references the directory). Laravel's stock `ExampleTest` was removed — it only
asserted `true === true`, which PHPStan correctly flagged as always-true. Put
genuine pure-logic unit tests there once there are any.

Two gotchas worth knowing before adding more:

- `LogsActivity` records `created` as well as `updated`, so any
  `User::factory()->create()` writes an activity row. Scope activity assertions
  to `where('description', 'updated')` or they'll count the factory's insert.
- `assertSee()` escapes its search string by default, but **literal Blade
  template text is never HTML-encoded** — only `{{ }}` output is. Assertions
  against literal copy containing apostrophes need `assertSee($text, escape:
  false)`.

## Quality checks

```sh
vendor/bin/pint --test   # style
composer analyse         # static analysis (PHPStan + Larastan, level 8)
php artisan test         # tests
npm run build            # verify the Sass/JS still compiles after touching resources/css or resources/js
```

`.github/workflows/ci.yml` runs all three gates on every push to `main` and
every PR: `lint` (Pint), `static-analysis` (PHPStan) and `tests` (PHP 8.4 and
8.5). Constraints it encodes, all of which will bite if you reorder the steps:

- **`npm run build` must run before `php artisan test`.** Every page renders
  `@vite()`, which throws `ViteManifestNotFoundException` when `public/build` is
  absent — 8 tests fail on a fresh checkout without it. The PHPStan job does not
  need the build; it reads PHP and never renders.
- **PHP floor is 8.4.1, not 8.3.** Symfony 8 and spatie/laravel-activitylog
  require it. `composer.json`, the README, and the CI matrix all say so.

PHPStan configuration lives in `phpstan.neon`: level 8, no baseline, nothing
suppressed. Level 8 rather than 9 because the published
spatie/laravel-permission migration reads its config via `mixed`; editing it
would be discarded on republish, and excluding a vendor migration just to hit a
higher number isn't worth it. If level 8 becomes noisy, lower the level in
`phpstan.neon` rather than removing the job.

A JS linter is still not installed — low value here, since the only project JS
is three small init modules.
