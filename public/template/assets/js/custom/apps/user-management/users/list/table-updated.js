"use strict";

// Class definition
var KTUsersListDatatable = (function () {
    // Shared variables
    var datatable;
    var filterMonth;
    var filterPayment;
    var table;
    var initialized = false;

    // Private functions
    var initUserTable = function () {
        // Check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable(table)) {
            // Destroy existing DataTable first
            $(table).DataTable().destroy();
        }

        // Skip date ordering for server-side processing
        // The server will handle date formatting and ordering

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            info: true,
            order: [[1, "asc"]],
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
                { orderable: false, targets: 7 }, // Disable ordering on column 7 (actions)
            ],
            processing: true,
            serverSide: true,
            ajax: {
                url: window.route("setting.user.datatable"),
                type: "GET",
                data: function (d) {
                    // Add search parameters
                    d.search = $('[data-kt-user-table-filter="search"]').val();
                    d.role = $('[data-kt-user-table-filter="role"]').val();
                    d.status = $('[data-kt-user-table-filter="status"]').val();
                    d.verified = $(
                        '[data-kt-user-table-filter="verified"]'
                    ).val();
                    return d;
                },
                error: function (xhr, status, error) {
                    console.error("DataTable AJAX Error:", xhr.responseText);
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
                                        <a href="#">
                                            <div class="symbol-label">
                                                ${imageContent}
                                            </div>
                                        </a>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <a href="#" class="text-gray-800 text-hover-primary mb-1">${data}</a>
                                        <span class="text-muted">${row.email}</span>
                                    </div>
                                </div>`;
                    },
                },
                {
                    data: "roles",
                    render: function (data, type, row) {
                        if (!data)
                            return '<span class="badge badge-light-secondary">No Role</span>';
                        return `<span class="badge badge-light-primary">${data}</span>`;
                    },
                },
                {
                    data: "login_status",
                    render: function (data, type, row) {
                        if (row.is_online) {
                            return `<div class="badge badge-light-success">Online</div>`;
                        } else {
                            return `<div class="badge badge-light-secondary">${data}</div>`;
                        }
                    },
                },
                {
                    data: "verified",
                    render: function (data, type, row) {
                        if (data) {
                            return `<div class="badge badge-light-success">Verified</div>`;
                        } else {
                            return `<div class="badge badge-light-warning">Unverified</div>`;
                        }
                    },
                },
                {
                    data: "created_at",
                    render: function (data, type, row) {
                        return `<div class="text-gray-900 fw-bold">${data}</div>
                                <div class="text-muted fs-7">${row.created_at_formatted}</div>`;
                    },
                },
                {
                    data: null,
                    orderable: false,
                    render: function (data, type, row) {
                        return `<button type="button" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <span class="svg-icon svg-icon-5 m-0">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor"/>
                                        </svg>
                                    </span>
                                </button>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="javascript:void(0)" class="menu-link px-3" data-kt-users-table-filter="edit_row" data-user-id="${row.id}">Edit</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="javascript:void(0)" class="menu-link px-3" data-kt-users-table-filter="delete_row" data-user-id="${row.id}">Delete</a>
                                    </div>
                                </div>`;
                    },
                },
            ],
        });

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        datatable.on("draw", function () {
            initToggleToolbar();
            handleRowActions();
            toggleToolbars();

            // Reinitialize KTMenu for dropdown menus in the table with a small delay
            setTimeout(function () {
                if (typeof KTMenu !== "undefined") {
                    try {
                        // Initialize all menus
                        KTMenu.init();

                        // Also try to specifically initialize table menus
                        const menus = table.querySelectorAll(
                            '[data-kt-menu="true"]'
                        );
                        menus.forEach((menu) => {
                            try {
                                if (
                                    !menu.hasAttribute(
                                        "data-kt-menu-initialized"
                                    )
                                ) {
                                    const menuInstance =
                                        KTMenu.getInstance(menu) ||
                                        new KTMenu(menu);
                                    menu.setAttribute(
                                        "data-kt-menu-initialized",
                                        "true"
                                    );
                                }
                            } catch (e) {
                                console.log("Menu init error:", e);
                            }
                        });
                    } catch (e) {
                        console.log("KTMenu init error:", e);
                    }
                }
            }, 100);
        });
    };

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector(
            '[data-kt-user-table-filter="search"]'
        );
        filterSearch.addEventListener("keyup", function (e) {
            datatable.ajax.reload();
        });
    };

    // Filter Datatable
    var handleFilterDatatable = function () {
        // Select filter options
        const filterButton = document.querySelector(
            '[data-kt-user-table-filter="filter"]'
        );
        const resetButton = document.querySelector(
            '[data-kt-user-table-filter="reset"]'
        );

        // Filter datatable on submit
        filterButton.addEventListener("click", function () {
            datatable.ajax.reload();
        });

        // Reset datatable
        resetButton.addEventListener("click", function () {
            // Reset filter form
            const filterForm = document.querySelector(
                '[data-kt-user-table-filter="form"]'
            );
            filterForm.querySelectorAll("select").forEach((select) => {
                $(select).val("").trigger("change");
            });

            // Reset search
            document.querySelector(
                '[data-kt-user-table-filter="search"]'
            ).value = "";

            // Reload datatable
            datatable.ajax.reload();
        });
    };

    // Handle Edit and Delete actions
    var handleRowActions = function () {
        // Handle Delete button click
        $(table).on(
            "click",
            '[data-kt-users-table-filter="delete_row"]',
            function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest("tr");

                // Get user name
                const userName = parent
                    .querySelectorAll("td")[2]
                    .querySelector("a").innerText;
                const userId = $(this).attr("data-user-id");

                // SweetAlert2 pop up --- official docs reference: https://sweetalert2.github.io/
                Swal.fire({
                    text: "Are you sure you want to delete " + userName + "?",
                    icon: "warning",
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary",
                    },
                }).then(function (result) {
                    if (result.value) {
                        // Delete user via AJAX
                        $.ajax({
                            url: window.route("setting.user.destroy") + userId,
                            type: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton:
                                                "btn fw-bold btn-primary",
                                        },
                                    }).then(function () {
                                        // Reload datatable
                                        datatable.ajax.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton:
                                                "btn fw-bold btn-primary",
                                        },
                                    });
                                }
                            },
                            error: function (xhr) {
                                const response = xhr.responseJSON;
                                Swal.fire({
                                    text:
                                        response.message || "An error occurred",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
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
        );

        // Handle Action dropdown button click
        $(table).on("click", '[data-kt-menu-trigger="click"]', function (e) {
            e.stopPropagation();
            // Button elements don't need preventDefault, just let KTMenu handle the dropdown
        });

        // Handle Edit button click
        $(table).on(
            "click",
            '[data-kt-users-table-filter="edit_row"]',
            function (e) {
                e.preventDefault();

                const userId = $(this).attr("data-user-id");

                // For now, just show alert - you can implement edit modal later
                Swal.fire({
                    text:
                        "Edit functionality for user ID: " +
                        userId +
                        " - Coming soon!",
                    icon: "info",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    },
                });
            }
        );
    };

    // Init toggle toolbar
    var initToggleToolbar = function () {
        // Toggle selected action toolbar
        // Select all checkboxes
        const checkboxes = table.querySelectorAll('[type="checkbox"]');

        // Select elements
        const deleteSelected = document.querySelector(
            '[data-kt-user-table-select="delete_selected"]'
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
                text: "Are you sure you want to delete selected users?",
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Yes, delete!",
                cancelButtonText: "No, cancel",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary",
                },
            }).then(function (result) {
                if (result.value) {
                    // Collect selected user IDs
                    const selectedIds = [];
                    checkboxes.forEach((c) => {
                        if (c.checked && c.value !== "1") {
                            // Exclude the "select all" checkbox
                            selectedIds.push(c.value);
                        }
                    });

                    if (selectedIds.length > 0) {
                        // Delete users via AJAX
                        $.ajax({
                            url: window.route("setting.user.destroy.multiple"),
                            type: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                            },
                            data: {
                                user_ids: selectedIds,
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "success",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton:
                                                "btn fw-bold btn-primary",
                                        },
                                    }).then(function () {
                                        // Reload datatable
                                        datatable.ajax.reload();

                                        // Reset checkbox
                                        table.querySelectorAll(
                                            '[type="checkbox"]'
                                        )[0].checked = false;
                                    });
                                } else {
                                    Swal.fire({
                                        text: response.message,
                                        icon: "error",
                                        buttonsStyling: false,
                                        confirmButtonText: "Ok, got it!",
                                        customClass: {
                                            confirmButton:
                                                "btn fw-bold btn-primary",
                                        },
                                    });
                                }
                            },
                            error: function (xhr) {
                                const response = xhr.responseJSON;
                                Swal.fire({
                                    text:
                                        response.message || "An error occurred",
                                    icon: "error",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton:
                                            "btn fw-bold btn-primary",
                                    },
                                });
                            },
                        });
                    }
                }
            });
        });
    };

    // Toggle toolbars
    var toggleToolbars = function () {
        // Define variables
        const toolbarBase = document.querySelector(
            '[data-kt-user-table-toolbar="base"]'
        );
        const toolbarSelected = document.querySelector(
            '[data-kt-user-table-toolbar="selected"]'
        );
        const selectedCount = document.querySelector(
            '[data-kt-user-table-select="selected_count"]'
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
            // Prevent multiple initialization
            if (initialized) {
                return;
            }

            table = document.querySelector("#kt_table_users");

            if (!table) {
                return;
            }

            initUserTable();
            initToggleToolbar();
            handleSearchDatatable();
            handleFilterDatatable();

            // Initialize KTMenu for dropdowns
            if (typeof KTMenu !== "undefined") {
                KTMenu.init();
            }

            initialized = true;
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
    KTUsersListDatatable.init();
});

// Alternative initialization if KTUtil is not available
$(document).ready(function () {
    if (typeof KTUsersListDatatable !== "undefined") {
        KTUsersListDatatable.init();
    }
});
