import "tom-select/dist/css/tom-select.css";
import TomSelect from "tom-select";
import Sortable from "sortablejs";

// import html2canvas from "html2canvas-pro";
var projectEndpoint;

projectEndpoint = `${baseurl}/admin/page_component`;

const tbody = document.getElementById("table_body");
// sortable
new Sortable(tbody, {
    animation: 150,
    handle: null, // or use a handle selector (e.g. ".drag-handle")
    ghostClass: "bg-yellow-100", // Tailwind class for visual feedback
    onEnd: function (evt) {
      console.log("Reordered rows:");
      const newOrder = [...tbody.querySelectorAll("tr")].map(row =>
        row.getAttribute("data-id")
      );
      console.log(newOrder); // e.g. ["102", "101", "103"]
    }
  });

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

// window.addRow = (component_id) => {
//     let url = `${baseurl}/admin/page_component/${component_id}/metadata`;
//     fetch(url)
//         .then((response) => response.json())
//         .then((json) => {
//             console.log(json);
//             //console.log(callback);
//             let row = ` <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
//                 <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
// ${json.label}
//                 </th>
//                 <td class="px-6 py-4 ">
//                 ${json.description}
//                 </td>

//                 <td class="px-6 py-4">
// <button type="button" onclick="window.deleteRow()" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
// <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
//   <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
//   <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
// </svg></button>
//                 </td>
//             </tr>`;

//             document.getElementById("table_body").innerHTML += row;
//         })
//         .catch((e) => {
//             alert("problem");
//         });
// };

window.addRow = (component_id) => {
    let url = `${baseurl}/admin/snippet/${component_id}/metadata`;
    fetch(url)
        .then((response) => response.json())
        .then((json) => {
            console.log(json);
            //console.log(callback);
            let row = ` <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700" data-id="${component_id}">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
${json.label}
                </th>
                <td class="px-6 py-4 ">
                ${json.description}
                </td>

                <td class="px-6 py-4">
<button type="button" onclick="window.deleteRow(${component_id})" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
  <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
</svg></button>
                </td>
            </tr>`;

            document.getElementById("table_body").innerHTML += row;
        })
        .catch((e) => {
            console.log(e);
        });
};

// delete the row
window.deleteRow = (id) => {
    let table = document.getElementById("list_table");
    let row = table.querySelector(`tr[data-id="${id}"]`);
    table.deleteRow(row.rowIndex);
};

window.save = () => {
    const form = document.getElementById("storeForm");
    const componentsInOrder = [...tbody.querySelectorAll("tr")].map(row =>
        row.getAttribute("data-id")
      );
      console.log(componentsInOrder);
    addHiddenField(form, "components", componentsInOrder);
    form.submit();
};

new TomSelect("#components", {
    create: false,
    preload: true,
    options: components,
    valueField: "value",
    // items: components.map((item) => {
    //     return item.value;
    // }),
    load: function (query, callback) {
        var url = baseurl + "/admin/snippet/search?q=" + query;
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

});

new TomSelect("#teams", {
    create: false,
    options: teams,
    preload:true,
    valueField: "value",
    items: teams.map((item) => {
        return item.value;
    }),
    load: function (query, callback) {
        var url = baseurl + "/admin/teams/search?q=" + query;
        fetch(url)
            .then((response) => response.json())
            .then((json) => {
                console.log('loading teams');
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
    preload:true,
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
                console.log('loading users');
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
    preload:true,
    options: projects,
    valueField: "value",
    items: projects.map((item) => {
        return item.value;
    }),
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

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");


const addHiddenField = (form, name, value) => {
    let input = document.createElement("input");
    input.type = "hidden";
    input.name = name;
    input.value = value;
    form.appendChild(input);
};
