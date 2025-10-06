"use strict";

// Class definition
var KTModalAddRole = (function () {
    var submitButton;
    var cancelButton;
    var closeButton;
    var validator;
    var form;
    var modal;

    // Init form inputs
    var initForm = function () {
        // Name input validation
        validator = FormValidation.formValidation(form, {
            fields: {
                role_name: {
                    validators: {
                        notEmpty: {
                            message: "Nama role wajib diisi",
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
    };

    var handleForm = function () {
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            if (validator) {
                validator.validate().then(function (status) {
                    if (status == "Valid") {
                        // Show loading indication
                        submitButton.setAttribute("data-kt-indicator", "on");

                        // Disable submit button
                        submitButton.disabled = true;

                        // Prepare form data
                        const formData = new FormData(form);

                        // Get selected permissions
                        const permissions = [];
                        const permissionCheckboxes = form.querySelectorAll(
                            'input[name="permissions[]"]:checked'
                        );
                        permissionCheckboxes.forEach((checkbox) => {
                            permissions.push(checkbox.value);
                        });

                        // Add permissions to form data
                        permissions.forEach((permission) => {
                            formData.append("permissions[]", permission);
                        });

                        // Submit form
                        fetch("/setting/role", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content"),
                                Accept: "application/json",
                            },
                            body: formData,
                        })
                            .then((response) => response.json())
                            .then((data) => {
                                // Remove loading indication
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );

                                // Enable submit button
                                submitButton.disabled = false;

                                if (data.success) {
                                    // Close modal
                                    modal.hide();

                                    // Show success message
                                    Swal.fire({
                                        text: data.message,
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton:
                                                "btn fw-bold btn-primary",
                                        },
                                    }).then(function () {
                                        // Reset form
                                        form.reset();

                                        // Reload DataTable if exists
                                        if (
                                            typeof KTRolesList !==
                                                "undefined" &&
                                            window.datatable
                                        ) {
                                            window.datatable.ajax.reload();
                                        } else {
                                            // Fallback to page reload
                                            location.reload();
                                        }
                                    });
                                } else {
                                    // Show error message
                                    let errorMessage =
                                        data.message ||
                                        "Terjadi kesalahan saat menyimpan role.";

                                    // Handle validation errors
                                    if (data.errors) {
                                        const errors = Object.values(
                                            data.errors
                                        ).flat();
                                        errorMessage = errors.join("\n");
                                    }

                                    Swal.fire({
                                        text: errorMessage,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton:
                                                "btn fw-bold btn-primary",
                                        },
                                    });
                                }
                            })
                            .catch((error) => {
                                console.error("Error:", error);

                                // Remove loading indication
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );

                                // Enable submit button
                                submitButton.disabled = false;

                                Swal.fire({
                                    text: "Terjadi kesalahan saat menyimpan role.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton:
                                            "btn fw-bold btn-primary",
                                    },
                                });
                            });
                    }
                });
            }
        });

        // Close button handler (simple close)
        if (closeButton) {
            closeButton.addEventListener("click", function (e) {
                e.preventDefault();
                form.reset();
                modal.hide();
            });
        }

        // Cancel button handler (with confirmation)
        if (cancelButton) {
            cancelButton.addEventListener("click", function (e) {
                e.preventDefault();

                Swal.fire({
                    text: "Apakah Anda yakin ingin membatalkan?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, batalkan!",
                    cancelButtonText: "Tidak",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary",
                    },
                }).then(function (result) {
                    if (result.value) {
                        form.reset(); // Reset form
                        modal.hide(); // Hide modal
                    } else if (result.dismiss === "cancel") {
                        Swal.fire({
                            text: "Form Anda tidak dibatalkan!.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                    }
                });
            });
        }
    };

    // Initialize select all functionality
    var initSelectAll = function () {
        // Handle select all checkboxes
        const selectAllCheckboxes = form.querySelectorAll(
            '[id^="kt_roles_select_all_add_"]'
        );

        selectAllCheckboxes.forEach(function (selectAllCheckbox, index) {
            selectAllCheckbox.addEventListener("change", function () {
                // Find the corresponding permissions checkboxes in the same row
                const row = this.closest("tr");
                const permissionCheckboxes = row.querySelectorAll(
                    'input[name="permissions[]"]'
                );

                permissionCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        });
    };

    return {
        // Public functions
        init: function () {
            // Elements
            modal = new bootstrap.Modal(
                document.querySelector("#kt_modal_add_role")
            );

            form = document.querySelector("#kt_modal_add_role_form");
            submitButton = document.querySelector("#kt_modal_add_role_submit");
            cancelButton = document.querySelector("#kt_modal_add_role_cancel");
            closeButton = document.querySelector("#kt_modal_add_role_close");

            initForm();
            handleForm();
            initSelectAll();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTModalAddRole.init();
});
