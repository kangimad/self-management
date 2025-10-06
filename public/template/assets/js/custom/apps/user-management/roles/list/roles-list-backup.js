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
            "info": false,
            'order': [],
            "pageLength": 10,
            "lengthChange": false,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": window.location.href,
                "type": "GET",
                "data": function(d) {
                    // Add custom parameters if needed
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            },
            "columns": [
                { 
                    "data": "id", 
                    "orderable": false,
                    "render": function(data, type, row) {
                        return `<div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="${data}" />
                                </div>`;
                    }
                },
                { 
                    "data": "name",
                    "render": function(data, type, row) {
                        return `<div class="d-flex flex-column">
                                    <a href="/setting/role/${row.id}" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                    <span>${row.permissions_count} permissions</span>
                                </div>`;
                    }
                },
                { "data": "users_count" },
                { "data": "created_at" },
                { 
                    "data": "actions", 
                    "orderable": false,
                    "render": function(data, type, row) {
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
                    }
                }
            ],
            'columnDefs': [
                { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
                { orderable: false, targets: 4 }, // Disable ordering on column 4 (actions)
            ],
            "drawCallback": function(settings) {
                // Reinitialize menus after table redraw
                KTMenu.createInstances();
                // Reinitialize event handlers
                handleDeleteRows();
                handleEditRows();
            }
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
        // Select all delete buttons
        const deleteButtons = document.querySelectorAll(
            '[data-kt-roles-table-filter="delete_row"]'
        );

        deleteButtons.forEach((d) => {
            // Delete button on click
            d.addEventListener("click", function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest("tr");

                // Get role name
                const roleName = parent.querySelectorAll("td")[1].innerText;

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
                        // Get role ID from data attribute
                        const roleId = d.getAttribute("data-role-id");

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
                                        // Remove current row
                                        datatable
                                            .row($(parent))
                                            .remove()
                                            .draw();
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
            });
        });
    };

    // Edit role
    var handleEditRows = function () {
        // Select all edit buttons
        const editButtons = document.querySelectorAll(
            '[data-kt-roles-table-filter="edit_row"]'
        );

        editButtons.forEach((e) => {
            // Edit button on click
            e.addEventListener("click", function (event) {
                event.preventDefault();

                const roleId = e.getAttribute("data-role-id");

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
            });
        });
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

    // Public methods
    return {
        init: function () {
            table = document.querySelector("#kt_roles_table");

            if (!table) {
                return;
            }

            initRoleTable();
            handleSearchDatatable();
            handleDeleteRows();
            handleEditRows();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTRolesList.init();
});
