---
paths:
  - 'database/migrations/**'
---

# Migrations

## Database indexing conventions
Single-centre scale (hundreds–thousands of rows/table). Base indexing is
enough; add indexes via a migration only when a real slow query appears —
do not pre-optimise, and don't add fulltext/exotic indexes speculatively.

- Do NOT add an explicit `->index()` for a plain foreign key:
  `foreignId()->constrained()` already creates the FK index in MySQL. Adding
  one is a redundant duplicate.
- DO index: workflow `status` columns; business/search columns that aren't
  unique (e.g. `ic_number` on `staff_profiles`/`children`/`child_guardians` —
  when it's also unique, that index covers the search too); date columns
  used for range filters.
- Composite indexes only for known hot query shapes, leftmost-column-first
  (e.g. `children (department_id, status)`,
  `leave_applications (staff_id, status)`,
  `payslips (staff_id, salary_month)` unique + standalone `salary_month`).
- N+1 is solved in code, not with indexes: `Model::shouldBeStrict()` outside
  production + eager loading in Actions (see `.ai/rules/app.md`). Package
  tables (`activity_log`, `media`, `permission_*`, `notifications`) bring
  their own indexes — leave them alone.

## Retrofitting a unique/not-null column onto a table with existing rows
Adding `->unique()` (or a `NOT NULL` column without a default) in one
`Schema::table()` call fails against existing rows — MySQL fills the
implicit default (e.g. `''` for every row), which collides on the unique
index. Pattern used for `users.ulid`:

1. Add the column `->nullable()` (`->unique()` alongside is fine — MySQL
   allows multiple `NULL`s in a unique index).
2. Backfill existing rows in a loop
   (`DB::table(...)->whereNull(...)->each(fn ($row) => ...)`).
3. Leave it nullable at the DB level; the model's `HasUlids` /
   creating-event guarantees every new row gets one going forward.

Full list of what's indexed and why: `05-backend-schema.md` → Conventions →
Indexing.
