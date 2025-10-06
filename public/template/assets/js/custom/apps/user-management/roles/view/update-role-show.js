"use strict";

var KTRoleUpdateModal = (function () {
    var modal;
    var form;
    var modalInstance;

    // Initialize Update Role
    var initUpdateRole = function () {
        modal = document.getElementById("kt_modal_update_role");
        form = modal.querySelector("#kt_modal_update_role_form");
        modalInstance = new bootstrap.Modal(modal);

        // Set role ID when modal is opened
        const editButton = document.querySelector(
            '[data-bs-target="#kt_modal_update_role"]'
        );
        if (editButton) {
            editButton.addEventListener("click", function () {
                const roleId = this.getAttribute("data-role-id");
                form.setAttribute("data-role-id", roleId);

                // Initialize checkbox states when modal opens
                setTimeout(() => {
                    initializeCheckboxStates();
                }, 100);
            });
        }

        // Initialize modal events
        modal.addEventListener("shown.bs.modal", function () {
            // Update checkbox states when modal is fully shown
            initializeCheckboxStates();
        });

        modal.addEventListener("hidden.bs.modal", function () {
            // Clean up when modal is hidden
            form.reset();
            form.removeAttribute("data-role-id");

            // Clear validation errors
            const errorElements = form.querySelectorAll(
                ".fv-plugins-message-container"
            );
            errorElements.forEach((element) => {
                element.innerHTML = "";
            });

            // Reset checkbox states
            const checkboxes = form.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach((checkbox) => {
                checkbox.indeterminate = false;
            });
        });

        // Form validation
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
                }),
            },
        });

        // Tombol close (ikon X)
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
                    if (result.isConfirmed) {
                        form.reset();
                        modalInstance.hide();
                    }
                });
            });

        // Tombol cancel (discard)
        modal
            .querySelector('[data-kt-roles-modal-action="cancel"]')
            .addEventListener("click", function (e) {
                e.preventDefault();
                Swal.fire({
                    text: "Apakah Anda yakin ingin membatalkan perubahan?",
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
                    if (result.isConfirmed) {
                        form.reset();
                        modalInstance.hide();
                    }
                });
            });

        // Tombol submit
        const submitButton = modal.querySelector(
            '[data-kt-roles-modal-action="submit"]'
        );
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            validator.validate().then(function (status) {
                if (status === "Valid") {
                    submitButton.setAttribute("data-kt-indicator", "on");
                    submitButton.disabled = true;

                    const roleId = form.getAttribute("data-role-id");
                    const formData = new FormData(form);
                    const data = {
                        role_name: formData.get("role_name"),
                        permissions: [],
                    };

                    // Ambil semua checkbox permission yang dicentang (kecuali select all)
                    const checkboxes = form.querySelectorAll(
                        'input[name="permissions[]"]:checked'
                    );
                    checkboxes.forEach((checkbox) => {
                        if (checkbox.value && checkbox.value.trim() !== "") {
                            data.permissions.push(checkbox.value);
                        }
                    });

                    // Debug information
                    console.log("Role ID:", roleId);
                    console.log("Data to send:", data);

                    if (!roleId) {
                        Swal.fire({
                            text: "Role ID tidak ditemukan. Silakan muat ulang halaman.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Oke",
                            customClass: { confirmButton: "btn btn-primary" },
                        });
                        submitButton.removeAttribute("data-kt-indicator");
                        submitButton.disabled = false;
                        return;
                    }

                    const url = `/setting/role/${roleId}`;
                    console.log("Request URL:", url);

                    fetch(url, {
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
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error(
                                    `HTTP error! status: ${response.status}`
                                );
                            }
                            return response.json();
                        })
                        .then((responseData) => {
                            submitButton.removeAttribute("data-kt-indicator");
                            submitButton.disabled = false;

                            if (responseData.success) {
                                Swal.fire({
                                    text: responseData.message,
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Oke, paham!",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                }).then(() => {
                                    modalInstance.hide();
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    text:
                                        responseData.message ||
                                        "Terjadi kesalahan saat memperbarui role.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Oke",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                });
                            }
                        })
                        .catch((error) => {
                            console.error("Fetch Error:", error);
                            submitButton.removeAttribute("data-kt-indicator");
                            submitButton.disabled = false;

                            let errorMessage =
                                "Terjadi kesalahan saat memperbarui role.";

                            if (error.message.includes("404")) {
                                errorMessage =
                                    "Role tidak ditemukan. Silakan muat ulang halaman.";
                            } else if (error.message.includes("403")) {
                                errorMessage =
                                    "Anda tidak memiliki akses untuk memperbarui role ini.";
                            } else if (error.message.includes("422")) {
                                errorMessage =
                                    "Data yang dikirim tidak valid. Silakan periksa kembali.";
                            } else if (error.message.includes("500")) {
                                errorMessage =
                                    "Terjadi kesalahan server. Silakan coba lagi nanti.";
                            }

                            Swal.fire({
                                text: errorMessage,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Oke",
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
                        confirmButtonText: "Oke",
                        customClass: { confirmButton: "btn btn-primary" },
                    });
                }
            });
        });
    };

    // Select all global
    var initSelectAllPermissions = function () {
        const selectAllCheckbox = form.querySelector("#kt_roles_select_all");
        const permissionCheckboxes = form.querySelectorAll(
            'input[name="permissions[]"]'
        );

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener("change", function (e) {
                permissionCheckboxes.forEach((checkbox) => {
                    checkbox.checked = e.target.checked;
                });

                // Juga toggle semua kategori select all
                const categorySelectAlls = form.querySelectorAll(
                    "[data-category-select-all]"
                );
                categorySelectAlls.forEach((catCheckbox) => {
                    catCheckbox.checked = e.target.checked;
                });
            });
        }
    };

    // Select all per kategori
    var initCategorySelectAll = function () {
        const categorySelectAlls = form.querySelectorAll(
            "[data-category-select-all]"
        );

        categorySelectAlls.forEach((categoryCheckbox) => {
            categoryCheckbox.addEventListener("change", function (e) {
                const category = e.target.getAttribute("data-category");
                const checkboxes = form.querySelectorAll(
                    `input[name="permissions[]"][data-category="${category}"]`
                );

                checkboxes.forEach((checkbox) => {
                    checkbox.checked = e.target.checked;
                });

                // Update global select all state
                updateGlobalSelectAllState();
            });
        });

        // Add individual checkbox listeners to update category select all state
        const permissionCheckboxes = form.querySelectorAll(
            'input[name="permissions[]"]'
        );
        permissionCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", function () {
                const category = this.getAttribute("data-category");
                updateCategorySelectAllState(category);
                updateGlobalSelectAllState();
            });
        });
    };

    // Update category select all state based on individual checkboxes
    var updateCategorySelectAllState = function (category) {
        const categoryCheckboxes = form.querySelectorAll(
            `input[name="permissions[]"][data-category="${category}"]`
        );
        const categorySelectAll = form.querySelector(
            `[data-category-select-all][data-category="${category}"]`
        );

        if (categorySelectAll && categoryCheckboxes.length > 0) {
            const checkedCount = form.querySelectorAll(
                `input[name="permissions[]"][data-category="${category}"]:checked`
            ).length;
            categorySelectAll.checked =
                checkedCount === categoryCheckboxes.length;
            categorySelectAll.indeterminate =
                checkedCount > 0 && checkedCount < categoryCheckboxes.length;
        }
    };

    // Update global select all state
    var updateGlobalSelectAllState = function () {
        const allPermissionCheckboxes = form.querySelectorAll(
            'input[name="permissions[]"]'
        );
        const globalSelectAll = form.querySelector("#kt_roles_select_all");

        if (globalSelectAll && allPermissionCheckboxes.length > 0) {
            const checkedCount = form.querySelectorAll(
                'input[name="permissions[]"]:checked'
            ).length;
            globalSelectAll.checked =
                checkedCount === allPermissionCheckboxes.length;
            globalSelectAll.indeterminate =
                checkedCount > 0 &&
                checkedCount < allPermissionCheckboxes.length;
        }
    };

    // Initialize checkbox states
    var initializeCheckboxStates = function () {
        // Update all category select all states
        const categories = new Set();
        form.querySelectorAll('input[name="permissions[]"]').forEach(
            (checkbox) => {
                const category = checkbox.getAttribute("data-category");
                if (category) {
                    categories.add(category);
                }
            }
        );

        categories.forEach((category) => {
            updateCategorySelectAllState(category);
        });

        // Update global select all state
        updateGlobalSelectAllState();
    };

    return {
        init: function () {
            modal = document.getElementById("kt_modal_update_role");
            if (!modal) return;

            form = modal.querySelector("#kt_modal_update_role_form");
            modalInstance = new bootstrap.Modal(modal);

            initUpdateRole();
            initSelectAllPermissions();
            initCategorySelectAll();
        },
    };
})();

document.addEventListener("DOMContentLoaded", function () {
    KTRoleUpdateModal.init();
});
