"use strict";

// Class definition
var KTUsersAddUser = (function () {
    // Shared variables
    const element = document.getElementById("kt_modal_add_user");
    const form = element.querySelector("#kt_modal_add_user_form");
    const modal = new bootstrap.Modal(element);

    // Init add schedule modal
    var initAddUser = function () {
        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(form, {
            fields: {
                user_name: {
                    validators: {
                        notEmpty: {
                            message: "Nama wajib diisi",
                        },
                    },
                },
                user_email: {
                    validators: {
                        notEmpty: {
                            message: "Email wajib diisi",
                        },
                        emailAddress: {
                            message: "Email tidak valid",
                        },
                    },
                },
                user_password: {
                    validators: {
                        notEmpty: {
                            message: "Password wajib diisi",
                        },
                        stringLength: {
                            min: 8,
                            message: "Password minimal 8 karakter",
                        },
                    },
                },
                user_password_confirmation: {
                    validators: {
                        notEmpty: {
                            message: "Konfirmasi password wajib diisi",
                        },
                        identical: {
                            compare: function () {
                                return form.querySelector(
                                    '[name="user_password"]'
                                ).value;
                            },
                            message: "Konfirmasi password tidak sesuai",
                        },
                    },
                },
                "user_role[]": {
                    validators: {
                        notEmpty: {
                            message: "Role wajib dipilih",
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

        // Submit button handler
        const submitButton = element.querySelector(
            '[data-kt-users-modal-action="submit"]'
        );
        submitButton.addEventListener("click", function (e) {
            // Prevent default button action
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    // console.log("validated!");

                    if (status == "Valid") {
                        // Show loading indication
                        submitButton.setAttribute("data-kt-indicator", "on");

                        // Disable button to avoid multiple click
                        submitButton.disabled = true;

                        // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                        setTimeout(function () {
                            // Remove loading indication
                            submitButton.removeAttribute("data-kt-indicator");

                            // Enable button
                            submitButton.disabled = false;

                            // Submit form via AJAX
                            const formData = new FormData(form);

                            // Clean up role field names for better handling
                            const roles = formData.getAll("user_role[]");
                            formData.delete("user_role[]");
                            roles.forEach((role) =>
                                formData.append("roles[]", role)
                            );

                            // Clean up other field names
                            const fieldsToRename = [
                                "user_name",
                                "user_email",
                                "user_password",
                                "user_password_confirmation",
                            ];
                            fieldsToRename.forEach((field) => {
                                if (formData.has(field)) {
                                    const value = formData.get(field);
                                    formData.delete(field);
                                    const cleanKey = field.replace("user_", "");
                                    if (cleanKey === "password_confirmation") {
                                        formData.append(
                                            "password_confirmation",
                                            value
                                        );
                                    } else {
                                        formData.append(cleanKey, value);
                                    }
                                }
                            });

                            // Handle image file upload - no need to rename as field is already named 'image'
                            // The FormData will automatically include the image file if selected

                            $.ajax({
                                url: window.route("setting.user.store"),
                                type: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": $(
                                        'meta[name="csrf-token"]'
                                    ).attr("content"),
                                },
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function (response) {
                                    if (response.success) {
                                        // Show success message popup
                                        Swal.fire({
                                            text: response.message,
                                            icon: "success",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton:
                                                    "btn btn-primary",
                                            },
                                        }).then(function (result) {
                                            if (result.isConfirmed) {
                                                // Reset form
                                                form.reset();

                                                // Reset validation
                                                if (validator) {
                                                    validator.resetForm();
                                                }

                                                // Reset Select2 dropdowns
                                                $(form)
                                                    .find(
                                                        '[name="user_role[]"]'
                                                    )
                                                    .val(null)
                                                    .trigger("change");

                                                modal.hide();

                                                // Reload datatable if it exists
                                                if (
                                                    typeof KTUsersListDatatable !==
                                                    "undefined"
                                                ) {
                                                    $("#kt_table_users")
                                                        .DataTable()
                                                        .ajax.reload();
                                                }
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            text: response.message,
                                            icon: "error",
                                            buttonsStyling: false,
                                            confirmButtonText: "Ok, got it!",
                                            customClass: {
                                                confirmButton:
                                                    "btn btn-primary",
                                            },
                                        });
                                    }
                                },
                                error: function (xhr) {
                                    let message = "An error occurred";

                                    if (xhr.responseJSON) {
                                        if (xhr.responseJSON.message) {
                                            message = xhr.responseJSON.message;
                                        } else if (xhr.responseJSON.errors) {
                                            // Handle validation errors
                                            const errors =
                                                xhr.responseJSON.errors;
                                            message = Object.values(errors)
                                                .flat()
                                                .join("\n");
                                        }
                                    }

                                    Swal.fire({
                                        text: message,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    });
                                },
                            });
                        }, 2000);
                    }
                });
            }
        });

        // Cancel button handler
        const cancelButton = element.querySelector(
            '[data-kt-users-modal-action="cancel"]'
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
                    // Reset form
                    form.reset();

                    // Reset validation
                    if (validator) {
                        validator.resetForm();
                    }

                    // Reset Select2 dropdowns
                    $(form)
                        .find('[name="user_role[]"]')
                        .val(null)
                        .trigger("change");

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

        // Close button handler
        const closeButton = element.querySelector(
            '[data-kt-users-modal-action="close"]'
        );
        closeButton.addEventListener("click", function (e) {
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
                    // Reset form
                    form.reset();

                    // Reset validation
                    if (validator) {
                        validator.resetForm();
                    }

                    // Reset Select2 dropdowns
                    $(form)
                        .find('[name="user_role[]"]')
                        .val(null)
                        .trigger("change");

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

    // Reset form when modal is hidden
    element.addEventListener("hidden.bs.modal", function () {
        // Reset form
        form.reset();

        // Reset validation if it exists
        if (typeof validator !== "undefined" && validator) {
            validator.resetForm();
        }

        // Reset Select2 dropdowns
        $(form).find('[name="user_role[]"]').val(null).trigger("change");
    });

    // Load roles on modal show
    element.addEventListener("shown.bs.modal", function () {
        // Initialize Select2 first
        const roleSelect = form.querySelector('[name="user_role[]"]');

        // Initialize Select2 with proper settings
        $(roleSelect).select2({
            placeholder: "Select a role...",
            allowClear: false,
            multiple: true,
            dropdownParent: $("#kt_modal_add_user"),
            width: "100%",
        });

        // Load roles for dropdown if needed (roles should already be in HTML)
        if (!roleSelect.options || roleSelect.options.length <= 1) {
            $.ajax({
                url: window.route("setting.user.roles"),
                type: "GET",
                success: function (response) {
                    // Clear existing options
                    $(roleSelect).empty();

                    // Add default option
                    $(roleSelect).append(
                        '<option value="">Select a role...</option>'
                    );

                    if (response.data && response.data.length > 0) {
                        response.data.forEach((role) => {
                            $(roleSelect).append(
                                new Option(role.text, role.value, false, false)
                            );
                        });
                    }

                    // Refresh Select2
                    $(roleSelect).trigger("change");
                },
                error: function (xhr, status, error) {
                    // console.error("Error loading roles:", error);
                },
            });
        }
    });

    return {
        // Public functions
        init: function () {
            initAddUser();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersAddUser.init();
});
