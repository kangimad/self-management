"use strict";

var KTUsersUpdateRole = (function () {
    const element = document.getElementById("kt_modal_update_role");
    const form = element.querySelector("#kt_modal_update_role_form");
    const modal = new bootstrap.Modal(element);

    var initUpdateRole = function () {
        const submitButton = element.querySelector(
            '[data-kt-users-modal-action="submit"]'
        );
        const cancelButton = element.querySelector(
            '[data-kt-users-modal-action="cancel"]'
        );
        const closeButton = element.querySelector(
            '[data-kt-users-modal-action="close"]'
        );

        // === SUBMIT ===
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

            // Validasi form sederhana
            const selectedRoles = form.querySelectorAll(
                'input[name="user_role[]"]:checked'
            );

            // Reset field errors
            clearFieldErrors();

            // Validasi dasar
            if (selectedRoles.length === 0) {
                showFieldError(
                    "user_role",
                    "Pilih minimal satu role untuk pengguna"
                );
                return;
            }

            // Show loading
            submitButton.setAttribute("data-kt-indicator", "on");
            submitButton.disabled = true;

            const userId = getUserIdFromUrl();
            if (!userId) {
                showError("User ID tidak ditemukan");
                resetButton(submitButton);
                return;
            }

            const formData = new FormData(form);
            formData.append("_method", "PATCH");

            $.ajax({
                url: getUpdateRoleUrl(userId),
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    resetButton(submitButton);

                    Swal.fire({
                        text:
                            response.message ||
                            "Role pengguna berhasil diperbarui!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, mengerti!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        },
                    }).then(() => {
                        modal.hide();

                        // Reload page to update role display
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
                    });
                },
                error: function (xhr) {
                    resetButton(submitButton);

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        // Laravel validation error
                        const errors = xhr.responseJSON.errors;
                        showFieldErrors(errors);

                        // Tampilkan pesan error pertama di SweetAlert
                        const firstError = Object.values(errors)[0][0];
                        showError(firstError);
                    } else {
                        showError(
                            xhr.responseJSON?.message ||
                                "Terjadi kesalahan saat memperbarui role."
                        );
                    }
                },
            });
        });

        // === CANCEL ===
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
                    resetForm();
                    clearFieldErrors();
                    modal.hide();
                }
            });
        });

        // === CLOSE ===
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
                    resetForm();
                    clearFieldErrors();
                    modal.hide();
                }
            });
        });
    };

    // Helper: ambil user ID dari URL
    var getUserIdFromUrl = function () {
        const userIdElement =
            form.querySelector("[data-user-id]") ||
            document.querySelector("[data-user-id]");
        if (userIdElement) return userIdElement.getAttribute("data-user-id");

        const urlParts = window.location.pathname.split("/");
        const userIndex = urlParts.indexOf("user");
        if (userIndex !== -1 && urlParts[userIndex + 1]) {
            return urlParts[userIndex + 1];
        }
        return null;
    };

    // Helper: buat URL update role Laravel
    var getUpdateRoleUrl = function (userId) {
        if (typeof window.route === "function") {
            return window.route("setting.user.update.role", userId);
        }
        return `/setting/user/${userId}/role`;
    };

    // Helper: reset tombol submit
    var resetButton = function (button) {
        button.removeAttribute("data-kt-indicator");
        button.disabled = false;
    };

    // Helper: reset form ke kondisi awal
    var resetForm = function () {
        // Tidak menggunakan form.reset() karena kita ingin mempertahankan original state
        // Ambil original state dari data attribute atau server state
        const originalRoleIds = getOriginalUserRoles();
        const checkboxes = form.querySelectorAll('input[name="user_role[]"]');

        checkboxes.forEach((checkbox) => {
            if (originalRoleIds.includes(parseInt(checkbox.value))) {
                checkbox.checked = true;
            } else {
                checkbox.checked = false;
            }
        });
    };

    // Helper: ambil original role user
    var getOriginalUserRoles = function () {
        // Coba ambil dari data attribute di form atau element lain
        const roleDataElement = document.querySelector("[data-user-roles]");
        if (roleDataElement) {
            const rolesString = roleDataElement.getAttribute("data-user-roles");
            try {
                return JSON.parse(rolesString);
            } catch (e) {
                console.warn("Failed to parse user roles data:", e);
            }
        }

        // Fallback: ambil dari checkbox yang checked saat page load
        const checkedBoxes = form.querySelectorAll(
            'input[name="user_role[]"]:checked'
        );
        return Array.from(checkedBoxes).map((cb) => parseInt(cb.value));
    };

    // Helper: tampilkan pesan error umum
    var showError = function (message) {
        Swal.fire({
            text: message,
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, mengerti!",
            customClass: {
                confirmButton: "btn fw-bold btn-primary",
            },
        });
    };

    // Helper: tampilkan error di bawah input tertentu
    var showFieldError = function (fieldName, message) {
        // Untuk role, tampilkan error di atas daftar role
        if (fieldName === "user_role") {
            const roleContainer = form.querySelector(".fv-row");
            if (roleContainer) {
                // Hapus error lama jika ada
                const existingError =
                    roleContainer.querySelector(".invalid-feedback");
                if (existingError) {
                    existingError.remove();
                }

                // Tambah error baru
                const errorDiv = document.createElement("div");
                errorDiv.classList.add("invalid-feedback", "d-block");
                errorDiv.innerText = message;

                const label = roleContainer.querySelector("label");
                if (label) {
                    label.insertAdjacentElement("afterend", errorDiv);
                } else {
                    roleContainer.insertBefore(
                        errorDiv,
                        roleContainer.firstChild
                    );
                }

                // Tambah class error ke container
                roleContainer.classList.add("has-error");
            }
        } else {
            const input = form.querySelector(`[name="${fieldName}"]`);
            if (input) {
                input.classList.add("is-invalid");

                // Hapus error lama jika ada
                const existingError =
                    input.parentNode.querySelector(".invalid-feedback");
                if (existingError) {
                    existingError.remove();
                }

                // Tambah error baru
                const errorDiv = document.createElement("div");
                errorDiv.classList.add("invalid-feedback");
                errorDiv.innerText = message;
                input.parentNode.appendChild(errorDiv);
            }
        }
    };

    // Helper: tampilkan error di bawah input (dari Laravel validation)
    var showFieldErrors = function (errors) {
        Object.keys(errors).forEach((field) => {
            showFieldError(field, errors[field][0]);
        });
    };

    // Helper: bersihkan semua field errors
    var clearFieldErrors = function () {
        // Hapus class is-invalid
        const invalidInputs = form.querySelectorAll(".is-invalid");
        invalidInputs.forEach((input) => {
            input.classList.remove("is-invalid");
        });

        // Hapus class has-error dari container
        const errorContainers = form.querySelectorAll(".has-error");
        errorContainers.forEach((container) => {
            container.classList.remove("has-error");
        });

        // Hapus pesan error
        const errorMessages = form.querySelectorAll(".invalid-feedback");
        errorMessages.forEach((error) => {
            error.remove();
        });
    };

    return {
        init: function () {
            if (!element) {
                console.warn("Update role modal not found");
                return;
            }
            initUpdateRole();
        },
    };
})();

// Inisialisasi
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdateRole.init();
});
