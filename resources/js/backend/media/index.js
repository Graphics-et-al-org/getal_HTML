import "tom-select/dist/css/tom-select.css";

import TomSelect from "tom-select";
import Swal from "sweetalert2";

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

window.copyText = (text) => {
    navigator.clipboard
        .writeText(text)
        .then(() => {
            Swal.fire({
                showClass: {
                    popup: "animate__animated animate__backInDown",
                },
                hideClass: {
                    popup: "animate__animated animate__backInUp",
                },
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 2000,
                icon: "success",
                title: "Copied!",
            });
        })
        .catch((err) => {
            Swal.fire({
                icon: "error",
                title: "Copy failed",
                text: "Failed to copy text to clipboard.",
            });
        });
};

window.deleteMedia = function (uuid) {
    console.log('deleting')
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('deleting', uuid)
            fetch(baseurl + "/admin/media/" + uuid + "/delete", {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            })
                .then((response) => response.json())
                .then((data) => {
                  
                    if (data.status=="success") {
                        Swal.fire("Deleted!", data.message, "success").then(
                            () => {
                                window.location.reload();
                            }
                        );
                    } else {
                        Swal.fire("Error!", data.message, "error");
                    }
                })
                .catch((error) => {
                    Swal.fire("Error!", "Failed to delete media.", "error");
                });
        }
    });
};
