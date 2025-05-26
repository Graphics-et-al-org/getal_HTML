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

// Rotating colours for backgrounds- @TODO update this to alight with some colour theory
const snippets_bg_colours = ["#fde8d4", "#ffd2e8", "#d6ecfd"];
var snippets_current_bg_colour = 0;

const keypointgrid = document.getElementsByClassName("keypoints").item(0);
const snippetsgrid = document.getElementsByClassName("snippets").item(0);

// document initialisation
document.addEventListener("DOMContentLoaded", function () {
    console.log("Document is ready");
    // colour the snippets background
    colourSnippetsBackground();
    // implement tooltips
    tippy("[data-tippy-content]");
    // openPublicDetailsModal()
});

// background colour the snipptets
// background colour the snipptets
const colourSnippetsBackground = () => {
    // maximum compatibility: :has() is not supported in pre-2023 browsers
    let snippets = snippetsgrid.querySelectorAll("div.snippet-container");
    let noHeading = Array.from(snippets).filter(
        (el) => !el.querySelector("div.heading")
    );
    noHeading.forEach((snippet) => {
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
