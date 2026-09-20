// Bootstrap's JS bundle plus Tabler's own behaviors (sidebar, dropdown,
// tooltip, popover, etc). Safe to load deferred — see
// public/js/tabler-theme.js for the one script that can't be.
import '@tabler/core/js/tabler';

// Auto-initializes every <x-datepicker> on the page — see
// docs/components/datepicker.md.
import './datepicker';

// Auto-initializes every <x-select advanced> on the page — see
// docs/components/select.md.
import './select';

// Auto-initializes every <x-calendar> on the page — see
// docs/components/calendar.md.
import './calendar';

// The shared confirmation dialog, plus the [data-confirm-delete] delete flow
// built on it. Needs <x-confirm-modal /> in the layout — see
// resources/views/components/confirm-modal.blade.php.
import './confirm';
