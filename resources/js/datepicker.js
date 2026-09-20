import { Litepicker } from 'litepicker';
import 'litepicker/dist/css/litepicker.css';

// Matches the chevron-left / chevron-right icons in <x-icon>, inlined here
// since Litepicker's buttonText wants raw HTML, not a Blade component.
const chevronLeft =
    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" class="icon"><path d="M15 6l-6 6l6 6" /></svg>';
const chevronRight =
    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" class="icon"><path d="M9 6l6 6l-6 6" /></svg>';

function initDatepicker(element) {
    const dataset = element.dataset;
    const format = dataset.datepickerFormat || 'YYYY-MM-DD';

    const picker = new Litepicker({
        element,
        format,
        singleMode: dataset.datepickerRange === undefined,
        inlineMode: dataset.datepickerInline !== undefined,
        minDate: dataset.datepickerMin || undefined,
        maxDate: dataset.datepickerMax || undefined,
        buttonText: {
            previousMonth: chevronLeft,
            nextMonth: chevronRight,
        },
    });

    // Inline mode has no <input> of its own — the calendar renders straight
    // into `element` — so the picked date is written to a paired hidden
    // input instead, for the value to actually reach the form submission.
    if (dataset.datepickerHiddenInput) {
        const hiddenInput = document.getElementById(dataset.datepickerHiddenInput);

        picker.on('selected', (date1, date2) => {
            hiddenInput.value = date2 ? `${date1.format(format)} - ${date2.format(format)}` : date1.format(format);
        });
    }

    // Litepicker sets buttonText as the nav buttons' innerHTML, and the icon
    // SVGs are aria-hidden, so the generated buttons otherwise have no
    // accessible name. It also re-renders them (new DOM nodes) on every open
    // and month change, so relabel on each "render" rather than once at init.
    const labelMonthButtons = () => {
        for (const button of document.querySelectorAll('.litepicker .button-previous-month')) {
            button.setAttribute('aria-label', 'Previous month');
        }
        for (const button of document.querySelectorAll('.litepicker .button-next-month')) {
            button.setAttribute('aria-label', 'Next month');
        }
    };
    picker.on('render', labelMonthButtons);
    labelMonthButtons();

    window.tabler_datepicker ??= {};
    window.tabler_datepicker[element.id] = picker;
}

function initAll() {
    document.querySelectorAll('[data-datepicker]').forEach(initDatepicker);
}

document.readyState !== 'loading' ? initAll() : document.addEventListener('DOMContentLoaded', initAll, { once: true });
