/**
 * FieldKonnect date picker.
 *
 * Every native <input type="date"> / <input type="datetime-local"> in the CRM
 * is turned into the themed flatpickr calendar (the browser's own popup can't
 * be styled). The input itself is kept, so its name, id, value format
 * (YYYY-MM-DD / YYYY-MM-DDTHH:MM), form submit, .val(), change events and
 * `required` keep working exactly as before.
 *
 * Opt out on a field with data-native-date.
 */
(function () {
    'use strict';

    if (!window.flatpickr) return;

    var SELECTOR = 'input[type="date"], input[type="datetime-local"]';

    function footer(fp, withTime) {
        var foot = document.createElement('div');
        foot.className = 'fk-fp-foot';
        foot.innerHTML = '<button type="button" data-fp="clear">Clear</button>'
            + '<button type="button" data-fp="today">' + (withTime ? 'Now' : 'Today') + '</button>';
        foot.addEventListener('click', function (event) {
            var button = event.target.closest('button');
            if (!button) return;
            if (button.getAttribute('data-fp') === 'clear') fp.clear();
            else fp.setDate(new Date(), true);
            fp.close();
        });
        fp.calendarContainer.appendChild(foot);
    }

    function enhance(input) {
        if (input._flatpickr || input.hasAttribute('data-fk-date') || input.hasAttribute('data-native-date')
            || input.classList.contains('hasDatepicker')) {
            return;
        }

        var withTime = input.type === 'datetime-local';
        var value = input.value;
        var format = withTime ? 'Y-m-d\\TH:i' : 'Y-m-d';

        input.setAttribute('data-fk-date', withTime ? 'datetime' : 'date');
        input.type = 'text';
        input.classList.add('fk-date-input');
        input.setAttribute('autocomplete', 'off');
        if (!input.getAttribute('placeholder')) {
            input.setAttribute('placeholder', withTime ? 'Select date & time' : 'Select date');
        }

        window.flatpickr(input, {
            dateFormat: format,
            enableTime: withTime,
            time_24hr: false,
            allowInput: true,          // keeps `required` validation (flatpickr would make it readonly)
            clickOpens: !input.readOnly,
            disableMobile: true,       // same calendar on phones
            monthSelectorType: 'static',
            defaultDate: value || null,
            minDate: input.getAttribute('min') || null,
            maxDate: input.getAttribute('max') || null,
            onReady: function (selected, str, fp) { footer(fp, withTime); },
            onOpen: function (selected, str, fp) {
                // Pages may change the value or min/max from code; pick that up.
                fp.set('minDate', input.getAttribute('min') || null);
                fp.set('maxDate', input.getAttribute('max') || null);
                var shown = fp.selectedDates[0] ? fp.formatDate(fp.selectedDates[0], format) : '';
                if (input.value !== shown) fp.setDate(input.value || null, false);
            }
        });
    }

    function scan(root) {
        if (!root || !root.querySelectorAll) return;
        if (root.matches && root.matches(SELECTOR)) enhance(root);
        root.querySelectorAll(SELECTOR).forEach(enhance);
    }

    function start() {
        scan(document.body);
        // Fields added later (modals, repeaters, AJAX-loaded forms).
        new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                mutation.addedNodes.forEach(function (node) {
                    if (node.nodeType === 1) scan(node);
                });
            });
        }).observe(document.body, { childList: true, subtree: true });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', start);
    else start();
})();
