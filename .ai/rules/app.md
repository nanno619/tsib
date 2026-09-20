---
paths:
  - 'app/**'
---

# App

## Hybrid Actions + Services architecture
Business logic uses a hybrid of Action classes and Service classes. Keep the
split disciplined:

- `app/Actions/` — one write use-case per class, one public method
  (`execute`/`handle`), wraps its own DB transaction, fires events.
  Controllers validate via a Form Request then call exactly one Action. E.g.
  `ApproveJobApplication`, `RegisterChild`, `SubmitForReview`,
  `ReturnForCorrection`, `ApproveLeaveApplication`, `SubmitPayslip`,
  `PublishPayslip`, `CarryForwardLeaveBalances`.
- `app/Services/` — stateless, reusable domain logic used by multiple
  Actions/tests; bound in the container; external-facing ones behind an
  interface. E.g. `LeaveDurationCalculator`, `LeaveBalanceService`,
  `StatutoryCalculator` → `MalaysianStatutoryCalculator`, `PayslipPdf` (mPDF
  wrapper).
- NOT services: reads/queries → model scopes or query objects;
  state-transition rules → a shared `Support/Workflow` trait + guards;
  validation → Form Requests.

Do not fold calculators into Actions, and do not put write use-cases in
Services. See `02-tech-design.md` §3 and `06-engineering-plan.md` at the repo
root.

## Query performance and container binding conventions

1. Call `Model::shouldBeStrict()` in `AppServiceProvider::boot()`, guarded to
   non-production. Lazy loading, missing attributes, and silently-discarded
   attributes must throw.
2. No global `$with` default eager loads on models. Load relations explicitly
   per query/Action so the cost is visible at the call site.
3. Bind domain service interfaces as singletons in a dedicated
   `DomainServiceProvider` (`StatutoryCalculator`, `LeaveDurationCalculator`,
   `PayslipPdf`, etc.) — they are stateless.
4. Eager loading is the Action's responsibility: an Action loads the
   relations it needs before returning models. Controllers must not trigger
   lazy loads in views.

Also follow superpowers-laravel: `performance-eager-loading`,
`performance-select-columns`, `data-chunking-large-datasets`,
`performance-caching`, `interfaces-and-di`.

## Enum casts need an explicit `@property` docblock

Larastan 3.12 doesn't reliably resolve a property's type from an enum cast
declared only inside the `casts(): array` method — `$model->status ===
SomeEnum::Case` gets flagged `identical.alwaysFalse` against `string`, even
though the cast is correct and the comparison works fine at runtime. Confirmed
with a minimal repro outside this app's own models, so it's a tool gap, not a
bug in any specific model.

Fix: add `@property EnumType $column` on the class docblock (immediately above
`#[Fillable]` is fine — a docblock isn't code, so it doesn't break the
attribute's binding to the class). Every model with an enum cast needs this:
`Child.status`, `ChildGuardian.type`, `JobApplication.status`,
`LeaveApplication.status`, `Payslip.status`, `Setting.web_app_status`. Add the
same line to any new model that casts a column to an enum.
