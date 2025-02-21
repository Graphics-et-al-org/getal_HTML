import "grapesjs/dist/css/grapes.min.css";
import "grapesjs-component-code-editor/dist/grapesjs-component-code-editor.min.css";

import grapesjs from "grapesjs";
import codeeditorplugin from "grapesjs-component-code-editor";
import parserPostCSS from "grapesjs-parser-postcss";
import presetplugin from "grapesjs-preset-webpage";
import customcode from "grapesjs-custom-code";
import BlocksBasic from "grapesjs-blocks-basic";
import suggestions from "@alandow/grapesjs-ui-suggest-classes";

var projectEndpoint;

projectEndpoint = `${baseurl}/admin/page`;

console.log(projectEndpoint);

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

console.log(projectEndpoint + `${page_id}/data`);

const editor = grapesjs.init({
    container: "#gjs",
    fromElement: true,
    noticeOnUnload: 0,
    height: "85vh",
    // Include tailwindcss into
    canvas: {
        styles: [tailwindcsspath],
    },

    plugins: [
        BlocksBasic,
        presetplugin,
        customcode,
        parserPostCSS,
        codeeditorplugin,
        suggestions,
    ],

    // styleManager: {
    //     sectors: [
    //         {
    //             name: "General",
    //             properties: [
    //                 {
    //                     extend: "float",
    //                     type: "radio",
    //                     default: "none",
    //                     options: [
    //                         { value: "none", className: "fa fa-times" },
    //                         { value: "left", className: "fa fa-align-left" },
    //                         { value: "right", className: "fa fa-align-right" },
    //                     ],
    //                 },
    //                 "display",
    //                 { extend: "position", type: "select" },
    //                 "top",
    //                 "right",
    //                 "left",
    //                 "bottom",
    //             ],
    //         },
    //         {
    //             name: "Dimension",
    //             open: false,
    //             properties: [
    //                 "width",
    //                 {
    //                     id: "flex-width",
    //                     type: "integer",
    //                     name: "Width",
    //                     units: ["px", "%"],
    //                     property: "flex-basis",
    //                     toRequire: 1,
    //                 },
    //                 "height",
    //                 "max-width",
    //                 "min-height",
    //                 "margin",
    //                 "padding",
    //             ],
    //         },
    //         {
    //             name: "Typography",
    //             open: false,
    //             properties: [
    //                 "font-family",
    //                 "font-size",
    //                 "font-weight",
    //                 "letter-spacing",
    //                 "color",
    //                 "line-height",
    //                 {
    //                     extend: "text-align",
    //                     options: [
    //                         {
    //                             id: "left",
    //                             label: "Left",
    //                             className: "fa fa-align-left",
    //                         },
    //                         {
    //                             id: "center",
    //                             label: "Center",
    //                             className: "fa fa-align-center",
    //                         },
    //                         {
    //                             id: "right",
    //                             label: "Right",
    //                             className: "fa fa-align-right",
    //                         },
    //                         {
    //                             id: "justify",
    //                             label: "Justify",
    //                             className: "fa fa-align-justify",
    //                         },
    //                     ],
    //                 },
    //                 {
    //                     property: "text-decoration",
    //                     type: "radio",
    //                     default: "none",
    //                     options: [
    //                         {
    //                             id: "none",
    //                             label: "None",
    //                             className: "fa fa-times",
    //                         },
    //                         {
    //                             id: "underline",
    //                             label: "underline",
    //                             className: "fa fa-underline",
    //                         },
    //                         {
    //                             id: "line-through",
    //                             label: "Line-through",
    //                             className: "fa fa-strikethrough",
    //                         },
    //                     ],
    //                 },
    //                 "text-shadow",
    //             ],
    //         },
    //         {
    //             name: "Decorations",
    //             open: false,
    //             properties: [
    //                 "opacity",
    //                 "border-radius",
    //                 "border",
    //                 "box-shadow",
    //                 "background", // { id: 'background-bg', property: 'background', type: 'bg' }
    //             ],
    //         },
    //         {
    //             name: "Extra",
    //             open: false,
    //             buildProps: ["transition", "perspective", "transform"],
    //         },
    //         {
    //             name: "Flex",
    //             open: false,
    //             properties: [
    //                 {
    //                     name: "Flex Container",
    //                     property: "display",
    //                     type: "select",
    //                     defaults: "block",
    //                     list: [
    //                         { value: "block", name: "Disable" },
    //                         { value: "flex", name: "Enable" },
    //                     ],
    //                 },
    //                 {
    //                     name: "Flex Parent",
    //                     property: "label-parent-flex",
    //                     type: "integer",
    //                 },
    //                 {
    //                     name: "Direction",
    //                     property: "flex-direction",
    //                     type: "radio",
    //                     defaults: "row",
    //                     list: [
    //                         {
    //                             value: "row",
    //                             name: "Row",
    //                             className: "icons-flex icon-dir-row",
    //                             title: "Row",
    //                         },
    //                         {
    //                             value: "row-reverse",
    //                             name: "Row reverse",
    //                             className: "icons-flex icon-dir-row-rev",
    //                             title: "Row reverse",
    //                         },
    //                         {
    //                             value: "column",
    //                             name: "Column",
    //                             title: "Column",
    //                             className: "icons-flex icon-dir-col",
    //                         },
    //                         {
    //                             value: "column-reverse",
    //                             name: "Column reverse",
    //                             title: "Column reverse",
    //                             className: "icons-flex icon-dir-col-rev",
    //                         },
    //                     ],
    //                 },
    //                 {
    //                     name: "Justify",
    //                     property: "justify-content",
    //                     type: "radio",
    //                     defaults: "flex-start",
    //                     list: [
    //                         {
    //                             value: "flex-start",
    //                             className: "icons-flex icon-just-start",
    //                             title: "Start",
    //                         },
    //                         {
    //                             value: "flex-end",
    //                             title: "End",
    //                             className: "icons-flex icon-just-end",
    //                         },
    //                         {
    //                             value: "space-between",
    //                             title: "Space between",
    //                             className: "icons-flex icon-just-sp-bet",
    //                         },
    //                         {
    //                             value: "space-around",
    //                             title: "Space around",
    //                             className: "icons-flex icon-just-sp-ar",
    //                         },
    //                         {
    //                             value: "center",
    //                             title: "Center",
    //                             className: "icons-flex icon-just-sp-cent",
    //                         },
    //                     ],
    //                 },
    //                 {
    //                     name: "Align",
    //                     property: "align-items",
    //                     type: "radio",
    //                     defaults: "center",
    //                     list: [
    //                         {
    //                             value: "flex-start",
    //                             title: "Start",
    //                             className: "icons-flex icon-al-start",
    //                         },
    //                         {
    //                             value: "flex-end",
    //                             title: "End",
    //                             className: "icons-flex icon-al-end",
    //                         },
    //                         {
    //                             value: "stretch",
    //                             title: "Stretch",
    //                             className: "icons-flex icon-al-str",
    //                         },
    //                         {
    //                             value: "center",
    //                             title: "Center",
    //                             className: "icons-flex icon-al-center",
    //                         },
    //                     ],
    //                 },
    //                 {
    //                     name: "Flex Children",
    //                     property: "label-parent-flex",
    //                     type: "integer",
    //                 },
    //                 {
    //                     name: "Order",
    //                     property: "order",
    //                     type: "integer",
    //                     defaults: 0,
    //                     min: 0,
    //                 },
    //                 {
    //                     name: "Flex",
    //                     property: "flex",
    //                     type: "composite",
    //                     properties: [
    //                         {
    //                             name: "Grow",
    //                             property: "flex-grow",
    //                             type: "integer",
    //                             defaults: 0,
    //                             min: 0,
    //                         },
    //                         {
    //                             name: "Shrink",
    //                             property: "flex-shrink",
    //                             type: "integer",
    //                             defaults: 0,
    //                             min: 0,
    //                         },
    //                         {
    //                             name: "Basis",
    //                             property: "flex-basis",
    //                             type: "integer",
    //                             units: ["px", "%", ""],
    //                             unit: "",
    //                             defaults: "auto",
    //                         },
    //                     ],
    //                 },
    //                 {
    //                     name: "Align",
    //                     property: "align-self",
    //                     type: "radio",
    //                     defaults: "auto",
    //                     list: [
    //                         {
    //                             value: "auto",
    //                             name: "Auto",
    //                         },
    //                         {
    //                             value: "flex-start",
    //                             title: "Start",
    //                             className: "icons-flex icon-al-start",
    //                         },
    //                         {
    //                             value: "flex-end",
    //                             title: "End",
    //                             className: "icons-flex icon-al-end",
    //                         },
    //                         {
    //                             value: "stretch",
    //                             title: "Stretch",
    //                             className: "icons-flex icon-al-str",
    //                         },
    //                         {
    //                             value: "center",
    //                             title: "Center",
    //                             className: "icons-flex icon-al-center",
    //                         },
    //                     ],
    //                 },
    //             ],
    //         },
    //     ],
    // },
    storageManager: {
        type: "remote",
        // stepsBeforeSave: 3,
        autosave: false, // Store data automatically
        autoload: page_id > 0, // Autoload stored data on init
        //  stepsBeforeSave: 1, // If autosave is enabled, indicates how many changes are necessary before the store method is triggered

        options: {
            remote: {
                urlLoad:
                    page_id > 0
                        ? projectEndpoint + `/${page_id}/data`
                        : projectEndpoint + `data`,
                urlStore:
                    page_id > 0
                        ? projectEndpoint + `${page_id}/update`
                        : projectEndpoint + `store`,
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
                //onStore: (data) => ({ id: page_pages[0], data }),
                onLoad: (result) => result.data,
            },
        },
    },
    panels: {
        defaults: [
            {
                buttons: [
                    //...
                    {
                        attributes: { title: "Open Code" },
                        className: "fa fa-code",
                        command: "open-code",
                        id: "open-code",
                    },
                    //...
                ],
                id: "views",
            },
        ],
    },
    selectorManager: { componentFirst: false },
    assetManager: {
        assets: [], // Load assets dynamically
        upload: false, // Disable upload for now
        autoAdd: true,
        // custom: true,
    },
    pluginsOpts: {
        presetplugin: {
            modalImportTitle: "Import Template",
            modalImportLabel:
                '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
            modalImportContent: function (editor) {
                return (
                    editor.getHtml() + "<style>" + editor.getCss() + "</style>"
                );
            },
        },
        BlocksBasic: {
            flexGrid: true,
            blocks: [
                "column1",
                "column2",
                "column3",
                "column3-7",
                "text",
                "link",
                "image",
            ],
        },
        suggestions: {
            enableCount: false,
            parseCssURLForClasses:true,
            cssToParseURL:tailwindcsspath,
        },
    },
});
const pn = editor.Panels;
const panelViews = pn.addPanel({
    id: "views",
});
panelViews.get("buttons").add([
    {
        attributes: {
            title: "Open Code",
        },
        className: "fa fa-file-code-o",
        command: "open-code",
        togglable: false, //do not close when button is clicked again
        id: "open-code",
    },
]);

const modal = editor.Modal;
const am = editor.AssetManager;

// You can reference the Selector Manager like this:
const sm = editor.SelectorManager;
// Add a new class to the system

// fetchAndExtractClasses(tailwindcsspath).then((classes) => {
//     classes.forEach((cls) => {
//         sm.addClass(cls);
//     });
// });

//const newClass = sm.addClass('my-nice-class');
// Add a new class to the system

// A small helper to add a search bar if it doesn't already exist
function injectSearchBar() {
    // The 'body' of the GrapesJS modal
    const modalBodyEl = modal.getContentEl();

    // Check if we've already inserted the search bar
    if (!modalBodyEl.querySelector(".search-container")) {
        // Create the container + input
        const searchContainer = document.createElement("div");
        searchContainer.className = "search-container";

        const searchInput = document.createElement("input");
        searchInput.type = "text";
        searchInput.placeholder = "Search assets...";

        // Insert into the modal body, above the asset manager
        searchContainer.appendChild(searchInput);
        // By default, the Asset Manager content is in the modal body,
        // so we prepend the search bar there
        modalBodyEl.prepend(searchContainer);

        // Listen for input changes
        searchInput.addEventListener("input", (e) => {
            const query = e.target.value.trim().toLowerCase();

            // Option A: client-side filtering of the currently loaded assets
            // -------------------------------------------------------------
            // 1. Get the existing assets
            // const allAssets = am.getAll();

            // // 2. Filter them in-memory
            // const filtered = allAssets.filter((asset) =>
            //     asset.get("src").toLowerCase().includes(query)
            // );

            // // 3. Clear and re-add
            // am.clear();
            // am.add(filtered.map((a) => a.attributes));

            // Option B: server-side searching
            // -------------------------------------------------------------
            // If you want to re-fetch from a server for each query, you'd do:

            am.load({
                params: {
                    q: query, // or any param your backend expects
                },
                reset: true,
            });
        });
    }
}

// Listen for when the asset manager is opened
editor.on("run:open-assets", () => {
    injectSearchBar();
});

// events handling

editor.on("update", () => {
    console.log("updated");
});

editor.on("load", () => {
    const blockBtn = editor.Panels.getButton("views", "open-blocks");
    blockBtn.set("active", 1);
    // editor.Panels.addButton("views", {
    //     id: "open-assets",
    //     className: "fa fa-folder-open",
    //     command: "open-assets",
    //     attributes: { title: "Open Assets" },
    // });
    // Manually add assets
    const assets = [
        { src: "https://dummyimage.com/600x400", name: "Placeholder 1" },
        { src: "https://dummyimage.com/400x200", name: "Placeholder 2" },
    ];

    editor.AssetManager.add(assets);
    editor.AssetManager.render(); // Force UI refresh
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

// make some GPT-enabled components

// // Add a custom block
// editor.BlockManager.add("gpt-enabled-text", {
//     label: "GPT enabled div",
//     category: "GPT enabled Blocks", // Optional grouping
//     attributes: { class: "fas fa-robot" }, // Icon for the block
//     media: `<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-robot" viewBox="0 0 16 16">
//   <path d="M6 12.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5M3 8.062C3 6.76 4.235 5.765 5.53 5.886a26.6 26.6 0 0 0 4.94 0C11.765 5.765 13 6.76 13 8.062v1.157a.93.93 0 0 1-.765.935c-.845.147-2.34.346-4.235.346s-3.39-.2-4.235-.346A.93.93 0 0 1 3 9.219zm4.542-.827a.25.25 0 0 0-.217.068l-.92.9a25 25 0 0 1-1.871-.183.25.25 0 0 0-.068.495c.55.076 1.232.149 2.02.193a.25.25 0 0 0 .189-.071l.754-.736.847 1.71a.25.25 0 0 0 .404.062l.932-.97a25 25 0 0 0 1.922-.188.25.25 0 0 0-.068-.495c-.538.074-1.207.145-1.98.189a.25.25 0 0 0-.166.076l-.754.785-.842-1.7a.25.25 0 0 0-.182-.135"/>
//   <path d="M8.5 1.866a1 1 0 1 0-1 0V3h-2A4.5 4.5 0 0 0 1 7.5V8a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1v1a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-1a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1v-.5A4.5 4.5 0 0 0 10.5 3h-2zM14 7.5V13a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7.5A3.5 3.5 0 0 1 5.5 4h5A3.5 3.5 0 0 1 14 7.5"/>
// </svg>`,
//     content: {
//         type: "gpt-enabled-text", // Associate with a custom component type
//         content:
//             '<div class="gpt-enabled-text">This is a block with custom data attributes.</div>',
//     },
//     style: `
//       .gpt-enabled-text {
//         padding: 20px;
//         background-color: #f4f4f4;
//         border: 1px solid #ddd;
//         text-align: center;
//       }
//     `,
// });

// // Define a custom component type for the block
// editor.DomComponents.addType("gpt-enabled-text", {
//     model: {
//         defaults: {
//             tagName: "div",
//             // attributes: {
//             //   'data-prompt-attr': 'Prompt herebfdbdf', // Default value for the custom attribute
//             //   'data-default-attr': 'Default content', // Add the custom data attribute
//             // },
//             classes: ["gpt-enabled-text"],
//             droppable: true, // Allow dropping elements inside
//             traits: [
//                 {
//                     type: "text",
//                     label: "Prompt for this field",
//                     name: "data-prompt-attr", // Name of the attribute
//                     changeProp: 1, // Update the component when changed
//                 },
//                 {
//                     type: "text",
//                     label: "Default content",
//                     name: "data-default-attr", // Name of the attribute
//                     changeProp: 1, // Update the component when changed
//                 },
//             ],
//         },
//         init() {
//             // Listen for changes to the custom attribute
//             this.on("change:data-prompt-attr", this.handleAttrChange);
//             this.on("change:data-default-attr", this.handleAttrChange);
//         },
//         handleAttrChange() {
//             console.log(
//                 "Custom attribute updated:",
//                 this.getAttributes()["data-prompt-attr"]
//             );
//             console.log(
//                 "Custom attribute updated:",
//                 this.getAttributes()["data-default-attr"]
//             );
//         },
//     },
//     view: {
//         onRender() {
//             console.log(
//                 "Custom block rendered with attributes:",
//                 this.model.getAttributes()
//             );
//         },
//     },
// });

// // Add a custom block
// editor.BlockManager.add("gpt-enabled-header", {
//     label: "GPT enabled header",
//     category: "GPT enabled Blocks", // Optional grouping
//     attributes: { class: "" }, // Icon for the block
//     media: `<svg
//    fill="currentColor"
//    class="bi bi-layout-sidebar"
//    viewBox="0 0 16 16"
//    xmlns="http://www.w3.org/2000/svg"
//    xmlns:svg="http://www.w3.org/2000/svg">
//   <path
//      d="m 13,0 a 2,2 0 0 1 2,2 v 12 a 2,2 0 0 1 -2,2 H 3 A 2,2 0 0 1 1,14 V 2 A 2,2 0 0 1 3,0 Z m 1,5 H 2 v 9 a 1,1 0 0 0 1,1 h 10 a 1,1 0 0 0 1,-1 z M 14,4 V 2 A 1,1 0 0 0 13,1 H 3 A 1,1 0 0 0 2,2 v 2 z"
//      id="path10" />
// </svg>`,
//     content: {
//         type: "gpt-enabled-header", // Associate with a custom component type
//         content: headercontent,
//     },
// });

// // Define a custom component type for the block
// editor.DomComponents.addType("gpt-enabled-header", {
//     model: {
//         defaults: {
//             tagName: "gpt-enabled-header",
//             // attributes: {
//             //   'data-prompt-attr': 'Prompt herebfdbdf', // Default value for the custom attribute
//             //   'data-default-attr': 'Default content', // Add the custom data attribute
//             // },
//             classes: ["gpt-enabled-header"],
//             droppable: true, // Allow dropping elements inside
//             traits: [
//                 {
//                     type: "text",
//                     label: "Prompt for this field",
//                     name: "data-prompt-attr", // Name of the attribute
//                     changeProp: 1, // Update the component when changed
//                 },
//                 {
//                     type: "text",
//                     label: "Default content",
//                     name: "data-default-attr", // Name of the attribute
//                     changeProp: 1, // Update the component when changed
//                 },
//             ],
//         },
//         init() {
//             // Listen for changes to the custom attribute
//             this.on("change:data-prompt-attr", this.handleAttrChange);
//             this.on("change:data-default-attr", this.handleAttrChange);
//         },
//         handleAttrChange() {
//             console.log(
//                 "Custom attribute updated:",
//                 this.getAttributes()["data-prompt-attr"]
//             );
//             console.log(
//                 "Custom attribute updated:",
//                 this.getAttributes()["data-default-attr"]
//             );
//         },
//     },
//     view: {
//         onRender() {
//             console.log(
//                 "Custom block rendered with attributes:",
//                 this.model.getAttributes()
//             );
//         },
//     },
// });

// // Add a custom block
// editor.BlockManager.add("gpt-enabled-footer", {
//     label: "GPT enabled footer",
//     category: "GPT enabled Blocks", // Optional grouping
//     attributes: { class: "" }, // Icon for the block
//     media: `<svg
//    fill="currentColor"
//    class="bi bi-layout-sidebar"
//    viewBox="0 0 16 16"
//    xmlns="http://www.w3.org/2000/svg"
//    xmlns:svg="http://www.w3.org/2000/svg">

//   <path
//      d="M 3,16 A 2,2 0 0 1 1,14 V 2 A 2,2 0 0 1 3,0 h 10 a 2,2 0 0 1 2,2 v 12 a 2,2 0 0 1 -2,2 z M 2,11 H 14 V 2 A 1,1 0 0 0 13,1 H 3 A 1,1 0 0 0 2,2 Z m 0,1 v 2 a 1,1 0 0 0 1,1 h 10 a 1,1 0 0 0 1,-1 v -2 z"
//      id="path10" />
// </svg>
// `,
//     content: {
//         type: "gpt-enabled-footer", // Associate with a custom component type
//         content: footercontent,
//     },
// });

// // Define a custom component type for the block
// editor.DomComponents.addType("gpt-enabled-footer", {
//     model: {
//         defaults: {
//             tagName: "gpt-enabled-footer",

//             classes: ["gpt-enabled-footer"],
//             droppable: true, // Allow dropping elements inside
//             traits: [
//                 {
//                     type: "text",
//                     label: "Prompt for this field",
//                     name: "data-prompt-attr", // Name of the attribute
//                     changeProp: 1, // Update the component when changed
//                 },
//                 {
//                     type: "text",
//                     label: "Default content",
//                     name: "data-default-attr", // Name of the attribute
//                     changeProp: 1, // Update the component when changed
//                 },
//             ],
//         },
//         init() {
//             // Listen for changes to the custom attribute
//             this.on("change:data-prompt-attr", this.handleAttrChange);
//             this.on("change:data-default-attr", this.handleAttrChange);
//         },
//         handleAttrChange() {
//             console.log(
//                 "Custom attribute updated:",
//                 this.getAttributes()["data-prompt-attr"]
//             );
//             console.log(
//                 "Custom attribute updated:",
//                 this.getAttributes()["data-default-attr"]
//             );
//         },
//     },
//     view: {
//         onRender() {
//             console.log(
//                 "Custom block rendered with attributes:",
//                 this.model.getAttributes()
//             );
//         },
//     },
// });

// editor.BlockManager.add("gpt-enabled-keypoints_section", {
//     label: "GPT enabled grid",
//     category: "GPT enabled Blocks", // Optional grouping
//     attributes: { class: "" }, // Icon for the block
//     media: `<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-grid-3x2-gap" viewBox="0 0 16 16">
//   <path d="M4 4v2H2V4zm1 7V9a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1m0-5V4a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1m5 5V9a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1m0-5V4a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1M9 4v2H7V4zm5 0h-2v2h2zM4 9v2H2V9zm5 0v2H7V9zm5 0v2h-2V9zm-3-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm1 4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1z"/>
// </svg>`,
//     content: {
//         type: "gpt-enabled-keypoints_section", // Associate with a custom component type
//         content: keypoints_section_content,
//     },
// });

// // Define a custom component type for the block
// editor.DomComponents.addType("gpt-enabled-keypoints_section", {
//     model: {
//         defaults: {
//             tagName: "gpt-enabled-keypoints_section",
//             classes: ["gpt-enabled-keypoints_section"],
//             droppable: true, // Allow dropping elements inside
//         },
//     },
//     view: {
//         onRender() {
//             console.log(
//                 "Custom block rendered with attributes:",
//                 this.model.getAttributes()
//             );
//         },
//     },
// });

new TomSelect("#tags", {
    create: true,
    options: tags,
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

const addHiddenField = (form, name, value) => {
    let input = document.createElement("input");
    input.type = "hidden";
    input.name = name;
    input.value = value;
    form.appendChild(input);
};

var projectEndpoint = `${baseurl}/admin/page/`;
import "tom-select/dist/css/tom-select.css";

import TomSelect from "tom-select";

window.save = () => {
    const form = document.getElementById("storeForm");
    addHiddenField(form, "content", JSON.stringify(editor.getProjectData()));
    addHiddenField(form, "html", editor.getHtml());
    addHiddenField(form, "css", editor.getCss());
    form.submit();
};

// async function fetchAndExtractClasses(url) {
//     try {
//         const response = await fetch(url);
//         const cssText = await response.text();

//         const classNames = new Set();
//         // Regex to match class selectors
//         const regex = /\.([\w-]+)/g;
//         let match;
//         while ((match = regex.exec(cssText)) !== null) {
//             classNames.add(match[1]);
//         }
//         return Array.from(classNames);
//     } catch (error) {
//         console.error("Error fetching CSS:", error);
//         return [];
//     }
// }
