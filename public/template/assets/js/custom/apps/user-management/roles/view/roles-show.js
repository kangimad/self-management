"use strict";

// Class definition
var KTRolesViewTable = (function () {
    // Define shared variables
    var datatable;
    var table;

    // Private functions
    var initRoleUsersTable = function () {
        // Get role ID from URL or data attribute
        const roleId = window.location.pathname.split("/").pop();

        // Check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable(table)) {
            $(table).DataTable().destroy();
        }

        // Init datatable with AJAX
        datatable = $(table).DataTable({
            info: true,
            order: [[2, "asc"]],
            pageLength: 10,
            lengthChange: true,
            lengthMenu: [10, 25, 50, 100],
            processing: true,
            serverSide: true,
            ajax: {
                url: `/setting/role/${roleId}/users`,
                type: "GET",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
                data: function (d) {
                    // Add custom search parameter to the default DataTables search
                    const customSearch = $(
                        '[data-kt-roles-table-filter="search"]'
                    ).val();
                    if (customSearch) {
                        d.search.value = customSearch;
                    }
                    return d;
                },
                error: function (xhr, status, error) {
                    console.error("DataTable AJAX Error:", {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        error: error,
                    });
                },
            },
            columns: [
                {
                    data: null,
                    orderable: false,
                    render: function (data, type, row) {
                        return `<div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="${row.id}" />
                                </div>`;
                    },
                },
                {
                    data: null,
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    },
                },
                {
                    data: "name",
                    render: function (data, type, row) {
                        const imageContent = row.image_url
                            ? `<img src="${row.image_url}" alt="${data}" />`
                            : `<div class="symbol-label fs-3 bg-light-primary text-primary">${row.initials}</div>`;

                        return `<div class="d-flex align-items-center">
                                    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                        <a href="/setting/user/${row.id}">
                                            <div class="symbol-label">
                                                ${imageContent}
                                            </div>
                                        </a>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="/setting/user/${row.id}" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                        <span class="text-muted">${row.email}</span>
                                    </div>
                                </div>`;
                    },
                },
                {
                    data: "login_status",
                    render: function (data, type, row) {
                        if (row.is_online) {
                            return '<span class="badge badge-light-success">Online</span>';
                        } else {
                            return '<span class="badge badge-light-secondary">Offline</span>';
                        }
                    },
                },
                {
                    data: "created_at",
                    render: function (data, type, row) {
                        return `<div class="text-gray-800 small">${data}</div>
                                <div class="text-muted small">${row.created_at_formatted}</div>`;
                    },
                },
                {
                    data: null,
                    orderable: false,
                    render: function (data, type, row) {
                        return `<a href="#" class="btn btn-sm btn-light btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                <i class="ki-outline ki-down fs-5 m-0"></i></a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="/setting/user/${row.id}" class="menu-link px-3">View</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-user-table-filter="remove_role" data-user-id="${row.id}">Remove Role</a>
                                    </div>
                                </div>`;
                    },
                },
            ],
            columnDefs: [
                { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
                { orderable: false, targets: 5 }, // Disable ordering on column 5 (actions)
            ],
            drawCallback: function (settings) {
                // Reinitialize menus after table redraw
                KTMenu.createInstances();

                // Re-init functions on every table re-draw
                initToggleToolbar();
                toggleToolbars();

                // Handle master checkbox
                const masterCheckbox = document.querySelector(
                    '#kt_roles_view_table thead input[type="checkbox"]'
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

    // Search Datatable
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector(
            '[data-kt-roles-table-filter="search"]'
        );

        if (!filterSearch) {
            console.error("Search input not found");
            return;
        }

        filterSearch.addEventListener("keyup", function (e) {
            // Add a small delay to avoid too many requests
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(function () {
                if (datatable) {
                    datatable.ajax.reload();
                } else {
                    console.error("DataTable not initialized");
                }
            }, 300);
        });
    };

    // Handle Remove Role action
    var handleRemoveRole = function () {
        $(table).on(
            "click",
            '[data-kt-user-table-filter="remove_role"]',
            function (e) {
                e.preventDefault();

                const userId = $(this).attr("data-user-id");
                const roleId = window.location.pathname.split("/").pop();
                const parent = $(this).closest("tr");
                const rowData = datatable.row(parent).data();
                const userName = rowData ? rowData.name : "this user";

                Swal.fire({
                    text: `Apakah Anda yakin ingin menghapus role dari ${userName}?`,
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
                        // Make POST request to remove user from role
                        fetch(`/setting/role/remove-user`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute("content"),
                                "Content-Type": "application/json",
                                Accept: "application/json",
                            },
                            body: JSON.stringify({
                                user_id: parseInt(userId),
                                role_id: parseInt(roleId),
                            }),
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
                                        datatable.ajax.reload(function () {
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
                    }
                });
            }
        );
    };

    // Init toggle toolbar
    var initToggleToolbar = function () {
        const checkboxes = table.querySelectorAll('[type="checkbox"]');
        const deleteSelected = document.querySelector(
            '[data-kt-view-roles-table-select="delete_selected"]'
        );

        if (!deleteSelected) return;

        checkboxes.forEach((c) => {
            c.addEventListener("click", function () {
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });

        deleteSelected.addEventListener("click", function () {
            Swal.fire({
                text: "Apakah Anda yakin ingin menghapus role dari user yang dipilih?",
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
                    const checkedBoxes = table.querySelectorAll(
                        'tbody [type="checkbox"]:checked'
                    );

                    if (checkedBoxes.length === 0) {
                        Swal.fire({
                            text: "Tidak ada user yang dipilih.",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                        return;
                    }

                    const userIds = [];
                    checkedBoxes.forEach((checkbox) => {
                        userIds.push(checkbox.value);
                    });

                    // Process removal for multiple users
                    const roleId = window.location.pathname.split("/").pop();

                    fetch(`/setting/role/remove-multiple-users`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                            "Content-Type": "application/json",
                            Accept: "application/json",
                        },
                        body: JSON.stringify({
                            user_ids: userIds.map((id) => parseInt(id)),
                            role_id: parseInt(roleId),
                        }),
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            const summary = data.summary || {};
                            let message = data.message || "Proses selesai.";

                            Swal.fire({
                                text: message,
                                icon:
                                    summary.failed > 0 ? "warning" : "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            }).then(function () {
                                // Reset master checkbox
                                const masterCheckbox = document.querySelector(
                                    '#kt_roles_view_table thead input[type="checkbox"]'
                                );
                                if (masterCheckbox) {
                                    masterCheckbox.checked = false;
                                }

                                datatable.ajax.reload(function () {
                                    toggleToolbars();
                                });
                            });
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                            Swal.fire({
                                text: "Terjadi kesalahan saat memproses permintaan.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            });
                        });
                }
            });
        });
    };

    // Toggle toolbars
    var toggleToolbars = function () {
        const toolbarBase = document.querySelector(
            '[data-kt-view-roles-table-toolbar="base"]'
        );
        const toolbarSelected = document.querySelector(
            '[data-kt-view-roles-table-toolbar="selected"]'
        );
        const selectedCount = document.querySelector(
            '[data-kt-view-roles-table-select="selected_count"]'
        );

        if (!toolbarBase || !toolbarSelected || !selectedCount) return;

        const allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');
        let checkedState = false;
        let count = 0;

        allCheckboxes.forEach((c) => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

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
            table = document.querySelector("#kt_roles_view_table");

            if (!table) {
                return;
            }

            initRoleUsersTable();
            initToggleToolbar();
            handleSearchDatatable();
            handleRemoveRole();
        },
        refresh: function () {
            if (datatable) {
                datatable.ajax.reload();
            }
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTRolesViewTable.init();
});

// Alternative initialization
$(document).ready(function () {
    if (typeof KTRolesViewTable !== "undefined") {
        KTRolesViewTable.init();
    }
});
