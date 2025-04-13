import "../common/tomselect_common";
import Swal from "sweetalert2";

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

window.confirmDelete = (id) => {
    Swal.fire({
        title: "Do you want to delete?",
        showDenyButton: true,
        icon: "warning",
        confirmButtonText: "Yes",
        denyButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.isConfirmed) {
                var url = baseurl + `/admin/template/${id}`;
                let formData = new FormData();
                formData.append("_method", "DELETE");
                fetch(url, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-CSRF-Token": csrfToken,
                    },
                })
                    .then((response) => response.json())
                    .then((response) => {
                       location.reload()
                    });
            }
        }
    });
};
