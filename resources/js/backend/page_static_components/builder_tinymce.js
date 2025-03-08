import "tom-select/dist/css/tom-select.css";
import "pretty-checkbox/dist/pretty-checkbox.min.css";

import TomSelect from "tom-select";
import tinymce from "tinymce";
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

var projectEndpoint;

projectEndpoint = `${baseurl}/admin/page_static_component`;

console.log(projectEndpoint);

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

tinymce.init({
    selector: "div#tinymce",
    license_key: 'gpl',
    skin: false,
    plugins: ["code", "image", "visualblocks"],
    toolbar: "code | image | visualblocks",
    content_css: tailwindcsspath,
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
        editor.on('init', (e) => {
            // load content from the server
            console.log(projectEndpoint+ `/${component_id}/data`);
            if(component_id > 0){
                fetch(projectEndpoint + `/${component_id}/data`)
                .then((response) => response.json())
                .then((data) => {
                    editor.setContent(data.content);
                });
                }

        });
      }
});

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



window.save = () => {
    const form = document.getElementById("storeForm");
    addHiddenField(
        form,
        "content",
        tinymce.get("tinymce").getContent({ format: "raw" })
    );
    // addHiddenField(form, "html", editor.getHtml());
    // addHiddenField(form, "css", editor.getCss());
    form.submit();
};

const addHiddenField = (form, name, value) => {
    let input = document.createElement("input");
    input.type = "hidden";
    input.name = name;
    input.value = value;
    form.appendChild(input);
};

