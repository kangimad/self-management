"use strict";

// Class definition
var KTRolesList = (function () {
    // Define shared variables
    var datatable;
    var table;

    // Private functions
    var initRoleTable = function () {
        // Init datatable with AJAX --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            info: false,
            order: [[2, "asc"]],
            pageLength: 10,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            processing: true,
            serverSide: true,
            ajax: {
                url: window.location.href,
                type: "GET",
                data: function (d) {
                    // Add custom parameters if needed
                    d._token = $('meta[name="csrf-token"]').attr("content");
                },
            },
            columns: [
                {
                    data: "id",
                    orderable: false,
                    render: function (data, type, row) {
                        return `<div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="${data}" />
                                </div>`;
                    },
                },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                {
                    data: "name",
                    render: function (data, type, row) {
                        return `<div class="d-flex flex-column">
                                    <a href="/setting/role/${row.id}" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                    <span>${row.permissions_count} permissions</span>
                                </div>`;
                    },
                },
                { data: "users_count" },
                { data: "created_at" },
                {
                    data: "actions",
                    orderable: false,
                    render: function (data, type, row) {
                        return `<a href="#" class="btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <i class="ki-outline ki-down fs-5 ms-1"></i></a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="/setting/role/${row.id}" class="menu-link px-3">View</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-roles-table-filter="edit_row" data-role-id="${row.id}">Edit</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-roles-table-filter="delete_row" data-role-id="${row.id}">Delete</a>
                                    </div>
                                </div>`;
                    },
                },
            ],
            columnDefs: [
                { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
                { orderable: false, targets: 1 }, // Disable ordering on column 0 (#)
                { orderable: false, targets: 4 }, // Disable ordering on column 4 (actions)
            ],
            drawCallback: function (settings) {
                // Reinitialize menus after table redraw
                KTMenu.createInstances();

                // Re-init functions on every table re-draw
                initToggleToolbar();
                toggleToolbars();

                // Handle master checkbox
                const masterCheckbox = document.querySelector(
                    '#kt_roles_table thead input[type="checkbox"]'
                );
                if (masterCheckbox) {
                    masterCheckbox.addEventListener("change", function () {
                        const isChecked = this.checked;
                        const checkboxes = table.querySelectorAll(
                            'tbody input[type="checkbox"]'
                        );
                        checkboxes.forEach((checkbox) => {
                            checkbox.checked = isChecked;
                        });
                        toggleToolbars();
                    });
                }
            },
        });
    };

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector(
            '[data-kt-roles-table-filter="search"]'
        );
        filterSearch.addEventListener("keyup", function (e) {
            datatable.search(e.target.value).draw();
        });
    };

    // Delete role
    var handleDeleteRows = function () {
        // Use event delegation for dynamically loaded content
        $(table).off("click", '[data-kt-roles-table-filter="delete_row"]');
        $(table).on(
            "click",
            '[data-kt-roles-table-filter="delete_row"]',
            function (e) {
                e.preventDefault();

                const roleId = $(this).attr("data-role-id");
                const parent = $(this).closest("tr");

                // Get role name from DataTable data
                const rowData = datatable.row(parent).data();
                const roleName = rowData ? rowData.name : "this role";

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Apakah Anda yakin ingin menghapus " + roleName + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Tidak, batalkan",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary",
                    },
                }).then(function (result) {
                    if (result.value) {
                        // Make DELETE request
                        fetch(`/setting/role/${roleId}`, {
                            method: "DELETE",
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
                                        // Reload DataTable
                                        datatable.ajax.reload(function () {
                                            // Ensure toolbars are properly toggled after reload
                                            toggleToolbars();
                                        });
                                    });
                                } else {
                                    Swal.fire({
                                        text:
                                            data.message ||
                                            "Terjadi kesalahan saat menghapus role.",
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
                                Swal.fire({
                                    text: "Terjadi kesalahan saat menghapus role.",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton:
                                            "btn fw-bold btn-primary",
                                    },
                                });
                            });
                    } else if (result.dismiss === "cancel") {
                        Swal.fire({
                            text: roleName + " tidak dihapus.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                    }
                });
            }
        );
    };

    // Edit role
    var handleEditRows = function () {
        // Use event delegation for dynamically loaded content
        $(table).off("click", '[data-kt-roles-table-filter="edit_row"]');
        $(table).on(
            "click",
            '[data-kt-roles-table-filter="edit_row"]',
            function (e) {
                e.preventDefault();

                const roleId = $(this).attr("data-role-id");

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
                            // Populate edit modal with role data
                            populateEditModal(data.data);

                            // Show edit modal
                            const editModal = document.querySelector(
                                "#kt_modal_update_role"
                            );
                            const modal = new bootstrap.Modal(editModal);
                            modal.show();
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
            }
        );
    };

    // Populate edit modal
    var populateEditModal = function (data) {
        const form = document.querySelector("#kt_modal_update_role_form");

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
        data.rolePermissions.forEach((permission) => {
            const checkbox = form.querySelector(`input[value="${permission}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    };

    // Init toggle toolbar
    var initToggleToolbar = function () {
        // Toggle selected action toolbar
        // Select all checkboxes
        const checkboxes = table.querySelectorAll('[type="checkbox"]');

        // Select elements
        const deleteSelected = document.querySelector(
            '[data-kt-roles-table-select="delete_selected"]'
        );

        // Toggle delete selected toolbar
        checkboxes.forEach((c) => {
            // Checkbox on click event
            c.addEventListener("click", function () {
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });

        // Deleted selected rows
        deleteSelected.addEventListener("click", function () {
            // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
            Swal.fire({
                text: "Apakah Anda yakin ingin menghapus role yang dipilih?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batalkan",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary",
                },
            }).then(function (result) {
                if (result.value) {
                    // Get selected checkboxes
                    const checkedBoxes = table.querySelectorAll(
                        'tbody [type="checkbox"]:checked'
                    );

                    if (checkedBoxes.length === 0) {
                        Swal.fire({
                            text: "Tidak ada role yang dipilih.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                        return;
                    }

                    // Collect role IDs
                    const roleIds = [];
                    checkedBoxes.forEach((checkbox) => {
                        roleIds.push(checkbox.value);
                    });

                    // Make DELETE request
                    fetch("/setting/role/", {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                            "Content-Type": "application/json",
                            Accept: "application/json",
                        },
                        body: JSON.stringify({
                            role_ids: roleIds,
                        }),
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                // Determine icon and title based on whether there are partial errors
                                const alertIcon = data.has_partial_errors
                                    ? "warning"
                                    : "success";
                                const alertTitle = data.has_partial_errors
                                    ? "Sebagian Berhasil"
                                    : "Berhasil";

                                Swal.fire({
                                    title: alertTitle,
                                    text: data.message,
                                    icon: alertIcon,
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton:
                                            "btn fw-bold btn-primary",
                                    },
                                }).then(function () {
                                    // Reset master checkbox
                                    const masterCheckbox =
                                        document.querySelector(
                                            '#kt_roles_table thead input[type="checkbox"]'
                                        );
                                    if (masterCheckbox) {
                                        masterCheckbox.checked = false;
                                    }

                                    // Always reload table if any roles were deleted (even with partial errors)
                                    datatable.ajax.reload(function () {
                                        // Ensure toolbars are properly toggled after reload
                                        toggleToolbars();
                                    });
                                });
                            } else {
                                Swal.fire({
                                    text:
                                        data.message ||
                                        "Terjadi kesalahan saat menghapus role.",
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
                            Swal.fire({
                                text: "Terjadi kesalahan saat menghapus role.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            });
                        });
                } else if (result.dismiss === "cancel") {
                    Swal.fire({
                        text: "Role yang dipilih tidak dihapus.",
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
    };

    // Toggle toolbars
    var toggleToolbars = function () {
        // Define variables
        const toolbarBase = document.querySelector(
            '[data-kt-roles-table-toolbar="base"]'
        );
        const toolbarSelected = document.querySelector(
            '[data-kt-roles-table-toolbar="selected"]'
        );
        const selectedCount = document.querySelector(
            '[data-kt-roles-table-select="selected_count"]'
        );

        // Select refreshed checkbox DOM elements
        const allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');

        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;

        // Count checked boxes
        allCheckboxes.forEach((c) => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        // Toggle toolbars
        if (checkedState) {
            selectedCount.innerHTML = count;
            toolbarBase.classList.add("d-none");
            toolbarSelected.classList.remove("d-none");
        } else {
            toolbarBase.classList.remove("d-none");
            toolbarSelected.classList.add("d-none");
        }
    };

    // Public methods
    return {
        init: function () {
            table = document.querySelector("#kt_roles_table");

            if (!table) {
                return;
            }

            initRoleTable();
            initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();
            handleEditRows();

            // Expose datatable to global scope
            window.datatable = datatable;
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTRolesList.init();
});
