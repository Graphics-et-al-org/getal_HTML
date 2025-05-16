import Sortable from "sortablejs";
import { Modal } from "flowbite";
import Swal from "sweetalert2";
import tippy from "tippy.js";
import "tippy.js/dist/tippy.css";
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

// document initialisation
document.addEventListener("DOMContentLoaded", function () {
    console.log("Document is ready");
    activateKeypointTinyMCE();
    // colour the snippets background
    colourSnippetsBackground();
    // implement tooltips
    tippy("[data-tippy-content]");
    // openPublicDetailsModal()
});

// Laravel security token for AJAX calls
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

// Rotating colours for backgrounds- @TODO update this to alight with some colour theory
const snippets_bg_colours = ["#fde8d4", "#ffd2e8", "#d6ecfd"];
var snippets_current_bg_colour = 0;

const keypointgrid = document.getElementsByClassName("keypoints").item(0);
const snippetsgrid = document.getElementsByClassName("snippets").item(0);

// get the layout for a keypoint
const keypointLayout = document
    .getElementsByClassName("keypoint-container")
    .item(0);
const keypointLayoutClone = keypointLayout.cloneNode(true);

const deleteButtons = document.getElementsByClassName("deletebutton");

// title editing
tinymce.init({
    selector: "#title",
    toolbar: false,
    menubar: false,
    inline: true,
    setup: (editor) => {
        // handle change event
        editor.on("change", (e) => {
            let editorContent = editor.getContent();
            if (editorContent.length > 0) {
                var url = baseurl + `/page/${uuid}/summary_update`;
                let formData = new FormData();
                //formData.append("keypointid", id);
                formData.append("keypoint_text", content);
                //console.log(id, content);
                //  return;
                // little bit of feedback goes here
                container
                    .querySelector(".keypoint_image_waiting")
                    .classList.remove("hidden");
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
                            container
                                .querySelector(".keypoint_image_waiting")
                                .classList.add("hidden");
                        } else {
                            container
                                .querySelector(".keypoint_image_waiting")
                                .classList.add("hidden");
                            showErrorFeedback();
                        }
                    })
                    .catch((error) => {
                        showErrorFeedback();
                        container
                            .querySelector(".keypoint_image_waiting")
                            .classList.add("hidden");
                    });
            }
        });
    },
});
// summary editing
tinymce.init({
    selector: "#summary",
    toolbar: false,
    menubar: false,
    inline: true,
});

// keypoint editing
const activateKeypointTinyMCE = (element) => {
    tinymce.init({
        selector: '[data-field="keypoint-text"]',
        toolbar: false,
        menubar: false,
        inline: true,
        placeholder: "Type here...",
        setup: (editor) => {
            setupKeypointTinyMCE(editor);
        },
    });
};

// setup tinymce inline editing
const setupKeypointTinyMCE = (editor) => {
    // });
    editor.on("focusin", (e) => {
        console.log("Editor was focusin.");
        const element = editor.getElement();

        const containerId = element.parentElement.id;
        console.log(element.closest("[data-keypointid]").dataset.keypoint_id);
        if (editor.getContent() == "<p>Click to edit</p>") {
            editor.setContent("");
        }
        console.log(editor.getContent());
    });

    editor.on("input", (e) => {
        //  console.log("Editor was input.");
        let container = editor.getElement().closest(".keypoint-container");
        // console.log(container.classList.contains("new"))
        // console.log(editor.getContent().length > 10)
        if (
            editor.getContent().length > 10 &&
            container.classList.contains("new")
        ) {
            // start feedbck
            //console.log(e.target.dataset)
            // console.log(Object.is(container, null))
            container
                .querySelector(".get-keypoint-image-btn")
                .classList.remove("hidden");
        } else {
            container
                .querySelector(".get-keypoint-image-btn")
                .classList.add("hidden");
        }
    });

    // handle change event
    editor.on("change", (e) => {
        console.log("Editor was change.");
        console.log(e.target.composing);
        console.log(editor.getElement().closest(".keypoint-container"));

        let container = editor.getElement().closest(".keypoint-container");
        console.log(container);
        if (
            editor.getContent().length > 10 &&
            container.classList.contains("new")
        ) {
            // start feedbck
            //console.log(e.target.dataset)
            // console.log(Object.is(container, null))
            container
                .querySelector(".get-keypoint-image-btn")
                .classList.add("hidden");
        } else {
            container
                .querySelector(".get-keypoint-image-btn")
                .classList.add("hidden");
        }

        let editorContent = editor.getContent();
        if (editorContent.length > 0) {
            // console.log(element.closest("[data-keypointid]"))
            if (!container.classList.contains("new")) {
                updateKeypointText(
                    container.dataset.keypointuuid,
                    editor.getContent(),
                    container
                );
            }
        }
    });

    editor.on("focusout", (e) => {
        console.log("Editor was focusout.");
        const element = editor.getElement();
        // Or, if the element is inside a containing div and you want its id:
        // const containerId = element.parentElement.id;
        //console.log(element.closest("[data-keypointid]").dataset.keypointid);

        var editorContent = editor.getContent();
        if (editorContent == "") {
            editor.setContent("<p>Click to edit</p>");
        }
    });
    // },
};

// // delete snippet
// window.deleteSnippet = (uuid) => {};

// Enable Sorting
Sortable.create(keypointgrid, {
    animation: 150, // Smooth transition
    ghostClass: "bg-gray-300", // Class applied to the dragged item
    onEnd: function (evt) {
        let newOrder = Array.from(keypointgrid.children).map(
            (el) => el.dataset.keypointid
        );
        showProcessFeedback();
        // submit the changes
        let url = baseurl + `/keypoint/reorder`;
        let formData = new FormData();
        formData.append("order", newOrder);
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
                showSuccessFeedback();
            })
            .catch(() => {
                showErrorFeedback();
            });
    },
    filter: "button, div.addbutton", // Exclude buttons and the 'add keypoint from being draggable
    preventOnFilter: false, // Ensure buttons remain clickable
});

Sortable.create(snippetsgrid, {
    animation: 150, // Smooth transition
    ghostClass: "bg-gray-300", // Class applied to the dragged item
    onEnd: function (evt) {
        // do not include
        let newOrder = Array.from(snippetsgrid.children).map(
            (el) => el.dataset.snippetid
        );
        showProcessFeedback();
        // submit the changes
        let url = baseurl + `/keypoint/reorder`;
        let formData = new FormData();
        formData.append("order", newOrder);
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
                showSuccessFeedback();
                colourSnippetsBackground();
            })
            .catch(() => {
                showErrorFeedback();
            });
        // Update the background colour of the snippets
    },
    filter: "button", // Exclude buttons from being draggable
    preventOnFilter: false, // Ensure buttons remain clickable
});

// enable editing
// add a keypoint
// setups
// set the modal menu element
//const $addKeypointTargetEl = document.getElementById("addKeypointModal");

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

// Add a keypoint div
window.addKeypoint = () => {
    console.log("adding keypoint");
    // make some random ID
    let _id = `keypoint_${crypto.randomUUID()}`;

    // get a keypoint template based on the keypoint layout
    let keypointTemplate = keypointLayoutClone.cloneNode(true);
    // Get the container to which you want to add the cloned node
    // Append the cloned node to the container
    keypointgrid.insertBefore(keypointTemplate, keypointgrid.lastElementChild);
    // clean up some parameters
    // update the element.closest("[data-keypoint_id]").dataset.keypoint_id
    keypointTemplate.closest("[data-keypointid]").dataset.keypointid = -1;
    // set new temp id
    keypointTemplate.setAttribute("id", _id);
    // flag it as new
    keypointTemplate.classList.add("new");
    // reset the text
    keypointTemplate.querySelector('[data-field="keypoint-text"]').innerText =
        "Click to edit";
    // add some identifier to the fields
    keypointTemplate.querySelector(
        '[data-field="keypoint-text"]'
    ).dataset.keypointid = _id;

    // reset the image
    keypointTemplate.querySelector('[data-field="keypoint-image"] img').src =
        baseurl + "/static/img/questionmark.svg";
    // add some identifier to
    // keypointTemplate
    //     .querySelector('[data-field="keypoint-image"] img')
    //     .setAttribute("data-keypointid", _id);
    // show the new keypoint button
    console.log(keypointTemplate.querySelector(".get-keypoint-image-btn"));
    // keypointTemplate
    //     .querySelector(".get-keypoint-image-btn")
    //     .classList.remove("hidden");

    keypointTemplate.querySelector(".get-keypoint-image-btn").dataset.target =
        _id;
    // kick tippy to enable the tooltips
    tippy("[data-tippy-content]");
    // activate the tinymce editor
    activateKeypointTinyMCE(
        keypointTemplate.querySelector('[data-field="keypoint-text"]')
    );
};

// window.closeAddKeypointModal = () => {
//     // reset the form
//     document.getElementById("keypoint_image").classList.remove("hidden");
//     document.getElementById("keypoint_image_waiting").classList.add("hidden");
//     document.getElementById("keypoint_image").src =
//         baseurl + "static/img/questionmark.svg";
//     addKeypointModal.hide();
// };

// get the keypoint icon
window.getKeypointIcon = (e) => {
    // get the text
    let container = document.getElementById(e.target.dataset.target);
    // start feedbck
    container
        .querySelector(".keypoint_image_waiting")
        .classList.remove("hidden");

    container.querySelector(".get-keypoint-image-btn").classList.add("hidden");
    // get the text
    let text = container.querySelector(
        '[data-field="keypoint-text"]'
    ).innerText;
    console.log(text);

    let url = baseurl + `/generate_keypoint_icon`;
    let formData = new FormData();
    formData.append("uuid", uuid);
    formData.append("keypoint_text", text);
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
            if (data.colourway_uuid.length > 0) {
                container.querySelector(
                    '[data-field="keypoint-image"] img'
                ).src = baseurl + "/colourway/" + data.colourway_uuid;
                // make the keypoint a real thing
                addKeypointToPage(text, data.colourway_uuid, container);
            } else {
                showIconNotFoundFeedback();
                container
                    .querySelector(".get-keypoint-image-btn")
                    .classList.remove("hidden");
            }
        })
        .catch((error) => {
            showErrorFeedback();
            container
                .querySelector(".get-keypoint-image-btn")
                .classList.remove("hidden");
        })
        .finally(() => {
            container
                .querySelector(".keypoint_image_waiting")
                .classList.add("hidden");
        });
};

const addKeypointToPage = (text, image_uuid, container) => {
    console.log("addKeypointToPage happening");
    //send the keypoint to the server
    var url = baseurl + `/page/${uuid}/add_keypoint`;
    let formData = new FormData();
    formData.append("keypoint_text", text);
    formData.append("image_uuid", image_uuid);
    formData.append("component", container.dataset.componentid);
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
                // remove new
                container.classList.remove("new");
                // fix up the various parameters
                container.dataset.keypointuuid = data.uuid;
                //container.setAttribute("id", data.id);
                // add some identifier to the fields
                container.querySelector(
                    '[data-field="keypoint-text"]'
                ).dataset.keypointid = data.id;
            }
        })
        .catch((error) => {
            showErrorFeedback();
            container
                .querySelector(".get-keypoint-image-btn")
                .classList.remove("hidden");
        });
};

// delete keypoint
window.deleteKeypoint = (e) => {
    console.log("delete keypoint");
    console.log(e);
    console.log(e.currentTarget.closest(".keypoint-container"));
    let container = e.currentTarget.closest(".keypoint-container");

    console.log(container.classList.contains("new"));
    //return;
    if (container.dataset.keypointid > 0) {
        // some ui feedback
        showProcessFeedback();

        // send the signal to remove keypoint
        let url =
            baseurl + `/keypoint/${container.dataset.keypointuuid}/remove`;
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
                    container.remove();
                } else {
                    // show error feedback
                    showErrorFeedback();
                }
            })
            .catch((error) => {
                showErrorFeedback();
            });
    } else {
        container.remove();
    }
};

const updateKeypointText = (keypointuuid, content, container) => {
    var url = baseurl + `/keypoint/${keypointuuid}/update`;
    let formData = new FormData();
    //formData.append("keypointid", id);
    formData.append("keypoint_text", content);
    //console.log(id, content);
    //  return;
    // little bit of feedback goes here
    container
        .querySelector(".keypoint_image_waiting")
        .classList.remove("hidden");
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
                container
                    .querySelector(".keypoint_image_waiting")
                    .classList.add("hidden");
            } else {
                container
                    .querySelector(".keypoint_image_waiting")
                    .classList.add("hidden");
                showErrorFeedback();
            }
        })
        .catch((error) => {
            showErrorFeedback();
            container
                .querySelector(".keypoint_image_waiting")
                .classList.add("hidden");
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
                    class="inline-flex items-center px-4 py-2 text-sm font-medium border border-gray-300 rounded-full cursor-pointer  bg-white text-gray-700 peer-checked:bg-blue-600 peer-checked:text-white hover:bg-gray-100">
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
window.showSternWarning = () => {
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
                    window.location.href = `${baseurl}/page/${uuid}/share_view`;
                    //   openPublicDetailsModal();
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

const showIconNotFoundFeedback = () => {
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
        icon: "warning",
        title: "Couldn't find a good icon :(",
    });
};
