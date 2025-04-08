import "tom-select/dist/css/tom-select.css";
import "pretty-checkbox/dist/pretty-checkbox.min.css";

import TomSelect from "tom-select";
import tinymce from "tinymce";
import { EditorState } from "@codemirror/state";
import { EditorView } from "@codemirror/view";
import { basicSetup } from "codemirror";
import { indentOnInput } from "@codemirror/language";
import { html } from "@codemirror/lang-html";
import { Modal } from "flowbite";
import beautify from "js-beautify";

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

// setup the CodeMirror editor
const cmEditor = new EditorView({
    state: EditorState.create({
        doc: "<h1>Hello, World!</h1>",
        extensions: [
            basicSetup,
            indentOnInput(), // Enable auto indent on Enter

            html(),
        ],
    }),
    parent: document.getElementById("codemirror-container"),
});

// set the modal menu element
const $targetEl = document.getElementById("html-editor-modal");

// options with default values
const options = {
    placement: "center-center",
    backdrop: "dynamic",
    backdropClasses: "bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40",
    closable: true,
    onHide: () => {
        console.log("hiding modal, saving content");
        tinymce.get("tinymce").setContent(cmEditor.state.doc.toString());
    },
    onShow: () => {
        console.log("modal is shown");
    },
    onToggle: () => {
        console.log("modal has been toggled");
    },
};

// instance options object
const instanceOptions = {
    id: "modalEl",
    override: true,
};

const modal = new Modal($targetEl, options, instanceOptions);

var projectEndpoint;

projectEndpoint = `${baseurl}/admin/page_component`;

console.log(projectEndpoint);

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

tinymce.init({
    selector: "div#tinymce",
    license_key: "gpl",
    skin: false,
    plugins: [
        "code",
        "image",
        "media",
        "visualblocks",
        "preview",
        "fullscreen",
    ],
    toolbar: "codeeditor | image | media| visualblocks | preview | fullscreen",
    content_css: tailwindcsspath,
    verify_html: false,
    extended_valid_elements:
        "svg[*],defs[*],pattern[*],desc[*],metadata[*],g[*],mask[*],path[*],line[*],marker[*],rect[*],circle[*],ellipse[*],polygon[*],polyline[*],linearGradient[*],radialGradient[*],stop[*],image[*],view[*],text[*],textPath[*],title[*],tspan[*],glyph[*],symbol[*],switch[*],use[*]",
    images_file_types: "svg,jpeg,jpg,png,gif",
    file_picker_types: "image",
    /* and here's our custom image picker*/
    file_picker_callback: function (cb, value, meta) {
        var input = document.createElement("input");
        input.setAttribute("type", "file");
        input.setAttribute("accept", "image/*");

        /*
            Note: In modern browsers input[type="file"] is functional without
            even adding it to the DOM, but that might not be the case in some older
            or quirky browsers like IE, so you might want to add it to the DOM
            just in case, and visually hide it. And do not forget do remove it
            once you do not need it anymore.
          */

        input.onchange = function () {
            var file = this.files[0];

            var reader = new FileReader();
            reader.onload = function () {
                /*
                Note: Now we need to register the blob in TinyMCEs image blob
                registry. In the next release this part hopefully won't be
                necessary, as we are looking to handle it internally.
              */
                var id = "blobid" + new Date().getTime();
                var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(",")[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
            };
            reader.readAsDataURL(file);
        };

        input.click();
    },
    setup: (editor) => {
        editor.ui.registry.addButton("codeeditor", {
            text: "Code Editor",
            onAction: function () {
                openCodeMirror(editor);
            },
        });
        editor.on("init", (e) => {
            // load content from the server
            console.log(projectEndpoint + `/${component_id}/data`);
            if (component_id > 0) {
                fetch(projectEndpoint + `/${component_id}/data`)
                    .then((response) => response.json())
                    .then((data) => {
                        editor.setContent(data.content);
                    });
            }
        });
    },
});

const openCodeMirror = (editor) => {
    // Create a modal with CodeMirror
    modal.toggle();
    // Initialize CodeMirror
    setCMEditorContent(tinymce.get("tinymce").getContent(), cmEditor);
    reIndentDocument(cmEditor);
};

window.closeModal = () => {
    modal.toggle();
};

// set the CodeMirror content
const setCMEditorContent = (newContent, editor) => {
    editor.dispatch({
        changes: { from: 0, to: editor.state.doc.length, insert: newContent },
    });
};

// fix up indentations
const reIndentDocument = (editor) => {
    const original = editor.state.doc.toString();
    const formatted = beautify.html(original, {
        indent_size: 2,
        wrap_line_length: 80,
        preserve_newlines: true,
    });

    editor.dispatch({
        changes: {
            from: 0,
            to: editor.state.doc.length,
            insert: formatted,
        },
    });
};

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

new TomSelect("#teams", {
    create: false,
    options: teams,
    preload: true,
    valueField: "value",
    items: teams.map((item) => {
        return item.value;
    }),
    load: function (query, callback) {
        var url = baseurl + "/admin/teams/search?q=" + query;
        fetch(url)
            .then((response) => response.json())
            .then((json) => {
                console.log("loading teams");
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

new TomSelect("#users", {
    create: false,
    preload: true,
    options: users,
    valueField: "value",
    items: users.map((item) => {
        return item.value;
    }),
    load: function (query, callback) {
        var url = baseurl + "/admin/users/search?q=" + query;
        fetch(url)
            .then((response) => response.json())
            .then((json) => {
                console.log("loading users");
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

new TomSelect("#projects", {
    create: false,
    preload: true,
    options: users,
    valueField: "value",
    // items: projects.map((item) => {
    //     return item.value;
    // }),
    load: function (query, callback) {
        var url = baseurl + "/admin/projects/search?q=" + query;
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

window.save = () => {
    const form = document.getElementById("storeForm");
    addHiddenField(form, "content", tinymce.get("tinymce").getContent());
    form.submit();
};

const addHiddenField = (form, name, value) => {
    let input = document.createElement("input");
    input.type = "hidden";
    input.name = name;
    input.value = value;
    form.appendChild(input);
};
