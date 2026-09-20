import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.bootstrap5.css';

// Renders a Tabler-styled dropdown row. `data.customProperties` comes from
// each <option>'s data-custom-properties attribute (Tom Select copies every
// data-* attribute onto the option's data object automatically) — arbitrary
// HTML like an <x-avatar> or a flag, shown before the label. Falls back to
// plain text when an option has none.
function renderOption(data, escape) {
    if (data.customProperties) {
        return `<div class="dropdown-item"><span class="dropdown-item-indicator">${data.customProperties}</span>${escape(data.text)}</div>`;
    }

    return `<div>${escape(data.text)}</div>`;
}

function initSelect(element) {
    window.tabler_select ??= {};

    window.tabler_select[element.id] = new TomSelect(element, {
        copyClassesToDropdown: false,
        dropdownParent: 'body',
        placeholder: element.dataset.placeholder || undefined,
        render: {
            item: renderOption,
            option: renderOption,
        },
    });
}

function initAll() {
    document.querySelectorAll('[data-advanced-select]').forEach(initSelect);
}

document.readyState !== 'loading' ? initAll() : document.addEventListener('DOMContentLoaded', initAll, { once: true });
