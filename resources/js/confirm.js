/**
 * Shared confirmation dialog, and the delete flow built on it.
 *
 * Forms opt in with data attributes:
 *   <form method="POST" action="…" data-confirm-delete
 *         data-confirm-title="Delete user" data-confirm-message="…">
 *
 * From JS:
 *   window.confirmAction({ title, message, confirmText, cancelText, onConfirm })
 *
 * Confirming submits the form for real, so the controller stays a plain
 * redirect + flash. Nothing here needs fetch, a CSRF meta tag or a JSON branch.
 *
 * Bootstrap's Modal class is not importable here: @tabler/core vendors Bootstrap
 * into its own dist instead of depending on the npm package, so `import …
 * from 'bootstrap'` would bundle a second, conflicting copy. Everything below
 * drives the modal through Bootstrap's own data-API instead — the same path the
 * markup takes — by clicking a throwaway [data-bs-toggle="modal"] trigger.
 */

const MODAL_ID = 'confirm-modal';

let pending = null;

function modalElement() {
    return document.getElementById(MODAL_ID);
}

function open(el) {
    const trigger = document.createElement('button');
    trigger.type = 'button';
    trigger.hidden = true;
    trigger.setAttribute('data-bs-toggle', 'modal');
    trigger.setAttribute('data-bs-target', `#${el.id}`);

    document.body.appendChild(trigger);
    trigger.click();
    trigger.remove();
}

function close(el) {
    el.querySelector('[data-bs-dismiss="modal"]')?.click();
}

function setText(el, selector, value) {
    const target = el.querySelector(selector);

    if (target && value) {
        target.textContent = value;
    }
}

/**
 * Open the shared dialog. Returns false when the layout rendered no modal, so
 * a caller can fall back to its own confirmation.
 */
export function confirmAction({ title, message, confirmText, cancelText, onConfirm } = {}) {
    const el = modalElement();

    if (!el) {
        return false;
    }

    setText(el, `#${el.id}-title`, title);
    setText(el, `#${el.id}-message`, message);
    setText(el, `#${el.id}-confirm`, confirmText);
    setText(el, `#${el.id}-cancel`, cancelText);

    pending = onConfirm ?? null;
    open(el);

    return true;
}

function init() {
    const el = modalElement();

    el?.querySelector(`#${el.id}-confirm`)?.addEventListener('click', () => {
        const action = pending;
        pending = null;
        close(el);
        action?.();
    });

    // Each form carries an inline confirm() as a no-JS fallback. Drop it now
    // that this module handles the submit, or the browser would prompt with a
    // native dialog and then the modal straight after.
    document.querySelectorAll('form[data-confirm-delete][onsubmit]').forEach((form) => {
        form.removeAttribute('onsubmit');
    });

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('form[data-confirm-delete]');

        if (!form) {
            return;
        }

        event.preventDefault();

        confirmAction({
            title: form.dataset.confirmTitle ?? 'Delete record',
            message: form.dataset.confirmMessage ?? 'Are you sure? This cannot be undone.',
            confirmText: form.dataset.confirmText ?? 'Delete',
            cancelText: form.dataset.cancelText ?? 'Cancel',
            // submit(), not requestSubmit(): the DOM method doesn't fire a
            // submit event, so it can't be intercepted a second time.
            onConfirm: () => form.submit(),
        });
    });
}

init();

// Exposed for inline handlers and anywhere else that needs a confirmation.
window.confirmAction = confirmAction;
