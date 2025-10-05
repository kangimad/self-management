"use strict";

// Class definition
var KTPermissionsListDatatable = (function () {
    // Shared variables
    var datatable;
    var table;
    var initialized = false;

    // Private functions
    var initPermissionTable = function () {
        // console.log("Initializing Permission DataTable...");

        // Check if route helper is available
        if (typeof route !== "function") {
            console.error("Ziggy route helper function not available");
            return;
        }

        // Check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable(table)) {
            // console.log("DataTable already initialized, destroying first...");
            $(table).DataTable().destroy();
        }

        try {
            // Init datatable --- more info on datatables: https://datatables.net/manual/
            datatable = $(table).DataTable({
                info: true,
                order: [[2, "asc"]],
                pageLength: 10,
                columnDefs: [
                    { orderable: false, targets: 0 }, // Disable ordering on column 0 (checkbox)
                    { orderable: false, targets: 1 }, // Disable ordering on column 1 (index)
                    { orderable: false, targets: 5 }, // Disable ordering on column 5 (actions)
                    { searchable: false, targets: 0 }, // Disable searching on checkbox column
                    { searchable: false, targets: 1 }, // Disable searching on index column
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: route("setting.permission.datatable"),
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
                            '[data-kt-permissions-table-filter="search"]'
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

                        // Handle specific error cases
                        if (xhr.status === 401) {
                            console.error(
                                "Authentication required. Redirecting to login..."
                            );
                            window.location.href = "/login";
                        } else if (xhr.status === 403) {
                            console.error("Access forbidden");
                            Swal.fire({
                                text: "You don't have permission to access this resource.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            });
                        } else if (xhr.status >= 500) {
                            console.error("Server error");
                            Swal.fire({
                                text: "Server error occurred. Please try again later.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            });
                        }
                    },
                },
                columns: [
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `<div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input class="form-check-input permission-checkbox" type="checkbox" value="${row.id}" />
                                </div>`;
                        },
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                    },
                    {
                        data: "name",
                        render: function (data, type, row) {
                            return `<div class="d-flex align-items-center">
                                    <div class="d-flex flex-column">
                                        <span class="text-gray-800 text-hover-primary mb-1 fw-bold">${data}</span>
                                        <span class="text-muted fs-7">Guard: ${row.guard_name}</span>
                                    </div>
                                </div>`;
                        },
                    },
                    {
                        data: "roles",
                        render: function (data, type, row) {
                            if (!data) {
                                return '<span class="text-muted">Tidak ada role</span>';
                            }
                            return data;
                        },
                    },
                    {
                        data: "created_at",
                        render: function (data, type, row) {
                            return data || "-";
                        },
                    },
                    {
                        data: null,
                        orderable: false,
                        render: function (data, type, row) {
                            return `<a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                    Actions
                                    <i class="ki-outline ki-down fs-5 ms-1"></i>
                                </a>
                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-permissions-table-filter="edit_row" data-permission-id="${row.id}">Edit</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-permissions-table-filter="delete_row" data-permission-id="${row.id}" data-permission-name="${row.name}">Delete</a>
                                    </div>
                                </div>`;
                        },
                    },
                ],
            });

            // Re-init functions on every table re-draw
            datatable.on("draw", function () {
                handleRowActions();
                initToggleToolbar();
                toggleToolbars();

                // Reinitialize KTMenu for dropdown menus in the table with a small delay
                setTimeout(function () {
                    if (typeof KTMenu !== "undefined") {
                        try {
                            // First, destroy existing menu instances to avoid conflicts
                            const existingMenus = table.querySelectorAll(
                                '[data-kt-menu="true"][data-kt-menu-initialized="true"]'
                            );
                            existingMenus.forEach((menu) => {
                                try {
                                    const instance = KTMenu.getInstance(menu);
                                    if (instance) {
                                        instance.destroy();
                                    }
                                    menu.removeAttribute(
                                        "data-kt-menu-initialized"
                                    );
                                } catch (e) {
                                    // console.log("Error destroying menu:", e);
                                }
                            });

                            // Initialize all menus
                            KTMenu.init();

                            // Also try to specifically initialize table menus
                            const menus = table.querySelectorAll(
                                '[data-kt-menu="true"]'
                            );

                            menus.forEach((menu, index) => {
                                try {
                                    if (
                                        !menu.hasAttribute(
                                            "data-kt-menu-initialized"
                                        )
                                    ) {
                                        const menuInstance = new KTMenu(menu);
                                        menu.setAttribute(
                                            "data-kt-menu-initialized",
                                            "true"
                                        );
                                    }
                                } catch (e) {
                                    console.error(
                                        "Menu init error for menu",
                                        index,
                                        ":",
                                        e
                                    );
                                }
                            });
                        } catch (e) {
                            console.error("KTMenu init error:", e);
                        }
                    } else {
                        console.error("KTMenu is not defined");
                    }
                }, 200);
            });
        } catch (error) {
            console.error("Error initializing DataTable:", error);
            Swal.fire({
                text: "Error initializing data table. Please refresh the page.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn fw-bold btn-primary",
                },
            });
        }
    };

    // Search Datatable
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector(
            '[data-kt-permissions-table-filter="search"]'
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

    // Handle Edit and Delete actions
    var handleRowActions = function () {
        // Handle Delete button click
        $(table).on(
            "click",
            '[data-kt-permissions-table-filter="delete_row"]',
            function (e) {
                e.preventDefault();

                const permissionName = $(this).attr("data-permission-name");
                const permissionId = $(this).attr("data-permission-id");

                // SweetAlert2 pop up
                Swal.fire({
                    text:
                        "Yakin hendak menghapus permission " +
                        permissionName +
                        "?",
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
                        // Delete permission via AJAX
                        $.ajax({
                            url: route(
                                "setting.permission.destroy",
                                permissionId
                            ),
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

        // Handle Edit button click
        $(table).on(
            "click",
            '[data-kt-permissions-table-filter="edit_row"]',
            function (e) {
                e.preventDefault();

                const permissionId = $(this).attr("data-permission-id");

                // Load permission data for editing
                loadPermissionForEdit(permissionId);
            }
        );
    };

    // Load permission data for editing
    var loadPermissionForEdit = function (permissionId) {
        $.ajax({
            url: route("setting.permission.show", permissionId),
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    const permission = response.data;

                    // Fill the update modal with permission data
                    $(
                        '#kt_modal_update_permission_form input[name="name"]'
                    ).val(permission.name);
                    $("#kt_modal_update_permission").attr(
                        "data-permission-id",
                        permission.id
                    );

                    // Show the update modal
                    $("#kt_modal_update_permission").modal("show");
                } else {
                    Swal.fire({
                        text: response.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn fw-bold btn-primary",
                        },
                    });
                }
            },
            error: function (xhr) {
                const response = xhr.responseJSON;
                Swal.fire({
                    text: response.message || "An error occurred",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    },
                });
            },
        });
    };

    // Handle toggle toolbar
    var initToggleToolbar = function () {
        // Toggle selected action toolbar
        const checkboxes = table.querySelectorAll('tbody [type="checkbox"]');
        const selectAllCheckbox = table.querySelector(
            'thead [type="checkbox"]'
        );

        // Check/uncheck all functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener("change", function () {
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = this.checked;
                });
                toggleToolbars();
            });
        }

        // Individual checkbox change event
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", function () {
                toggleToolbars();
                updateSelectAllCheckbox();
            });
        });
    };

    // Update select all checkbox state
    var updateSelectAllCheckbox = function () {
        const checkboxes = table.querySelectorAll('tbody [type="checkbox"]');
        const selectAllCheckbox = table.querySelector(
            'thead [type="checkbox"]'
        );

        if (selectAllCheckbox && checkboxes.length > 0) {
            const checkedCount = Array.from(checkboxes).filter(
                (cb) => cb.checked
            ).length;

            if (checkedCount === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (checkedCount === checkboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }
    };

    // Toggle toolbars
    var toggleToolbars = function () {
        const selectedCheckboxes = table.querySelectorAll(
            'tbody [type="checkbox"]:checked'
        );
        const selectedCount = selectedCheckboxes.length;

        const toolbarBase = document.querySelector(
            '[data-kt-permission-table-toolbar="base"]'
        );
        const toolbarSelected = document.querySelector(
            '[data-kt-permission-table-toolbar="selected"]'
        );
        const selectedCountElement = document.querySelector(
            '[data-kt-permission-table-select="selected_count"]'
        );

        if (selectedCount > 0) {
            toolbarBase.classList.add("d-none");
            toolbarSelected.classList.remove("d-none");
            selectedCountElement.textContent = selectedCount;
        } else {
            toolbarBase.classList.remove("d-none");
            toolbarSelected.classList.add("d-none");
        }
    };

    // Handle bulk delete
    var handleBulkDelete = function () {
        const deleteSelectedButton = document.querySelector(
            '[data-kt-permission-table-select="delete_selected"]'
        );

        if (deleteSelectedButton) {
            deleteSelectedButton.addEventListener("click", function () {
                const selectedCheckboxes = table.querySelectorAll(
                    'tbody [type="checkbox"]:checked'
                );
                const selectedIds = Array.from(selectedCheckboxes).map(
                    (cb) => cb.value
                );

                if (selectedIds.length === 0) {
                    return;
                }

                Swal.fire({
                    text: `Yakin hendak menghapus ${selectedIds.length} permission?`,
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
                        // Delete selected permissions via AJAX
                        $.ajax({
                            url: route("setting.permission.destroy.multiple"),
                            type: "DELETE",
                            data: {
                                ids: selectedIds,
                            },
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                                "X-Requested-With": "XMLHttpRequest",
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
            });
        }
    };

    // Public methods
    return {
        init: function () {
            // Prevent multiple initialization
            if (initialized) {
                return;
            }

            table = document.querySelector("#kt_permissions_table");

            if (!table) {
                console.error("Table #kt_permissions_table not found");
                return;
            }

            initPermissionTable();
            handleSearchDatatable();
            handleBulkDelete();

            // Initialize KTMenu for dropdowns
            if (typeof KTMenu !== "undefined") {
                KTMenu.init();
            } else {
                console.error("KTMenu not available");
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

// Permission Modal Handlers
var KTPermissionsModal = (function () {
    var submitAddButton;
    var submitUpdateButton;
    var cancelButton;
    var closeButton;
    var validator;
    var form;
    var modal;

    // Init add permission modal
    var initAddPermission = function () {
        // Submit button handler
        submitAddButton = document.querySelector(
            "#kt_modal_add_permission_submit"
        );
        submitAddButton.addEventListener("click", function (e) {
            e.preventDefault();

            // Prevent double submission
            if (submitAddButton.disabled) {
                return false;
            }

            // Simple client-side validation
            const nameInput = document.querySelector(
                '#kt_modal_add_permission_form input[name="name"]'
            );
            if (!nameInput.value.trim()) {
                Swal.fire({
                    text: "Nama permission harus diisi",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn fw-bold btn-primary",
                    },
                });
                return false;
            }

            // Show loading indication
            submitAddButton.setAttribute("data-kt-indicator", "on");
            submitAddButton.disabled = true;

            // Submit form data
            const formData = new FormData(
                document.getElementById("kt_modal_add_permission_form")
            );

            $.ajax({
                url: route("setting.permission.store"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                success: function (response) {
                    if (response.success) {
                        // Hide loading indication
                        submitAddButton.removeAttribute("data-kt-indicator");
                        submitAddButton.disabled = false;

                        // Show success message
                        Swal.fire({
                            text: response.message,
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        }).then(function () {
                            // Hide modal
                            $("#kt_modal_add_permission").modal("hide");

                            // Reset form
                            document
                                .getElementById("kt_modal_add_permission_form")
                                .reset();
                            clearFormErrors();

                            // Reload datatable
                            KTPermissionsListDatatable.refresh();
                        });
                    } else {
                        // Hide loading indication
                        submitAddButton.removeAttribute("data-kt-indicator");
                        submitAddButton.disabled = false;

                        Swal.fire({
                            text: response.message,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                    }
                },
                error: function (xhr) {
                    // Hide loading indication
                    submitAddButton.removeAttribute("data-kt-indicator");
                    submitAddButton.disabled = false;

                    const response = xhr.responseJSON;

                    if (response && response.errors) {
                        // Display field-specific errors
                        Object.keys(response.errors).forEach(function (field) {
                            const input = document.querySelector(
                                `#kt_modal_add_permission_form input[name="${field}"]`
                            );
                            const errorContainer = input
                                ? input.parentNode.querySelector(
                                      ".invalid-feedback"
                                  )
                                : null;

                            if (input && errorContainer) {
                                input.classList.add("is-invalid");
                                errorContainer.innerHTML =
                                    response.errors[field][0];
                                errorContainer.style.display = "block";
                            }
                        });

                        // Also show general error message
                        const errorMessage = Object.values(response.errors)
                            .flat()
                            .join("\n");
                        Swal.fire({
                            text: errorMessage,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                    } else {
                        const errorMessage =
                            response?.message || "An error occurred";
                        Swal.fire({
                            text: errorMessage,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                    }
                },
            });
        });

        // Function to clear form errors
        var clearFormErrors = function () {
            const form = document.getElementById(
                "kt_modal_add_permission_form"
            );
            const inputs = form.querySelectorAll("input");
            const errorContainers = form.querySelectorAll(".invalid-feedback");

            inputs.forEach(function (input) {
                input.classList.remove("is-invalid");
            });

            errorContainers.forEach(function (container) {
                container.innerHTML = "";
                container.style.display = "none";
            });
        };

        // Cancel button handler
        document
            .querySelector("#kt_modal_add_permission_cancel")
            .addEventListener("click", function (e) {
                e.preventDefault();

                Swal.fire({
                    text: "Yakin membatalkan pembuatan permission?",
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
                        $("#kt_modal_add_permission").modal("hide");
                        document
                            .getElementById("kt_modal_add_permission_form")
                            .reset();
                        clearFormErrors();
                    }
                });
            });

        // Close button handler
        document
            .querySelector("#kt_modal_add_permission_close")
            .addEventListener("click", function (e) {
                e.preventDefault();
                $("#kt_modal_add_permission").modal("hide");
                document.getElementById("kt_modal_add_permission_form").reset();
                clearFormErrors();
            });

        // Clear error when user starts typing
        document
            .querySelector('#kt_modal_add_permission_form input[name="name"]')
            .addEventListener("input", function () {
                this.classList.remove("is-invalid");
                const errorContainer =
                    this.parentNode.querySelector(".invalid-feedback");
                if (errorContainer) {
                    errorContainer.innerHTML = "";
                    errorContainer.style.display = "none";
                }
            });
    };

    // Init update permission modal
    var initUpdatePermission = function () {
        // Submit button handler
        document
            .querySelector("#kt_modal_update_permission_submit")
            .addEventListener("click", function (e) {
                e.preventDefault();

                // Prevent double submission
                if (this.disabled) {
                    return false;
                }

                const permissionId = $("#kt_modal_update_permission").attr(
                    "data-permission-id"
                );
                const formData = new FormData(
                    document.getElementById("kt_modal_update_permission_form")
                );

                // Show loading indication
                this.setAttribute("data-kt-indicator", "on");
                this.disabled = true;

                $.ajax({
                    url: route("setting.permission.update", permissionId),
                    type: "PUT",
                    data: {
                        name: formData.get("name"),
                        _token: $('meta[name="csrf-token"]').attr("content"),
                    },
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (response) {
                        // Hide loading indication
                        document
                            .querySelector("#kt_modal_update_permission_submit")
                            .removeAttribute("data-kt-indicator");
                        document.querySelector(
                            "#kt_modal_update_permission_submit"
                        ).disabled = false;

                        if (response.success) {
                            Swal.fire({
                                text: response.message,
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            }).then(function () {
                                // Hide modal
                                $("#kt_modal_update_permission").modal("hide");

                                // Reload datatable
                                KTPermissionsListDatatable.refresh();
                            });
                        } else {
                            Swal.fire({
                                text: response.message,
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            });
                        }
                    },
                    error: function (xhr) {
                        // Hide loading indication
                        document
                            .querySelector("#kt_modal_update_permission_submit")
                            .removeAttribute("data-kt-indicator");
                        document.querySelector(
                            "#kt_modal_update_permission_submit"
                        ).disabled = false;

                        const response = xhr.responseJSON;
                        let errorMessage = "An error occurred";

                        if (response && response.errors) {
                            errorMessage = Object.values(response.errors)
                                .flat()
                                .join("\n");
                        } else if (response && response.message) {
                            errorMessage = response.message;
                        }

                        Swal.fire({
                            text: errorMessage,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn fw-bold btn-primary",
                            },
                        });
                    },
                });
            });

        // Cancel button handler
        document
            .querySelector("#kt_modal_update_permission_cancel")
            .addEventListener("click", function (e) {
                e.preventDefault();
                $("#kt_modal_update_permission").modal("hide");
            });

        // Close button handler
        document
            .querySelector("#kt_modal_update_permission_close")
            .addEventListener("click", function (e) {
                e.preventDefault();
                $("#kt_modal_update_permission").modal("hide");
            });
    };

    return {
        init: function () {
            initAddPermission();
            initUpdatePermission();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTPermissionsListDatatable.init();
    KTPermissionsModal.init();
});

// Alternative initialization if KTUtil is not available
$(document).ready(function () {
    if (typeof KTPermissionsListDatatable !== "undefined") {
        KTPermissionsListDatatable.init();
    }
    if (typeof KTPermissionsModal !== "undefined") {
        KTPermissionsModal.init();
    }
});
