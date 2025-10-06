"use strict";

var KTUsersUpdateDetailsNew = (function () {
    const element = document.getElementById("kt_modal_update_details");
    const form = element.querySelector("#kt_modal_update_user_form");
    const modal = new bootstrap.Modal(element);

    var initUpdateDetails = function () {
        const submitButton = element.querySelector('[data-kt-users-modal-action="submit"]');
        const cancelButton = element.querySelector('[data-kt-users-modal-action="cancel"]');
        const closeButton = element.querySelector('[data-kt-users-modal-action="close"]');

        // === SUBMIT ===
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();

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
                url: getUpdateUrl(userId),
                type: "POST",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    resetButton(submitButton);

                    Swal.fire({
                        text: response.message || "Data berhasil diperbarui!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, mengerti!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        },
                    }).then(() => {
                        modal.hide();
                        window.location.reload();
                    });
                },
                error: function (xhr) {
                    resetButton(submitButton);

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        // Laravel validation error
                        const errors = xhr.responseJSON.errors;
                        const messages = Object.values(errors).flat().join("\n");

                        Swal.fire({
                            text: messages,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, mengerti!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });

                        // Optional: tampilkan error di bawah input
                        showFieldErrors(errors);
                    } else {
                        showError(
                            xhr.responseJSON?.message ||
                                "Terjadi kesalahan saat memperbarui data."
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
                    modal.hide();
                }
            });
        });
    };

    // Helper: ambil user ID dari URL
    var getUserIdFromUrl = function () {
        const userIdElement = form.querySelector("[data-user-id]") || document.querySelector("[data-user-id]");
        if (userIdElement) return userIdElement.getAttribute("data-user-id");

        const urlParts = window.location.pathname.split("/");
        const userIndex = urlParts.indexOf("user");
        if (userIndex !== -1 && urlParts[userIndex + 1]) {
            return urlParts[userIndex + 1];
        }
        return null;
    };

    // Helper: buat URL update Laravel
    var getUpdateUrl = function (userId) {
        if (typeof window.route === "function") {
            return window.route("setting.user.update.detail", userId);
        }
        return `/setting/user/${userId}/detail`;
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

    // Helper: tampilkan error di bawah input (opsional)
    var showFieldErrors = function (errors) {
        Object.keys(errors).forEach((field) => {
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                const errorDiv = document.createElement("div");
                errorDiv.classList.add("invalid-feedback");
                errorDiv.innerText = errors[field][0];
                if (!input.classList.contains("is-invalid")) {
                    input.classList.add("is-invalid");
                    input.parentNode.appendChild(errorDiv);
                }
            }
        });
    };

    return {
        init: function () {
            if (!element) {
                console.warn("Update details modal not found");
                return;
            }
            initUpdateDetails();
        },
    };
})();

// Inisialisasi
KTUtil.onDOMContentLoaded(function () {
    KTUsersUpdateDetailsNew.init();
});
