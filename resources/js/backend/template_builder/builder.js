import "grapesjs/dist/css/grapes.min.css";

import grapesjs from "grapesjs";
import BlocksBasic from "grapesjs-blocks-basic";
import html2canvas from "html2canvas-pro";
import { headercontent, footercontent, keypoints_section_content } from "./blocks"

var projectEndpoint = `${baseurl}/admin/pagepage/${page_pages[0]}`;
console.log(projectEndpoint);

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

const editor = grapesjs.init({
    container: "#gjs",
    fromElement: true,
    height: "85vh",
    // Include tailwindcss into
    canvas: {
        styles: [tailwindcsspath]
      },
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


// make some GPT-enabled components

// Add a custom block
editor.BlockManager.add('gpt-enabled-text', {
    label: 'GPT enabled div',
    category: 'GPT enabled Blocks', // Optional grouping
    attributes: { class: 'fas fa-robot' }, // Icon for the block
    media:`<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-robot" viewBox="0 0 16 16">
  <path d="M6 12.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5M3 8.062C3 6.76 4.235 5.765 5.53 5.886a26.6 26.6 0 0 0 4.94 0C11.765 5.765 13 6.76 13 8.062v1.157a.93.93 0 0 1-.765.935c-.845.147-2.34.346-4.235.346s-3.39-.2-4.235-.346A.93.93 0 0 1 3 9.219zm4.542-.827a.25.25 0 0 0-.217.068l-.92.9a25 25 0 0 1-1.871-.183.25.25 0 0 0-.068.495c.55.076 1.232.149 2.02.193a.25.25 0 0 0 .189-.071l.754-.736.847 1.71a.25.25 0 0 0 .404.062l.932-.97a25 25 0 0 0 1.922-.188.25.25 0 0 0-.068-.495c-.538.074-1.207.145-1.98.189a.25.25 0 0 0-.166.076l-.754.785-.842-1.7a.25.25 0 0 0-.182-.135"/>
  <path d="M8.5 1.866a1 1 0 1 0-1 0V3h-2A4.5 4.5 0 0 0 1 7.5V8a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1v1a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-1a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1v-.5A4.5 4.5 0 0 0 10.5 3h-2zM14 7.5V13a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V7.5A3.5 3.5 0 0 1 5.5 4h5A3.5 3.5 0 0 1 14 7.5"/>
</svg>`,
    content: {
      type: 'gpt-enabled-text', // Associate with a custom component type
      content: '<div class="gpt-enabled-text">This is a block with custom data attributes.</div>',
    },
    style: `
      .gpt-enabled-text {
        padding: 20px;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        text-align: center;
      }
    `,
  });

  // Define a custom component type for the block
editor.DomComponents.addType('gpt-enabled-text', {
    model: {
      defaults: {
        tagName: 'div',
        // attributes: {
        //   'data-prompt-attr': 'Prompt herebfdbdf', // Default value for the custom attribute
        //   'data-default-attr': 'Default content', // Add the custom data attribute
        // },
        classes: ['gpt-enabled-text'],
        droppable: true, // Allow dropping elements inside
        traits: [
          {
            type: 'text',
            label: 'Prompt for this field',
            name: 'data-prompt-attr', // Name of the attribute
            changeProp: 1, // Update the component when changed
          },
          {
            type: 'text',
            label: 'Default content',
            name: 'data-default-attr', // Name of the attribute
            changeProp: 1, // Update the component when changed
          },
        ],
      },
      init() {
        // Listen for changes to the custom attribute
        this.on('change:data-prompt-attr', this.handleAttrChange);
        this.on('change:data-default-attr', this.handleAttrChange);
      },
      handleAttrChange() {
        console.log('Custom attribute updated:', this.getAttributes()['data-prompt-attr']);
        console.log('Custom attribute updated:', this.getAttributes()['data-default-attr']);
      },
    },
    view: {
      onRender() {
        console.log('Custom block rendered with attributes:', this.model.getAttributes());
      },
    },
  });


  // Add a custom block
editor.BlockManager.add('gpt-enabled-header', {
    label: 'GPT enabled header',
    category: 'GPT enabled Blocks', // Optional grouping
    attributes: { class: '' }, // Icon for the block
    media:`<svg
   fill="currentColor"
   class="bi bi-layout-sidebar"
   viewBox="0 0 16 16"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:svg="http://www.w3.org/2000/svg">
  <path
     d="m 13,0 a 2,2 0 0 1 2,2 v 12 a 2,2 0 0 1 -2,2 H 3 A 2,2 0 0 1 1,14 V 2 A 2,2 0 0 1 3,0 Z m 1,5 H 2 v 9 a 1,1 0 0 0 1,1 h 10 a 1,1 0 0 0 1,-1 z M 14,4 V 2 A 1,1 0 0 0 13,1 H 3 A 1,1 0 0 0 2,2 v 2 z"
     id="path10" />
</svg>`,
    content: {
      type: 'gpt-enabled-header', // Associate with a custom component type
      content:headercontent,
    },
   });

  // Define a custom component type for the block
editor.DomComponents.addType('gpt-enabled-header', {
    model: {
      defaults: {
        tagName: 'gpt-enabled-header',
        // attributes: {
        //   'data-prompt-attr': 'Prompt herebfdbdf', // Default value for the custom attribute
        //   'data-default-attr': 'Default content', // Add the custom data attribute
        // },
        classes: ['gpt-enabled-header'],
        droppable: true, // Allow dropping elements inside
        traits: [
          {
            type: 'text',
            label: 'Prompt for this field',
            name: 'data-prompt-attr', // Name of the attribute
            changeProp: 1, // Update the component when changed
          },
          {
            type: 'text',
            label: 'Default content',
            name: 'data-default-attr', // Name of the attribute
            changeProp: 1, // Update the component when changed
          },
        ],
      },
      init() {
        // Listen for changes to the custom attribute
        this.on('change:data-prompt-attr', this.handleAttrChange);
        this.on('change:data-default-attr', this.handleAttrChange);
      },
      handleAttrChange() {
        console.log('Custom attribute updated:', this.getAttributes()['data-prompt-attr']);
        console.log('Custom attribute updated:', this.getAttributes()['data-default-attr']);
      },
    },
    view: {
      onRender() {
        console.log('Custom block rendered with attributes:', this.model.getAttributes());
      },
    },
  });

    // Add a custom block
editor.BlockManager.add('gpt-enabled-footer', {
    label: 'GPT enabled footer',
    category: 'GPT enabled Blocks', // Optional grouping
    attributes: { class: '' }, // Icon for the block
    media:`<svg
   fill="currentColor"
   class="bi bi-layout-sidebar"
   viewBox="0 0 16 16"
   xmlns="http://www.w3.org/2000/svg"
   xmlns:svg="http://www.w3.org/2000/svg">

  <path
     d="M 3,16 A 2,2 0 0 1 1,14 V 2 A 2,2 0 0 1 3,0 h 10 a 2,2 0 0 1 2,2 v 12 a 2,2 0 0 1 -2,2 z M 2,11 H 14 V 2 A 1,1 0 0 0 13,1 H 3 A 1,1 0 0 0 2,2 Z m 0,1 v 2 a 1,1 0 0 0 1,1 h 10 a 1,1 0 0 0 1,-1 v -2 z"
     id="path10" />
</svg>
`,
    content: {
      type: 'gpt-enabled-footer', // Associate with a custom component type
      content:footercontent,
    },
   });

  // Define a custom component type for the block
editor.DomComponents.addType('gpt-enabled-footer', {
    model: {
      defaults: {
        tagName: 'gpt-enabled-footer',
        // attributes: {
        //   'data-prompt-attr': 'Prompt herebfdbdf', // Default value for the custom attribute
        //   'data-default-attr': 'Default content', // Add the custom data attribute
        // },
        classes: ['gpt-enabled-footer'],
        droppable: true, // Allow dropping elements inside
        traits: [
          {
            type: 'text',
            label: 'Prompt for this field',
            name: 'data-prompt-attr', // Name of the attribute
            changeProp: 1, // Update the component when changed
          },
          {
            type: 'text',
            label: 'Default content',
            name: 'data-default-attr', // Name of the attribute
            changeProp: 1, // Update the component when changed
          },
        ],
      },
      init() {
        // Listen for changes to the custom attribute
        this.on('change:data-prompt-attr', this.handleAttrChange);
        this.on('change:data-default-attr', this.handleAttrChange);
      },
      handleAttrChange() {
        console.log('Custom attribute updated:', this.getAttributes()['data-prompt-attr']);
        console.log('Custom attribute updated:', this.getAttributes()['data-default-attr']);
      },
    },
    view: {
      onRender() {
        console.log('Custom block rendered with attributes:', this.model.getAttributes());
      },
    },
  });


  editor.BlockManager.add('gpt-enabled-keypoints_section', {
    label: 'GPT enabled grid',
    category: 'GPT enabled Blocks', // Optional grouping
    attributes: { class: '' }, // Icon for the block
    media:`<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-grid-3x2-gap" viewBox="0 0 16 16">
  <path d="M4 4v2H2V4zm1 7V9a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1m0-5V4a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1m5 5V9a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1m0-5V4a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1M9 4v2H7V4zm5 0h-2v2h2zM4 9v2H2V9zm5 0v2H7V9zm5 0v2h-2V9zm-3-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1zm1 4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1z"/>
</svg>`,
    content: {
      type: 'gpt-enabled-keypoints_section', // Associate with a custom component type
      content:keypoints_section_content,
    },
   });

  // Define a custom component type for the block
editor.DomComponents.addType('gpt-enabled-keypoints_section', {
    model: {
      defaults: {
        tagName: 'gpt-enabled-keypoints_section',
        // attributes: {
        //   'data-prompt-attr': 'Prompt herebfdbdf', // Default value for the custom attribute
        //   'data-default-attr': 'Default content', // Add the custom data attribute
        // },
        classes: ['gpt-enabled-keypoints_section'],
        droppable: true, // Allow dropping elements inside

      },

    },
    view: {
      onRender() {
        console.log('Custom block rendered with attributes:', this.model.getAttributes());
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



