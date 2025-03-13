import Sortable from "sortablejs";

const grid = document.getElementsByClassName("grid").item(0);

const deleteButtons = document.getElementsByClassName("deletebutton");
document.querySelectorAll(".deletebutton").forEach(button => {
    button.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent interfering with other events
        const keypoint = this.closest(".keypoint"); // Find the closest .keypoint parent
        if (keypoint) {
            keypoint.remove(); // Remove the element from the DOM
        }
    });
});

// Enable Sorting
Sortable.create(grid, {
    animation: 150, // Smooth transition
    ghostClass: "bg-gray-300", // Class applied to the dragged item
    onEnd: function (evt) {
        console.log("New Order:", Array.from(grid.children).map((el) => el.innerText));
    },
    filter: "button", // Exclude buttons from being draggable
    preventOnFilter: false, // Ensure buttons remain clickable
});
