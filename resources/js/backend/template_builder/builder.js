import "grapesjs/dist/css/grapes.min.css";
import grapesjs from "grapesjs";
import BlocksBasic from "grapesjs-blocks-basic";
import html2canvas from 'html2canvas-pro';

var projectEndpoint = `${baseurl}/admin/pagepage/${page_pages[0]}`;
console.log(projectEndpoint);

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


const editor = grapesjs.init({
    container: "#gjs",
    fromElement: true,
    height: "85vh",
    plugins: [BlocksBasic],
    storageManager: {
        type: "remote",
       // stepsBeforeSave: 3,
        autosave: true, // Store data automatically
        autoload: true, // Autoload stored data on init
        stepsBeforeSave: 1, // If autosave is enabled, indicates how many changes are necessary before the store method is triggered

        options: {
            remote: {
                urlLoad: projectEndpoint + "/data",
                urlStore: projectEndpoint + "/update",
                headers: {
                    'X-CSRF-TOKEN': csrfToken, // CSRF Token
                    'Content-Type': 'application/json',
                  },
                // The `remote` storage uses the POST method when stores data but
                // the json-server API requires PATCH.
                fetchOptions: (opts) =>
                    opts.method === "POST" ? { method: "PATCH" } : {},
                // As the API stores projects in this format `{id: 1, data: projectData }`,
                // we have to properly update the body before the store and extract the
                // project data from the response result.
                onStore: (data) => ({ id: page_pages[0], data }),
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

editor.on('load', () => {
    const blockBtn = editor.Panels.getButton('views', 'open-blocks');
    blockBtn.set('active', 1);
})

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

var blockManager = editor.BlockManager;

const icon = `<svg version="1.1" class="wgt" x="0px" y="0px" viewBox="-274 365 63 65">
  <g class="wgt-ico">
  <g class="wgt-ico_wgt-ico-box">
  <path class="wgt-color-secondary" style="fill: currentColor"
d="M-235.5,379.6h0.7c0.1,0,0.1,0.1,0.1,0.2l-11.7,26.7h-1l11.8-26.8C-235.5,379.7-235.5,379.6-235.5,379.6z M-233.8,379.7c0,0,0.1-0.1,0.1-0.1h0.1c0.2,0,0.4,0.3,0.4,0.6v0.4l-11.3,25.9h-1C-245.6,406.5-233.8,379.7-233.8,379.7z M-240.1,377.1h3.2c0.3,0,0.6,0.3,0.6,0.6s-0.3,0.6-0.6,0.6h-3.2c-0.3,0-0.6-0.3-0.6-0.6C-240.7,377.3-240.5,377.1-240.1,377.1z M-238.1,379.8l-11.7,26.7h-1l11.8-26.8c0,0,0.1-0.1,0.1-0.1h0.7C-238.2,379.6-238.1,379.7-238.1,379.8z M-240.7,379.6h0.7 c0.1,0,0.1,0.1,0.1,0.2l-11.7,26.7h-1l11.8-26.8C-240.8,379.7-240.8,379.6-240.7,379.6z M-246.6,377.1h3.2c0.3,0,0.6,0.3,0.6,0.6 s-0.3,0.6-0.6,0.6h-3.2c-0.3,0-0.6-0.3-0.6-0.6C-247.2,377.3-246.9,377.1-246.6,377.1z M-245,379.6L-245,379.6l-11.8,26.9h-1 l11.8-26.8c0,0,0.1-0.1,0.1-0.1C-246,379.6-245,379.6-245,379.6z M-247.7,379.6h0.7c0.1,0,0.1,0.1,0.1,0.2l-11.7,26.7h-1 l11.8-26.8C-247.8,379.7-247.7,379.6-247.7,379.6z M-253,377.1h3.2c0.3,0,0.6,0.3,0.6,0.6s-0.3,0.6-0.6,0.6h-3.2 c-0.3,0-0.6-0.3-0.6-0.6C-253.6,377.3-253.4,377.1-253,377.1z M-259.5,377.1h3.2c0.3,0,0.6,0.3,0.6,0.6s-0.3,0.6-0.6,0.6h-3.2 c-0.3,0-0.6-0.3-0.6-0.6C-260.1,377.3-259.8,377.1-259.5,377.1z M-260.2,380.3c0-0.1,0-0.2,0-0.3l0.1-0.2c0-0.1,0.1-0.1,0.2-0.1 h0.6c0.1,0,0.1,0.1,0.1,0.2l-1.1,2.6L-260.2,380.3C-260.3,380.3-260.2,380.3-260.2,380.3z M-260.2,384.1l1.9-4.4 c0,0,0.1-0.1,0.1-0.1h0.7c0.1,0,0.1,0.1,0.1,0.2l-2.9,6.6C-260.2,386.4-260.2,384.1-260.2,384.1z M-260.2,388.1l3.7-8.4 c0,0,0.1-0.1,0.1-0.1h0.7c0.1,0,0.1,0.1,0.1,0.2l-4.6,10.5C-260.2,390.4-260.2,388.1-260.2,388.1z M-260.2,392l5.4-12.3 c0,0,0.1-0.1,0.1-0.1h0.7c0.1,0,0.1,0.1,0.1,0.2l-6.4,14.5C-260.2,394.3-260.2,392-260.2,392z M-260.2,396.1l7.2-16.4 c0,0,0.1-0.1,0.1-0.1h0.7c0.1,0,0.1,0.1,0.1,0.2l-8.1,18.5C-260.2,398.4-260.2,396.1-260.2,396.1z M-260.2,400l8.9-20.3 c0,0,0.1-0.1,0.1-0.1h0.7c0.1,0,0.1,0.1,0.1,0.2l-9.9,22.5C-260.2,402.3-260.2,400-260.2,400z M-260.2,404l10.7-24.3 c0,0,0.1-0.1,0.1-0.1h0.7c0.1,0,0.1,0.1,0.1,0.2l-11.6,26.4c0-0.1-0.1-0.2-0.1-0.3C-260.2,405.9-260.2,404-260.2,404z M-260.4,410.2C-260.4,410.2-260.5,410.2-260.4,410.2l-3.5,0.1c0,0-0.1,0-0.1-0.1v-3.4c0,0,0-0.1,0.1-0.1h1.1v-2.5 c0-0.3,0.3-0.6,0.6-0.6s0.6,0.3,0.6,0.6v2.5h1.1c0,0,0.1,0,0.1,0.1C-260.4,406.8-260.4,410.2-260.4,410.2z M-262.2,390.7 c0.3,0,0.6,0.3,0.6,0.6v3.2c0,0.3-0.3,0.6-0.6,0.6s-0.6-0.3-0.6-0.6v-3.2C-262.8,391-262.5,390.7-262.2,390.7z M-262.8,388.1v-3.2 c0-0.3,0.3-0.6,0.6-0.6s0.6,0.3,0.6,0.6v3.2c0,0.3-0.3,0.6-0.6,0.6S-262.8,388.4-262.8,388.1z M-262.2,397.2 c0.3,0,0.6,0.3,0.6,0.6v3.2c0,0.3-0.3,0.6-0.6,0.6s-0.6-0.3-0.6-0.6v-3.2C-262.8,397.4-262.5,397.2-262.2,397.2z M-260.4,379.3 C-260.4,379.3-260.5,379.4-260.4,379.3l-1.2,0.1v2.2c0,0.3-0.3,0.6-0.6,0.6s-0.6-0.3-0.6-0.6v-2.2h-1.1c0,0-0.1,0-0.1-0.1v-3.4 c0,0,0-0.1,0.1-0.1h3.4c0,0,0.1,0,0.1,0.1L-260.4,379.3L-260.4,379.3L-260.4,379.3z M-256.6,409.1h-3.2c-0.3,0-0.6-0.3-0.6-0.6 s0.3-0.6,0.6-0.6h3.2c0.3,0,0.6,0.3,0.6,0.6C-256,408.8-256.2,409.1-256.6,409.1z M-256.1,406.5l11.8-26.8c0,0,0.1-0.1,0.1-0.1 h0.7c0.1,0,0.1,0.1,0.1,0.2l-1.7,3.9v0.2l-10,22.7C-255.1,406.5-256.1,406.5-256.1,406.5z M-242.6,379.7c0,0,0.1-0.1,0.1-0.1h0.7 c0.1,0,0.1,0.1,0.1,0.2l-11.7,26.7h-1C-254.4,406.5-242.6,379.7-242.6,379.7z M-250.1,409.1h-3.2c-0.3,0-0.6-0.3-0.6-0.6 s0.3-0.6,0.6-0.6h3.2c0.3,0,0.6,0.3,0.6,0.6C-249.5,408.8-249.8,409.1-250.1,409.1z M-249.1,406.5l11.8-26.8c0,0,0.1-0.1,0.1-0.1 h0.7c0.1,0,0.1,0.1,0.1,0.2l-11.7,26.7C-248.1,406.5-249.1,406.5-249.1,406.5z M-243.7,409.1h-3.2c-0.3,0-0.6-0.3-0.6-0.6 s0.3-0.6,0.6-0.6h3.2c0.3,0,0.6,0.3,0.6,0.6C-243.1,408.8-243.4,409.1-243.7,409.1z M-243.8,406.5l10.6-24.2v2.3l-9.6,21.9H-243.8 z M-242.1,406.5l8.8-20.2v2.3l-7.8,17.9C-241.1,406.5-242.1,406.5-242.1,406.5z M-239.3,406.5h-1l7.1-16.2v2.3L-239.3,406.5z M-233.2,394.3v2.3l-4.3,9.9h-1L-233.2,394.3z M-237.2,409.1h-3.2c-0.3,0-0.6-0.3-0.6-0.6s0.3-0.6,0.6-0.6h3.2 c0.3,0,0.6,0.3,0.6,0.6C-236.6,408.8-236.9,409.1-237.2,409.1z M-236.8,406.5l3.6-8.2v2.3l-2.6,5.9H-236.8z M-233.2,402.3v2.3 l-0.8,2h-1C-235.1,406.5-233.2,402.3-233.2,402.3z M-229.5,410.2C-229.5,410.2-229.5,410.2-229.5,410.2l-3.5,0.1 c0,0-0.1,0-0.1-0.1v-1.1h-1c-0.3,0-0.6-0.3-0.6-0.6s0.3-0.6,0.6-0.6h1v-1.1c0,0,0-0.1,0.1-0.1h3.4c0,0,0.1,0,0.1,0.1 C-229.5,406.9-229.5,410.2-229.5,410.2z M-231.3,387.8c0.3,0,0.6,0.3,0.6,0.6v3.2c0,0.3-0.3,0.6-0.6,0.6s-0.6-0.3-0.6-0.6v-3.2 C-231.9,388-231.6,387.8-231.3,387.8z M-231.9,385.2v-3.2c0-0.3,0.3-0.6,0.6-0.6s0.6,0.3,0.6,0.6v3.2c0,0.3-0.3,0.6-0.6,0.6 C-231.6,385.8-231.9,385.5-231.9,385.2z M-231.3,394.2c0.3,0,0.6,0.3,0.6,0.6v3.2c0,0.3-0.3,0.6-0.6,0.6s-0.6-0.3-0.6-0.6v-3.2 C-231.9,394.5-231.6,394.2-231.3,394.2z M-231.3,400.7c0.3,0,0.6,0.3,0.6,0.6v3.2c0,0.3-0.3,0.6-0.6,0.6s-0.6-0.3-0.6-0.6v-3.2 C-231.9,401-231.6,400.7-231.3,400.7z M-229.5,379.3C-229.5,379.3-229.5,379.4-229.5,379.3l-3.5,0.1c0,0-0.1,0-0.1-0.1v-1.1h-0.7 c-0.3,0-0.6-0.3-0.6-0.6s0.3-0.6,0.6-0.6h0.7v-1.1c0,0,0-0.1,0.1-0.1h3.4c0,0,0.1,0,0.1,0.1C-229.5,375.9-229.5,379.3-229.5,379.3 z"></path>
  </g>
  </g>
  </svg>`;

// 'my-first-block' is the ID of the block
blockManager.add("my-first-block", {
    label: `${icon} <br/>Simple block`,
    type: "SimpleBox",
    content:
        '<div style="border: 1px solid rgb(204, 204, 204); background-color: #fff; width: 200px; height:200px"></div>',
});

blockManager.add("box", {
    label: "Box",
    content: `<div class="box" style="padding: 20px;">
  <div style="width: 300px">
    <div style="width: 280px; padding: 2px">
        <div>Content</div>
    </div>
  </div>
</div>`,
});
