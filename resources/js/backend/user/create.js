import "tom-select/dist/css/tom-select.css";
import "pretty-checkbox/dist/pretty-checkbox.min.css";

import TomSelect from "tom-select";

new TomSelect("#roles", {
    create: true,
    allowEmptyOption: true,
    plugins: {
        clear_button: {
            title: "Remove all selected options",
        },
        remove_button: {
            title: "Remove this item",
        },
    },
});

new TomSelect("#teams", {
    create: true,
    allowEmptyOption: true,
    plugins: {
        clear_button: {
            title: "Remove all selected options",
        },
        remove_button: {
            title: "Remove this item",
        },
    },
});

new TomSelect("#projects", {
    create: true,
    allowEmptyOption: true,
    plugins: {
        clear_button: {
            title: "Remove all selected options",
        },
        remove_button: {
            title: "Remove this item",
        },
    },
});
//
document.addEventListener("DOMContentLoaded", function () {
    // Initialize the pretty-checkbox
    document.querySelectorAll('input[type=radio][name="type_radio"]').forEach(radio => radio.addEventListener('change', () => {
        if (radio.value === 'local') {
            document.querySelectorAll('.localfield').forEach(element=>element.classList.remove('hidden'));
            document.querySelectorAll('.providerfield').forEach(element=>element.classList.add('hidden'));
        } else {
            document.querySelectorAll('.localfield').forEach(element=>element.classList.add('hidden'));
            document.querySelectorAll('.providerfield').forEach(element=>element.classList.remove('hidden'));
        }
    }));


});
