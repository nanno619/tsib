---
paths:
  - 'app/Actions/**'
---

# Actions

## Workflow transition actions
Status changes on `Child` / `LeaveApplication` / `Payslip` / `JobApplication`
go through Actions, never a raw `->update()` from a controller.

- Generic, side-effect-free transitions:
  `app/Actions/Workflow/{SubmitForReview, ReturnForCorrection, RejectSubmission}`.
  They accept any model implementing a shared `Workflowable` contract, read
  the destination from `$model->workflowTarget('submit'|'return'|'reject')`,
  stamp reviewer/timestamp/note columns, wrap in a transaction, and fire a
  `Submission*` event.
- Transitions with domain side effects get their own action:
  `ApproveChildRegistration` (assigns department + responsible teacher),
  `ApproveLeaveApplication` (guards + draws down `leave_balances` via
  `LeaveBalanceService`), `PublishPayslip` (renders + attaches the PDF, sets
  `notified_at`).
- To make a new model workflowable: `implements Workflowable`,
  `use HasWorkflow`, define `allowedTransitions()` and `workflowTargets()`;
  override the timestamp/note column hooks only if they differ from the
  defaults.
- Notifications are listeners on the events, not inline in the action.

Status columns are plain strings backed by a PHP enum per entity (see
`05-backend-schema.md` → Conventions) — not a native MySQL `ENUM` column.
