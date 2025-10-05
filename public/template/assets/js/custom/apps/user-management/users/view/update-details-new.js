"use strict";

// Class definition
var KTUsersUpdateDetailsNew = (function () {
    // Shared variables
    const element = document.getElementById("kt_modal_update_details");
    const form = element.querySelector("#kt_modal_update_user_form");
    const modal = new bootstrap.Modal(element);

    // Private functions
    var initUpdateDetails = function () {
        // Init form validation rules
        var validator = FormValidation.formValidation(form, {
            fields: {
                name: {
                    validators: {
                        notEmpty: {
                            message: "Nama wajib diisi",
                        },
                        stringLength: {
                            min: 2,
                            max: 255,
                            message: "Nama harus antara 2-255 karakter",
                        },
                    },
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: "Email wajib diisi",
                        },
                        emailAddress: {
                            message: "Format email tidak valid",
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
            e.preventDefault();

            // Validate form before submit
            if (validator) {
                validator.validate().then(function (status) {
                    console.log("Validation status:", status);

                    if (status == "Valid") {
                        // Show loading indication
                        submitButton.setAttribute("data-kt-indicator", "on");
                        submitButton.disabled = true;

                        // Get user ID from current URL or data attribute
                        const userId = getUserIdFromUrl();

                        if (!userId) {
                            console.error("User ID not found");
                            submitButton.removeAttribute("data-kt-indicator");
                            submitButton.disabled = false;

                            Swal.fire({
                                text: "User ID tidak ditemukan",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, mengerti!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            });
                            return;
                        }

                        // Prepare form data
                        const formData = new FormData(form);
                        formData.append("_method", "PATCH");

                        // Submit form via AJAX
                        $.ajax({
                            url: getUpdateUrl(userId),
                            type: "POST",
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                                Accept: "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                // Remove loading indication
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );
                                submitButton.disabled = false;

                                if (response.success) {
                                    // Show success message
                                    Swal.fire({
                                        text:
                                            response.message ||
                                            "Data berhasil diperbarui!",
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, mengerti!",
                                        customClass: {
                                            confirmButton:
                                                "btn fw-bold btn-primary",
                                        },
                                    }).then(function (result) {
                                        if (result.isConfirmed) {
                                            modal.hide();

                                            // Reload page to show updated data
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    // Show error message
                                    Swal.fire({
                                        text:
                                            response.message ||
                                            "Terjadi kesalahan saat memperbarui data",
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, mengerti!",
                                        customClass: {
                                            confirmButton:
                                                "btn fw-bold btn-primary",
                                        },
                                    });
                                }
                            },
                            error: function (xhr) {
                                // Remove loading indication
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );
                                submitButton.disabled = false;

                                let message =
                                    "Terjadi kesalahan saat memperbarui data";

                                if (xhr.responseJSON) {
                                    if (xhr.responseJSON.message) {
                                        message = xhr.responseJSON.message;
                                    } else if (xhr.responseJSON.errors) {
                                        // Handle validation errors
                                        const errors = xhr.responseJSON.errors;
                                        message = Object.values(errors)
                                            .flat()
                                            .join("\n");
                                    }
                                }

                                Swal.fire({
                                    text: message,
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, mengerti!",
                                    customClass: {
                                        confirmButton:
                                            "btn fw-bold btn-primary",
                                    },
                                });
                            },
                        });
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
                text: "Yakin ingin membatalkan perubahan?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, batalkan!",
                cancelButtonText: "Tidak, lanjutkan",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                    cancelButton: "btn fw-bold btn-active-light",
                },
            }).then(function (result) {
                if (result.value) {
                    // Reset form
                    form.reset();

                    // Reset validation
                    if (validator) {
                        validator.resetForm();
                    }

                    modal.hide();
                } else if (result.dismiss === "cancel") {
                    Swal.fire({
                        text: "Perubahan belum dibatalkan!",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, mengerti!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
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
                text: "Yakin ingin menutup tanpa menyimpan?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, tutup!",
                cancelButtonText: "Tidak, lanjutkan",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                    cancelButton: "btn fw-bold btn-active-light",
                },
            }).then(function (result) {
                if (result.value) {
                    // Reset form
                    form.reset();

                    // Reset validation
                    if (validator) {
                        validator.resetForm();
                    }

                    modal.hide();
                }
            });
        });
    };

    // Helper function to get user ID from URL
    var getUserIdFromUrl = function () {
        // First try to get from form data attribute
        const userIdElement =
            form.querySelector("[data-user-id]") ||
            document.querySelector("[data-user-id]");
        if (userIdElement) {
            return userIdElement.getAttribute("data-user-id");
        }

        // Fallback to URL parsing
        const urlParts = window.location.pathname.split("/");
        const userIndex = urlParts.indexOf("user");

        if (userIndex !== -1 && urlParts[userIndex + 1]) {
            return urlParts[userIndex + 1];
        }

        return null;
    };

    // Helper function to get update URL
    var getUpdateUrl = function (userId) {
        // Use Laravel route helper if available
        if (typeof window.route === "function") {
            return window.route("setting.user.update.detail", userId);
        }

        // Fallback to manual URL construction
        return `/setting/user/${userId}/detail`;
    };

    // Reset form when modal is hidden
    element.addEventListener("hidden.bs.modal", function () {
        // Reset form
        form.reset();

        // Reset validation if it exists
        if (typeof validator !== "undefined" && validator) {
            validator.resetForm();
        }

        // Reset image input if exists
        const imageInput = form.querySelector('[data-kt-image-input="true"]');
        if (imageInput && typeof KTImageInput !== "undefined") {
            const imageInputInstance = KTImageInput.getInstance(imageInput);
            if (imageInputInstance) {
                imageInputInstance.goDefault();
            }
        }
    });

    // Initialize image input when modal is shown
    element.addEventListener("shown.bs.modal", function () {
        // Initialize image input if it exists and KTImageInput is available
        const imageInput = form.querySelector('[data-kt-image-input="true"]');
        if (imageInput && typeof KTImageInput !== "undefined") {
            if (!imageInput.hasAttribute("data-kt-image-input-initialized")) {
                new KTImageInput(imageInput);
                imageInput.setAttribute(
                    "data-kt-image-input-initialized",
                    "true"
                );
            }
        }
    });

    // Public methods
    return {
        init: function () {
            if (!element) {
                console.warn("Update details modal not found");
                return;
            }

            console.log("Initializing KTUsersUpdateDetailsNew...");
            initUpdateDetails();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdateDetailsNew.init();
});

// Alternative initialization if KTUtil is not available
$(document).ready(function () {
    if (typeof KTUsersUpdateDetailsNew !== "undefined") {
        KTUsersUpdateDetailsNew.init();
    }
});
