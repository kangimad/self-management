"use strict";

// Class Definition
var KTUsersExport = (function () {
    // Shared variables
    const element = document.getElementById("kt_modal_export_users");
    const form = element.querySelector("#kt_modal_export_users_form");
    const modal = new bootstrap.Modal(element);

    // Init export users modal
    var initExportUsers = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(form, {
            fields: {
                format: {
                    validators: {
                        notEmpty: {
                            message: "Format export wajib dipilih",
                        },
                    },
                },
            },

            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap: new FormValidation.plugins.Bootstrap5({
                    rowSelector: ".fv-row",
                    eleInvalidClass: "",
                    eleValidClass: "",
                }),
            },
        });

        // Export button handler
        const exportButton = element.querySelector(
            '[data-kt-users-export-action="submit"]'
        );
        exportButton.addEventListener("click", function (e) {
            // Prevent default button action
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    console.log("validated!");

                    if (status == "Valid") {
                        // Show loading indication
                        exportButton.setAttribute("data-kt-indicator", "on");

                        // Disable button to avoid multiple click
                        exportButton.disabled = true;

                        // Get current filters from the main table
                        const currentFilters = {
                            search:
                                document.querySelector(
                                    '[data-kt-user-table-filter="search"]'
                                ).value || "",
                            role:
                                document.querySelector(
                                    '[data-kt-user-table-filter="role"]'
                                ).value || "",
                            status:
                                document.querySelector(
                                    '[data-kt-user-table-filter="status"]'
                                ).value || "",
                            verified:
                                document.querySelector(
                                    '[data-kt-user-table-filter="verified"]'
                                ).value || "",
                        };

                        // Build query string
                        const queryParams = new URLSearchParams(
                            currentFilters
                        ).toString();
                        const exportUrl =
                            window.route("setting.user.export") +
                            (queryParams ? "?" + queryParams : "");

                        // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        setTimeout(function () {
                            // Remove loading indication
                            exportButton.removeAttribute("data-kt-indicator");

                            // Enable button
                            exportButton.disabled = false;

                            // Create a temporary link to download the file
                            const link = document.createElement("a");
                            link.href = exportUrl;
                            link.style.display = "none";
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);

                            // Show success message popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                            Swal.fire({
                                text: "Users exported successfully!",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn btn-primary",
                                },
                            }).then(function (result) {
                                if (result.isConfirmed) {
                                    modal.hide();
                                }
                            });
                        }, 2000);
                    }
                });
            }
        });

        // Cancel button handler
        const cancelButton = element.querySelector(
            '[data-kt-users-export-action="cancel"]'
        );
        cancelButton.addEventListener("click", function (e) {
            e.preventDefault();

            Swal.fire({
                text: "Are you sure you would like to cancel?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, cancel it!",
                cancelButtonText: "No, return",
                customClass: {
                    confirmButton: "btn btn-primary",
                    cancelButton: "btn btn-active-light",
                },
            }).then(function (result) {
                if (result.value) {
                    form.reset(); // Reset form
                    modal.hide();
                } else if (result.dismiss === "cancel") {
                    Swal.fire({
                        text: "Your form has not been cancelled!.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary",
                        },
                    });
                }
            });
        });
    };

    return {
        // Public functions
        init: function () {
            initExportUsers();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersExport.init();
});
