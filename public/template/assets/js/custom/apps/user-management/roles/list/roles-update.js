"use strict";

var KTModalUpdateRole = (function () {
    var submitButton;
    var cancelButton;
    var closeButton;
    var validator;
    var form;
    var modal;

    // Init form validation
    var initForm = function () {
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

            if (!validator) return;

            validator.validate().then(function (status) {
                if (status !== "Valid") return;

                submitButton.setAttribute("data-kt-indicator", "on");
                submitButton.disabled = true;

                const roleId = form.getAttribute("data-role-id");
                const formData = new FormData(form);

                // collect permissions
                form.querySelectorAll('input[name="permissions[]"]:checked').forEach((el) => {
                    formData.append("permissions[]", el.value);
                });

                formData.append("_method", "PUT");

                fetch(`/setting/role/${roleId}`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                        Accept: "application/json",
                    },
                    body: formData,
                })
                    .then((r) => r.json())
                    .then((data) => {
                        submitButton.removeAttribute("data-kt-indicator");
                        submitButton.disabled = false;

                        if (data.success) {
                            // tutup modal
                            const modalInstance = bootstrap.Modal.getInstance(
                                document.querySelector("#kt_modal_update_role")
                            );
                            modalInstance?.hide();

                            Swal.fire({
                                text: data.message,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            }).then(() => location.reload());
                        } else {
                            let msg =
                                data.message ||
                                "Terjadi kesalahan saat mengupdate role.";
                            if (data.errors) {
                                msg = Object.values(data.errors)
                                    .flat()
                                    .join("\n");
                            }

                            Swal.fire({
                                text: msg,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            });
                        }
                    })
                    .catch((err) => {
                        console.error("Error:", err);
                        submitButton.removeAttribute("data-kt-indicator");
                        submitButton.disabled = false;
                        Swal.fire({
                            text: "Terjadi kesalahan saat mengupdate role.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                    });
            });
        });

        // Tombol close (X)
        if (closeButton) {
            closeButton.addEventListener("click", function (e) {
                e.preventDefault();
                form.reset();
                const modalInstance = bootstrap.Modal.getInstance(
                    document.querySelector("#kt_modal_update_role")
                );
                modalInstance?.hide();
            });
        }

        // Tombol batal
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
                        form.reset();
                        const modalInstance = bootstrap.Modal.getInstance(
                            document.querySelector("#kt_modal_update_role")
                        );
                        modalInstance?.hide();
                    }
                });
            });
        }
    };

    // select all permission handler
    var initSelectAll = function () {
        const selectAllCheckboxes = form.querySelectorAll(
            '[id^="kt_roles_select_all_edit_"]'
        );
        selectAllCheckboxes.forEach((selectAll) => {
            selectAll.addEventListener("change", function () {
                const row = this.closest("tr");
                row.querySelectorAll('input[name="permissions[]"]').forEach(
                    (cb) => (cb.checked = this.checked)
                );
            });
        });
    };

    return {
        init: function () {
            const modalEl = document.querySelector("#kt_modal_update_role");
            if (!modalEl) return;

            modal =
                bootstrap.Modal.getInstance(modalEl) ||
                new bootstrap.Modal(modalEl);

            form = document.querySelector("#kt_modal_update_role_form");
            submitButton = document.querySelector("#kt_modal_update_role_submit");
            cancelButton = document.querySelector("#kt_modal_update_role_cancel");
            closeButton = document.querySelector("#kt_modal_update_role_close");

            initForm();
            handleForm();
            initSelectAll();
        },
    };
})();

KTUtil.onDOMContentLoaded(function () {
    if (document.querySelector("#kt_modal_update_role")) {
        KTModalUpdateRole.init();
    }
});
