"use strict";

// Class definition for Role Update
var KTRoleUpdateModal = (function () {
    // Define shared variables
    var modal;
    var form;
    var modalInstance;

    // Private functions
    var initUpdateRole = function () {
        // Get elements
        modal = document.getElementById("kt_modal_update_role");
        form = modal.querySelector("#kt_modal_update_role_form");
        modalInstance = new bootstrap.Modal(modal);

        // Init form validation
        var validator = FormValidation.formValidation(form, {
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

        // Handle close button
        modal
            .querySelector('[data-kt-roles-modal-action="close"]')
            .addEventListener("click", function (e) {
                e.preventDefault();
                Swal.fire({
                    text: "Apakah Anda yakin ingin menutup?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, tutup!",
                    cancelButtonText: "Tidak, kembali",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light",
                    },
                }).then(function (result) {
                    if (result.value) {
                        modalInstance.hide();
                    }
                });
            });

        // Handle cancel button
        modal
            .querySelector('[data-kt-roles-modal-action="cancel"]')
            .addEventListener("click", function (e) {
                e.preventDefault();
                Swal.fire({
                    text: "Apakah Anda yakin ingin membatalkan?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, batalkan!",
                    cancelButtonText: "Tidak, kembali",
                    customClass: {
                        confirmButton: "btn btn-primary",
                        cancelButton: "btn btn-active-light",
                    },
                }).then(function (result) {
                    if (result.value) {
                        form.reset();
                        modalInstance.hide();
                    }
                });
            });

        // Handle submit button
        const submitButton = modal.querySelector(
            '[data-kt-roles-modal-action="submit"]'
        );
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            if (validator) {
                validator.validate().then(function (status) {
                    if (status === "Valid") {
                        // Show loading indicator
                        submitButton.setAttribute("data-kt-indicator", "on");
                        submitButton.disabled = true;

                        // Get role ID from form attribute
                        const roleId = form.getAttribute("data-role-id");

                        // Collect form data
                        const formData = new FormData(form);
                        const data = {
                            role_name: formData.get("role_name"),
                            permissions: [],
                        };

                        // Collect selected permissions
                        const checkboxes = form.querySelectorAll(
                            'input[type="checkbox"]:checked'
                        );
                        checkboxes.forEach((checkbox) => {
                            if (checkbox.value && checkbox.value !== "1") {
                                // Skip "select all" checkbox
                                data.permissions.push(checkbox.value);
                            }
                        });

                        // Make AJAX request
                        fetch(`/setting/role/${roleId}`, {
                            method: "PUT",
                            headers: {
                                "X-CSRF-TOKEN": document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content"),
                                "Content-Type": "application/json",
                                Accept: "application/json",
                            },
                            body: JSON.stringify(data),
                        })
                            .then((response) => response.json())
                            .then((responseData) => {
                                // Remove loading indicator
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );
                                submitButton.disabled = false;

                                if (responseData.success) {
                                    Swal.fire({
                                        text: responseData.message,
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    }).then(function () {
                                        modalInstance.hide();
                                        // Reload page to show updated data
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        text:
                                            responseData.message ||
                                            "Terjadi kesalahan saat memperbarui role.",
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton: "btn btn-primary",
                                        },
                                    });
                                }
                            })
                            .catch((error) => {
                                console.error("Error:", error);
                                // Remove loading indicator
                                submitButton.removeAttribute(
                                    "data-kt-indicator"
                                );
                                submitButton.disabled = false;

                                Swal.fire({
                                    text: "Terjadi kesalahan saat memperbarui role.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                });
                            });
                    } else {
                        Swal.fire({
                            text: "Mohon periksa kembali data yang diisi.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                    }
                });
            }
        });
    };

    // Handle select all permissions
    var initSelectAllPermissions = function () {
        const selectAllCheckbox = form.querySelector("#kt_roles_select_all");
        const permissionCheckboxes = form.querySelectorAll(
            'input[type="checkbox"]:not(#kt_roles_select_all)'
        );

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener("change", function (e) {
                permissionCheckboxes.forEach((checkbox) => {
                    checkbox.checked = e.target.checked;
                });
            });
        }
    };

    // Handle edit button click
    var handleEditButton = function () {
        const editButton = document.querySelector(
            '[data-kt-role-edit-btn="true"]'
        );
        if (!editButton) return;

        editButton.addEventListener("click", function (e) {
            e.preventDefault();

            const roleId = this.getAttribute("data-role-id");

            // Fetch role data
            fetch(`/setting/role/${roleId}/edit`, {
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Populate modal with role data
                        populateEditModal(data.data);

                        // Show modal
                        modalInstance.show();
                    } else {
                        Swal.fire({
                            text:
                                data.message ||
                                "Terjadi kesalahan saat mengambil data role.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    Swal.fire({
                        text: "Terjadi kesalahan saat mengambil data role.",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        },
                    });
                });
        });
    };

    // Populate edit modal with data
    var populateEditModal = function (data) {
        // Set role name
        form.querySelector('input[name="role_name"]').value = data.role.name;

        // Set role ID for form submission
        form.setAttribute("data-role-id", data.role.id);

        // Clear all permission checkboxes first
        const checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach((checkbox) => {
            checkbox.checked = false;
        });

        // Check permissions that are assigned to this role
        if (data.rolePermissions && data.rolePermissions.length > 0) {
            data.rolePermissions.forEach((permission) => {
                const checkbox = form.querySelector(
                    `input[value="${permission}"]`
                );
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        }
    };

    // Public methods
    return {
        init: function () {
            if (!modal) return;

            initUpdateRole();
            initSelectAllPermissions();
            handleEditButton();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTRoleUpdateModal.init();
});

// Alternative initialization
$(document).ready(function () {
    if (typeof KTRoleUpdateModal !== "undefined") {
        KTRoleUpdateModal.init();
    }
});
