import Sortable from "sortablejs";
import { Modal } from "flowbite";
import Swal from "sweetalert2";

const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

document.addEventListener("DOMContentLoaded", function () {
    console.log("Document is ready");
    // colourSnippetsBackground();
    // openPublicDetailsModal()
});

// Copy the clinician URL to clipboard
window.copyClinicianUrl = () => {
    navigator.clipboard.writeText(`${baseurl}/page/${uuid}`).then(
        function () {
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
                title: "Clinician URL copied to clipboard",
            });
            /* clipboard successfully set */
            navigator.clipboard.readText().then(
                function (e) {},
                function () {
                    alert("Clipboard failed");
                }
            );
        },
        function (e) {
            alert("Cut and pasting not supported in this browser");
        }
    );
};

// Copy the clinician URL to clipboard
window.copyPatientUrl = () => {
    navigator.clipboard.writeText(`${baseurl}/public/page/${uuid}`).then(
        function () {
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
                title: "Patient URL copied to clipboard",
            });
            /* clipboard successfully set */
            navigator.clipboard.readText().then(
                function (e) {},
                function () {
                    alert("Clipboard failed");
                }
            );
        },
        function (e) {
            alert("Cut and pasting not supported in this browser");
        }
    );
};

// Copy the clinician URL to clipboard
window.unavailableFunction = () => {
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
        title: "This feature is disabled in demo mode",
    });
};

window.showPrintDialog = () => {
    const iframe = document.querySelector(".iframe-wrapper iframe");
    if (!iframe) {
        window.unavailableFunction();
        return;
    }
    try {
        const iframeWin = iframe.contentWindow;
        if (!iframeWin) throw new Error("No contentWindow found");
        iframeWin.focus();
        iframeWin.print();
    } catch (e) {
        console.warn(
            "Iframe printing blocked or cross-origin, showing demo disabled popup.",
            e
        );
        window.unavailableFunction();
    }
};

const $qrcodeModalTargetEl = document.getElementById("qrcodeModal");

// options with default values
const options = {
    backdrop: "dynamic",
    backdropClasses: "bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40",
    closable: true,
    onHide: () => {
        console.log("modal is hidden");
    },
    onShow: () => {
        console.log("modal is shown");
    },
    onToggle: () => {
        console.log("modal has been toggled");
    },
};

// instance options object
const qrCodeInstanceOptions = {
    id: "qrcodeModal",
    override: true,
};

const qrCodeModal = new Modal(
    $qrcodeModalTargetEl,
    options,
    qrCodeInstanceOptions
);

window.openQrCodeModal = () => {
    console.log("openQrCodeModal");
    qrCodeModal.show();
    // modal.classList.remove("hidden");
    // modal.classList.add("flex");
};

window.closeQrCodeModal = () => {
    qrCodeModal.hide();
};

window.printQrCode = () => {
    const qrCodeElement = document.getElementById("qrcode");
    const qrCodeImage = qrCodeElement.querySelector("img");
    console.log("qrCodeImage", qrCodeImage);
    if (qrCodeImage) {
        // Build your HTML content as a string
        const htmlContent = `
      <!DOCTYPE html>
      <html>
        <head>
          <title>Print QR Code</title>
        </head>
        <body>
          <img src="${qrCodeImage.src}" alt="QR Code" />
        </body>
      </html>
    `;

        // Create a Blob with the HTML content and a proper MIME type
        const blob = new Blob([htmlContent], { type: "text/html" });
        // Generate an object URL for the Blob
        const url = URL.createObjectURL(blob);

        // Open the Blob URL in a new window
        const printWindow = window.open(url, "_blank");
        printWindow.addEventListener('load', () => {
            printWindow.focus();
            printWindow.print();
          });
          
          // Close the window only after printing is complete.
          printWindow.onafterprint = () => {
            printWindow.close();
            URL.revokeObjectURL(url);
          };

    }
};

function sendToPatient() {
    alert("This feature is disabled in demo mode");
}
