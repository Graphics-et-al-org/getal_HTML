import "grapesjs/dist/css/grapes.min.css";

import grapesjs from "grapesjs";
import BlocksBasic from "grapesjs-blocks-basic";

import "tom-select/dist/css/tom-select.css";

import TomSelect from "tom-select";
import axios from "axios";
import $ from "jquery";

// initial setup
document.addEventListener("DOMContentLoaded", function () {
    // intercept form submission
    // $("#storeForm").on("submit", (e) => {
    //     const form = document.getElementById("storeForm");
    //     addHiddenField(
    //         form,
    //         "content",
    //         JSON.stringify(editor.getProjectData())
    //     );
    //     form.submit();
    // });
    // You can add any additional code to run after the document is loaded here
});

window.save = () => {
    const form = document.getElementById("storeForm");
    addHiddenField(form, "content", JSON.stringify(editor.getProjectData()));
    addHiddenField(form, "html", editor.getHtml());
    addHiddenField(form, "css", editor.getCss());
    form.submit();
};

new TomSelect("#tags", {
    create: true,
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

// import html2canvas from "html2canvas-pro";
var projectEndpoint;

projectEndpoint = `${baseurl}/admin/page_static_component`;

console.log(projectEndpoint);

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

editor = grapesjs.init({
    container: "#gjs",
    fromElement: true,
    height: "85vh",
    noticeOnUnload: 0,
    // Include tailwindcss into
    canvas: {
        styles: [tailwindcsspath],
    },
    plugins: [BlocksBasic],
    storageManager: {
        type: "remote",
        // stepsBeforeSave: 3,
        autosave: false, // Store data automatically
        autoload: component_id > 0, // Autoload stored data on init
        //  stepsBeforeSave: 1, // If autosave is enabled, indicates how many changes are necessary before the store method is triggered

        options: {
            remote: {
                urlLoad:
                    component_id > 0
                        ? projectEndpoint + `/${component_id}/data`
                        : projectEndpoint + `/data`,
                urlStore:
                    component_id > 0
                        ? projectEndpoint + `/${component_id}/update`
                        : projectEndpoint + `/store`,
                headers: {
                    "X-CSRF-TOKEN": csrfToken, // CSRF Token
                    "Content-Type": "application/json",
                },
                // The `remote` storage uses the POST method when stores data but
                // the json-server API requires PATCH.
                fetchOptions: (opts) =>
                    opts.method === "POST" ? { method: "PATCH" } : {},
                // As the API stores projects in this format `{id: 1, data: projectData }`,
                // we have to properly update the body before the store and extract the
                // project data from the response result.
                //onStore: (data) => ({ id: component_id, data }),
                onLoad: (result) => result.data,
            },
        },
    },
    pluginsOpts: {
        [BlocksBasic]: {
            flexGrid: true,
        },
    },
});

// events handling

editor.on("load", () => {
    const blockBtn = editor.Panels.getButton("views", "open-blocks");
    blockBtn.set("active", 1);
});

// storage start: little feedback toast maybe?
editor.on("storage:start", (type) => {
    console.log("Storage start");
    console.log(type);
});

// storage end: little feedback toast maybe?
editor.on("storage:end:store", () => {
    console.log("Storage store request ended");
});

// an error in storing
editor.on("storage:error:store", (err) => {
    console.log("Error on store");
    console.log(err);
});

editor.Panels.addButton("options", [
    { id: "undo", className: "fa fa-undo", command: "core:undo" },
    { id: "redo", className: "fa fa-repeat", command: "core:redo" },
]);

const save = () => {
    const form = document.getElementById("storeForm");
    addHiddenField(form, "content", JSON.stringify(editor.getProjectData()));
    form.submit();
};

const addHiddenField = (form, name, value) => {
    let input = document.createElement("input");
    input.type = "hidden";
    input.name = name;
    input.value = value;
    form.appendChild(input);
};
