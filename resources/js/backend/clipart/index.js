import "tom-select/dist/css/tom-select.css";

import TomSelect from "tom-select";
import Swal from 'sweetalert2';

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

new TomSelect("#tags", {
    create: false,
    options: tags,
    preload: true,
    valueField: "value",
    items: tags.map((item) => {
        return item.value;
    }),
    load: function (query, callback) {
        var url = baseurl + "/api/tags?q=" + query;
        fetch(url)
            .then((response) => response.json())
            .then((json) => {
                //console.log(callback);
                callback(json);
            })
            .catch(() => {
                callback();
            });
    },
    plugins: {
        clear_button: {
            title: "Remove all selected options",
        },
        remove_button: {
            title: "Remove this item",
        },
    },
});

window.confirmRefreshMetadata = () => {
    Swal.fire({
        title: "Refresh all AI metadata? This will take a long time",
        showCancelButton: true,
        confirmButtonText: "Yes",
        customClass: {
            actions: "my-actions",
            cancelButton: "order-1 right-gap",
            confirmButton: "order-2",
        },
    }).then((result) => {
        if (result.isConfirmed) {
            var url = baseurl + "/admin/clipart/refreshallaimetadata"
            fetch(url, {
                method: 'POST',

                headers: {
                    "X-CSRF-Token": csrfToken
                  }})
                .then((response) => response.json())
                .then((json) => {
                    console.log(json);
                    Swal.fire("Submitted!", "", "success");
                })
                .catch(() => {

                });
        }
    });
};
