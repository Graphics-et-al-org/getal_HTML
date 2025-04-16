import Sortable from "sortablejs";
import { Modal } from "flowbite";
import Swal from "sweetalert2";
import "tinymce/tinymce";
import "tinymce/skins/ui/oxide/skin.min.css";
import "tinymce/skins/content/default/content.min.css";
import "tinymce/skins/content/default/content.css";
import "tinymce/icons/default/icons";
import "tinymce/themes/silver/theme";
import "tinymce/models/dom/model";
import "tinymce/plugins/code";
import "tinymce/plugins/image";
import "tinymce/plugins/visualblocks";
import "tinymce/plugins/preview";
import "tinymce/plugins/media";
import "tinymce/plugins/fullscreen";

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// Rotating colours for backgrounds- @TODO update this to alight with some colour theory
const snippets_bg_colours = ["#fde8d4", "#ffd2e8", "#d6ecfd"];
var snippets_current_bg_colour = 0;

const keypointgrid = document.getElementsByClassName("keypoints").item(0);
const snippetsgrid = document.getElementsByClassName("snippets").item(0);

// get the layout for a keypoint
const keypointLayout = document.getElementsByClassName("keypoint").item(0);
const keypointLayoutClone = keypointLayout.cloneNode(true);

const deleteButtons = document.getElementsByClassName("deletebutton");

// title editing
tinymce.init({
    selector: "#title",
    toolbar: false,
    menubar: false,
    inline: true,
});
// summary editing
tinymce.init({
    selector: "#summary",
    toolbar: false,
    menubar: false,
    inline: true,
});

// keypoint editing
tinymce.init({
    selector: '[data-field="keypoint-text"]',
    toolbar: false,
    menubar: false,
    inline: true,
    setup: (editor) => {
        // });
        editor.on("focusin", (e) => {
            console.log("Editor was focusin.");
            const element = editor.getElement();

            const containerId = element.parentElement.id;
            console.log(
                element.closest("[data-keypoint_id]").dataset.keypoint_id
            );
            console.log(editor.getContent());
        });

        editor.on("focusout", (e) => {
            console.log("Editor was focusout.");
            const element = editor.getElement();

            // Or, if the element is inside a containing div and you want its id:
            const containerId = element.parentElement.id;
            console.log(
                element.closest("[data-keypoint_id]").dataset.keypoint_id
            );
            console.log(editor.getContent());
            updateKeypointText(
                element.closest("[data-keypoint_id]").dataset.keypoint_id,
                editor.getContent()
            );
        });
    },
});

document.addEventListener("DOMContentLoaded", function () {
    console.log("Document is ready");
    colourSnippetsBackground();
    // openPublicDetailsModal()
});

// delete keypoint
window.deleteKeypoint = (uuid) => {
    // some ui feedback
    showProcessFeedback();
    // send the signal to remove keypoint
    let url = baseurl + `/page/${uuid}/keypoint/${uuid}/remove`;
    fetch(url, {
        method: "GET",
        headers: {
            "X-CSRF-Token": csrfToken,
        },
    })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if (data.status == 0) {
                // remove the keypoint from the DOM
                showSuccessFeedback();
                document.getElementById(`keypoint_${uuid}`).remove();
            } else {
                // show error feedback
                showErrorFeedback();
            }
        });
};

// delete snippet
window.deleteSnippet = (uuid) => {};

// Enable Sorting
Sortable.create(keypointgrid, {
    animation: 150, // Smooth transition
    ghostClass: "bg-gray-300", // Class applied to the dragged item
    onEnd: function (evt) {
        console.log(
            "New Order:",
            Array.from(keypointgrid.children).map((el) => el.innerText)
        );
    },
    filter: "button", // Exclude buttons from being draggable
    preventOnFilter: false, // Ensure buttons remain clickable
});

Sortable.create(snippetsgrid, {
    animation: 150, // Smooth transition
    ghostClass: "bg-gray-300", // Class applied to the dragged item
    onEnd: function (evt) {
        console.log(
            "New Order:",
            Array.from(snippetsgrid.children).map((el) => el.innerText)
        );
        // Update the background colour of the snippets
        colourSnippetsBackground();
    },
    filter: "button", // Exclude buttons from being draggable
    preventOnFilter: false, // Ensure buttons remain clickable
});

// enable editing
// add a keypoint
// setups
// set the modal menu element
const $addKeypointTargetEl = document.getElementById("addKeypointModal");

// options with default values
const options = {
    backdrop: "dynamic",
    backdropClasses: "bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40",
    closable: true,
    onHide: () => {
        console.log("modal is hidden");
    },
    onShow: () => {
        console.log("modal is shown");
    },
    onToggle: () => {
        console.log("modal has been toggled");
    },
};

// instance options object
const addKeypointInstanceOptions = {
    id: "addKeypointModal",
    override: true,
};

const addKeypointModal = new Modal(
    $addKeypointTargetEl,
    options,
    addKeypointInstanceOptions
);

window.openAddKeypointModal = () => {
    addKeypointModal.show();
    // modal.classList.remove("hidden");
    // modal.classList.add("flex");
};
window.closeAddKeypointModal = () => {
    // reset the form
    document.getElementById("keypoint_image").classList.remove("hidden");
    document.getElementById("keypoint_image_waiting").classList.add("hidden");
    document.getElementById("keypoint_image").src =
        baseurl + "static/img/questionmark.svg";
    addKeypointModal.hide();
};

window.getKeypointIcon = () => {
    document.getElementById("keypoint_image").classList.add("hidden");
    document
        .getElementById("keypoint_image_waiting")
        .classList.remove("hidden");
    let url = baseurl + `/generate_keypoint_icon`;
    let formData = new FormData();
    formData.append("uuid", uuid);
    formData.append(
        "keypoint_text",
        document.getElementById("keypoint_text").value
    );
    fetch(url, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-Token": csrfToken,
        },
    })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if (data.best_image > 0) {
                used_images.push(data.best_image);
                document.getElementById("keypoint_image").src =
                    baseurl + "/clipart/" + data.best_image + "/baseline";
            }
            document
                .getElementById("keypoint_image")
                .classList.remove("hidden");
            document
                .getElementById("keypoint_image_waiting")
                .classList.add("hidden");
        });
};

window.addKeypoint = () => {
    // send the keypoint to the server
    var url = baseurl + `/page/${uuid}/add_keypoint`;
    let formData = new FormData();
    formData.append(
        "keypoint_text",
        document.getElementById("keypoint_text").value
    );
    formData.append("best_image", used_images[used_images.length - 1]);
    fetch(url, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-Token": csrfToken,
        },
    })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            console.log(
                keypointLayoutClone.querySelectorAll(
                    "[data-field='keypoint-text']"
                )
            );
            if (data.status == 0) {
                console.log(document.getElementById("keypoint_text").value);
                keypointLayoutClone.querySelectorAll(
                    "[data-field='keypoint-text']"
                )[0].innerText = document.getElementById("keypoint_text").value;
                keypointLayoutClone
                    .querySelectorAll("[data-field='keypoint-image'] img")
                    .forEach((img) => {
                        const container = img.closest(
                            '[data-field="keypoint-image"]'
                        );
                        img.src =
                            baseurl +
                            "/clipart/" +
                            used_images[used_images.length - 1] +
                            "/baseline";
                    });
                keypointgrid.insertBefore(
                    keypointLayoutClone.cloneNode(true),
                    document.getElementById("addKeypointButton")
                );
                window.closeAddKeypointModal();
            }
        });
};

const updateKeypointText = (id, content) => {
    var url = baseurl + `/page/${uuid}/update_keypoint/${id}`;
    let formData = new FormData();
    formData.append("keypoint_id", id);
    formData.append("content", content);
    // little bit of feedback goes here
    fetch(url, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-Token": csrfToken,
        },
    })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);

            if (data.status == 0) {
                // Success feedback goes here
                console.log(document.getElementById("keypoint_text").value);
                keypointLayoutClone.querySelectorAll(
                    "[data-field='keypoint-text']"
                )[0].innerText = document.getElementById("keypoint_text").value;
                keypointLayoutClone
                    .querySelectorAll("[data-field='keypoint-image'] img")
                    .forEach((img) => {
                        const container = img.closest(
                            '[data-field="keypoint-image"]'
                        );
                        img.src =
                            baseurl +
                            "/clipart/" +
                            used_images[used_images.length - 1] +
                            "/baseline";
                    });
                keypointgrid.insertBefore(
                    keypointLayoutClone.cloneNode(true),
                    document.getElementById("addKeypointButton")
                );
                window.closeAddKeypointModal();
            } else {
                // failurew goes here
            }
        });
};

// snippets modal
// set the modal menu element
const $addCollectionTargetEl = document.getElementById("addCollectionModal");

// instance options object
const addCollectionInstanceOptions = {
    id: "addCollectionModal",
    override: true,
};

const addCollectionModal = new Modal(
    $addCollectionTargetEl,
    options,
    addCollectionInstanceOptions
);

window.openAddCollectionModal = () => {
    addCollectionModal.show();
};
window.closeAddCollectionModal = () => {
    // reset the form
    // document.getElementById("keypoint_image").classList.remove("hidden");
    // document.getElementById("keypoint_image_waiting").classList.add("hidden");
    // document.getElementById("keypoint_image").src =
    //     baseurl + "static/img/questionmark.svg";
    addCollectionModal.hide();
};

var scheduled_function;
const delay_by_in_ms = 700;
var isSearching = false;
var info_categories = [];
var selected_info_categories = [];

// handle the text entry,
window.handleCollectionTextEntry = (e) => {
    if (typeof scheduled_function === "number") {
        clearTimeout(scheduled_function);
    }
    scheduled_function = setTimeout(
        getCollectionBySearch,
        delay_by_in_ms,
        0,
        true
    );
};

async function getCollectionBySearch() {
    if (document.getElementById("categorysearch").value.length > 1) {
        isSearching = true;
        let url =
            baseurl +
            `/categories/search?q=${
                document.getElementById("categorysearch").value
            }`;
        fetch(url, {
            method: "GET",
            headers: {
                "X-CSRF-Token": csrfToken,
            },
        })
            .then((response) => response.json())
            .then((data) => {
                console.log(data);
                // are any of these already selected?
                info_categories = info_categories.filter((item) =>
                    selected_info_categories.includes(item.uuid)
                );
                data.forEach((category) => {
                    // check if the category is already in the list
                    if (!document.getElementById(`cb_${category.uuid}`)) {
                        // add the category to the list
                        const newCategory = document.createElement("li");
                        newCategory.innerHTML = `
                          <input type="checkbox" id="cb_${category.uuid}" value="${category.uuid}" class="peer hidden"  onchange="window.handleInfoCategoryChange(event)" />
                          <label for="cb_${category.uuid}"
                            class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:text-gray-600 hover:bg-gray-100 peer-checked:text-blue-600">
                            <div class="w-full text-sm">${category.text}</div>
                          </label>
                        `;
                        document
                            .getElementById("categorieslist")
                            .appendChild(newCategory);
                    }
                });

                isSearching = false;
            });
        // static_components = res.data;
    }
}

// handle a selection of a static component
window.handleInfoCategoryChange = (e) => {
    //add to selected static components list
    console.log(e.target.value);
    if (e.target.checked) {
        if (!selected_info_categories.includes(e.target.value)) {
            selected_info_categories.push(e.target.value);
        }
    } else {
        selected_info_categories.includes(e.target.value) &&
            selected_info_categories.splice(
                selected_info_categories.indexOf(e.target.value),
                1
            );
    }
};

window.addSelectedCollections = () => {
    // get the selected categories from the backend as an html string, whack it on the end of the snippets grid
    // and then clear the selected categories list
    // and the search input
    console.log("selected_info_categories");
    console.log(selected_info_categories);
    let url = baseurl + `/categories/addfromuuids`;
    let formData = new FormData();
    formData.append("uuids", selected_info_categories);
    fetch(url, {
        method: "POST",
        body: formData,
        headers: {
            "X-CSRF-Token": csrfToken,
        },
    })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if (data.status == 0) {
                snippetsgrid.insertAdjacentHTML("beforeend", data.html);
                colourSnippetsBackground();
                addCollectionModal.hide();
            }
        });
};

const $publicDetailsTargetEl = document.getElementById("publicDetailsModal");

// instance options object
const showPublicDetailsOptions = {
    id: "addKeypointModal",
    override: true,
};

const addPublicDetailsModal = new Modal(
    $publicDetailsTargetEl,
    options,
    showPublicDetailsOptions
);

window.openPublicDetailsModal = () => {
    addPublicDetailsModal.show();
};

window.closePublicDetailsModal = () => {
    addPublicDetailsModal.hide();
};

// show a stern warning
window.showWarning = () => {
    Swal.fire({
        title: "Important notice",
        text: 'This tool is designed to support, not substitute, professional judgement. By selecting "Confirm and continue", you affirm that you have verified the accuracy and appropriateness of the information and diagrams provided',
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirm and continue",
    }).then((result) => {
        if (result.isConfirmed) {
            // send the signal to confirm
            let url = baseurl + `/page/${uuid}/approve`;
            fetch(url, {
                method: "GET",
                headers: {
                    "X-CSRF-Token": csrfToken,
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    console.log(data);
                    openPublicDetailsModal();
                });
        }
    });
};

// background colour the snipptets
const colourSnippetsBackground = () => {
    snippetsgrid
        .querySelectorAll(".component:not(.heading)")
        .forEach((snippet) => {
            snippet.style.backgroundColor =
                snippets_bg_colours[snippets_current_bg_colour];
            snippets_current_bg_colour =
                (snippets_current_bg_colour + 1) % snippets_bg_colours.length;
        });
};

const showProcessFeedback = () => {
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
        timer: 3000,
        icon: "info",
        title: "Processing...",
    });
};

const showSuccessFeedback = () => {
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
        timer: 3000,
        icon: "success",
        title: "Success!",
    });
};

const showErrorFeedback = () => {
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
        timer: 3000,
        icon: "error",
        title: "Error!",
    });
};
