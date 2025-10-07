"use strict";

var KTUsersUpdatePassword = (function () {
    const element = document.getElementById("kt_modal_update_password");
    const form = element.querySelector("#kt_modal_update_password_form");
    const modal = new bootstrap.Modal(element);

    var initUpdatePassword = function () {
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
            const currentPassword = form.querySelector(
                '[name="current_password"]'
            ).value;
            const newPassword = form.querySelector(
                '[name="new_password"]'
            ).value;
            const confirmPassword = form.querySelector(
                '[name="confirm_password"]'
            ).value;

            // Reset field errors
            clearFieldErrors();

            // Validasi dasar
            if (!currentPassword) {
                showFieldError(
                    "current_password",
                    "Password saat ini harus diisi"
                );
                return;
            }

            if (!newPassword) {
                showFieldError("new_password", "Password baru harus diisi");
                return;
            }

            if (newPassword.length < 8) {
                showFieldError(
                    "new_password",
                    "Password baru minimal 8 karakter"
                );
                return;
            }

            if (!confirmPassword) {
                showFieldError(
                    "confirm_password",
                    "Konfirmasi password harus diisi"
                );
                return;
            }

            if (newPassword !== confirmPassword) {
                showFieldError(
                    "confirm_password",
                    "Konfirmasi password tidak cocok"
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
                url: getUpdatePasswordUrl(userId),
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
                            response.message || "Password berhasil diperbarui!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, mengerti!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        },
                    }).then(() => {
                        form.reset();
                        modal.hide();
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
                                "Terjadi kesalahan saat memperbarui password."
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
                    form.reset();
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
                    form.reset();
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

    // Helper: buat URL update password Laravel
    var getUpdatePasswordUrl = function (userId) {
        if (typeof window.route === "function") {
            return window.route("setting.user.update.password", userId);
        }
        return `/setting/user/${userId}/password`;
    };

    // Helper: reset tombol submit
    var resetButton = function (button) {
        button.removeAttribute("data-kt-indicator");
        button.disabled = false;
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

        // Hapus pesan error
        const errorMessages = form.querySelectorAll(".invalid-feedback");
        errorMessages.forEach((error) => {
            error.remove();
        });
    };

    return {
        init: function () {
            if (!element) {
                console.warn("Update password modal not found");
                return;
            }
            initUpdatePassword();
        },
    };
})();

// Inisialisasi
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdatePassword.init();
});
