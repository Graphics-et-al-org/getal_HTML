import Swal from "sweetalert2";

// Laravel security token for AJAX calls
const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB limit
const ALLOWED_TYPES = [
    "application/pdf",
    "application/msword",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
];

let isLoading = false;
let fileError = "";
let doctor_text = "";

let acceptedFiles = [];

let rejectedFiles = [];

const openFilePicker = () => {
    const fileInput = document.getElementById("fileInput");
    if (fileInput) {
        fileInput.click(); // Trigger file input
    }
};

// set up element listeners and stuff
document.addEventListener("DOMContentLoaded", () => {
    let fileinputdiv = document.getElementById("hybrid-file-input");
    fileinputdiv.addEventListener("drop", handleDrop);
    fileinputdiv.addEventListener("dragover", preventDefaults);
    fileinputdiv.addEventListener("dragenter", preventDefaults);
    fileinputdiv.addEventListener("dragleave", preventDefaults);
    let doctor_textarea = document.getElementById("doctorTextInput");
    doctor_textarea.addEventListener("input", handleInput);
    document
        .getElementById("fileInput")
        .addEventListener("change", handleFileSelection);
    document
        .getElementById("open-filepicker-button")
        .addEventListener("click", openFilePicker);
    document
        .getElementById("removeFileButton")
        .addEventListener("click", resetfileInput);
    // form submit
    document.getElementById("submitButton").addEventListener("click", (e) => {
        document.getElementById("loading-modal").classList.remove("hidden");
        e.preventDefault();
        const fileInput = document.getElementById("fileInput");
        console.log("submitButton");
        console.log(fileInput);
        const doctorTextInput = document.getElementById("doctorTextInput");
        // if (acceptedFiles.length==0 && doctorTextInput.value === "") {
        //     alert("Please select a file or enter text.");
        //     return;
        // }
        // submit the form
        const formData = new FormData();
        if (fileInput.files[0]) {
            formData.append("file", acceptedFiles[0]);
        }
        if (doctorTextInput.value) {
            formData.append("doctor_text", doctorTextInput.value);
        }
        // Add any other form data you need

        formData.append("collections", selected_collections);
        // Send the form data to the server
        fetch(`${baseurl}/createpage`, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-Token": csrfToken,
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json();
            })
            .then((data) => {
                document.getElementById("loading-modal").classList.add("hidden");
                showSuccessFeedback().then(() => {
                    location.href = `${baseurl}/page/${data.uuid}`;
                });
            })
            .catch((error) => {
                document.getElementById("loading-modal").classList.add("hidden");
                console.error("Error:", error);
                showErrorFeedback();
                // Handle error (e.g., show an error message)
            });

        console.log("Form submitted");
    });
});

// handle the file selection
const handleFileSelection = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (file.size > MAX_FILE_SIZE) {
            fileError = "File size exceeds 5MB limit.";
            return;
        }
        if (!ALLOWED_TYPES.includes(file.type)) {
            return "Only PDF and Word documents are allowed.";
        }
        fileError = "";
        triggerLoading(() => {
            console.log("File selected:", file);
            handleFile(file);

            //doctorTextInput.classList.add("hidden");
        });
    }
};

const handleDrop = (event) => {
    console.log("Handle drop event");
    document.getElementById("doctorTextInput").value = "";
    event.preventDefault();
    event.stopPropagation();
    const file = event.dataTransfer.files[0];
    if (file) {
        // is this an accepted type?

        if (file.size > MAX_FILE_SIZE) {
            fileError = "File size exceeds 5MB limit.";
            return;
        }
        if (!ALLOWED_TYPES.includes(file.type)) {
            return "Only PDF and Word documents are allowed.";
        }
        fileError = "";
        triggerLoading(() => {
            handleFile(file);
            console.log("File selected:", file);
        });
    }
};

const resetfileInput = () => {
    console.log("Resetting file input");
    acceptedFiles.length = 0; // Clear the accepted files array
    rejectedFiles.length = 0; // Clear the rejected files array
    const fileInput = document.getElementById("fileInput");
    const previewContainer = document.getElementById("previewContainer");
    const doctorTextInput = document.getElementById("doctorTextInput");
    const previewLabel = document.getElementById("previewLabel");

    fileInput.value = ""; // Clear the file input
    previewContainer.classList.add("hidden"); // Hide the preview container
    doctorTextInput.classList.remove("hidden"); // Show the text input
    previewLabel.innerHTML = ""; // Clear the preview label
};

// text input
const handleInput = (event) => {
    if (fileInput.value != "") {
        fileInput.value = "";
        previewContainer.classList.add("hidden");
        doctorTextInput.classList.remove("hidden");
    }
    console.log(event);
};

const triggerLoading = (callback) => {
    console.log("triggerLoading triggered");
    isLoading = true;
    setTimeout(() => {
        callback();
        isLoading = false;
    }, 1500); // 1.5-second loading delay
};

const preventDefaults = (event) => {
    event.preventDefault();
    event.stopPropagation();
};

const handleFile = (file) => {
    // empty the array
    acceptedFiles.length = 0;
    // push the file to the end
    acceptedFiles.push(file);

    previewContainer.classList.remove("hidden");
    previewLabel.innerHTML = file.name;
};

////////////////////////////////////////////////
// seach snippets api
////////////////////////////////////////////////

var isSearching = false;
var info_categories = [];
var selected_collections = [];

// Define the actual search function
async function getCollectionBySearch(query) {
    console.log("getCollectionBySearch called with:", query);
    if (!query) {
        document.getElementById(
            "categorieslist"
        ).innerHTML = `<div style="color:red;">Please enter a search term.</div>`;

        return;
    }
    try {
        const res = await fetch(
            `${baseurl}/categories/search?q=${encodeURIComponent(query)}`
        );

        if (!res.ok) throw new Error("Search failed.");
        let data = await res.json();
        console.log(data);
        // are any of these already selected?
        info_categories = info_categories.filter((item) =>
            selected_collections.includes(item.uuid)
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
    } catch (err) {
        document.getElementById(
            "categorieslist"
        ).innerHTML = `<div style="color:red;">Error: ${err.message}</div>`;
    }
}

/// Define the debounce function (unchanged)
function debounce(callback, delay) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => {
            callback.apply(this, args);
        }, delay);
    };
}

// Create the debounced search function
const debouncedSearch = debounce(function () {
    const query = document.getElementById("categorysearch").value.trim();
    getCollectionBySearch(query);
}, 300);

// Attach the debounced function to the input event
document
    .getElementById("categorysearch")
    .addEventListener("input", debouncedSearch);

// handle a selection of a static component
window.handleInfoCategoryChange = (e) => {
    //add to selected static components list
    console.log(e.target.value);
    if (e.target.checked) {
        if (!selected_collections.includes(e.target.value)) {
            selected_collections.push(e.target.value);
        }
    } else {
        selected_collections.includes(e.target.value) &&
            selected_collections.splice(
                selected_collections.indexOf(e.target.value),
                1
            );
    }
};

//@TODO submit form, make a choice if no text/foile to make just n info sheet
const showSuccessFeedback = () => {
    let promise = new Promise((resolve, reject) => {
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
        }).then(() => {
            resolve(true);
        });
    });
    return promise;
};

const showErrorFeedback = () => {
     let promise = new Promise((resolve, reject) => {
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
        }).then(() => {
            resolve(true);
        });
    });
    return promise;
};

