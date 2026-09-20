import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

// No separate CSS import: FullCalendar injects its own styles into a
// <style data-fullcalendar> tag at render time. Tabler's colors/spacing
// come from @tabler/core/scss/tabler-vendors (see resources/css/app.scss),
// which overrides FullCalendar's own --fc-* custom properties and a few
// selectors (.fc-button, .fc-toolbar-title, ...) to match the rest of the app.

function initCalendar(element) {
    const dataset = element.dataset;

    const events = dataset.calendarEvents ? JSON.parse(dataset.calendarEvents) : [];

    // FullCalendar's `height` option wants a number (pixels) or one of a few
    // keyword strings ('auto', 'parent') — a numeric *string* like "600"
    // (all data-* attributes are strings) is silently misread and the
    // calendar collapses to its toolbar's height only. Coerce numeric-looking
    // values to a real number; anything else passes through as-is.
    const heightAttr = dataset.calendarHeight;
    const height = heightAttr && ! Number.isNaN(Number(heightAttr)) ? Number(heightAttr) : heightAttr || 'auto';

    window.tabler_calendar ??= {};

    window.tabler_calendar[element.id] = new Calendar(element, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
        initialView: dataset.calendarView || 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek',
        },
        height,
        events,
        selectable: dataset.calendarSelectable !== undefined,
    });

    window.tabler_calendar[element.id].render();
}

function initAll() {
    document.querySelectorAll('[data-calendar]').forEach(initCalendar);
}

document.readyState !== 'loading' ? initAll() : document.addEventListener('DOMContentLoaded', initAll, { once: true });
