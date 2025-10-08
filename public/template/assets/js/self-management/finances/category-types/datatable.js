"use strict";

// Class definition
var KTCategoryTypesListDatatable = (function () {
    // Shared variables
    var datatable;
    var table;
    var initialized = false;

    // Private functions
    var initCategoryTypeTable = function () {
        // console.log("Initializing Category Type DataTable...");

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
                    { orderable: true, targets: 2 }, // Enable ordering on column 2 (name)
                    { orderable: true, targets: 3 }, // Enable ordering on column 3 (categories)
                    { orderable: true, targets: 4 }, // Enable ordering on column 4 (created_at)
                    { orderable: false, targets: 5 }, // Disable ordering on column 5 (actions)
                    { searchable: false, targets: 0 }, // Disable searching on checkbox column
                    { searchable: false, targets: 1 }, // Disable searching on index column
                    { searchable: false, targets: 5 }, // Disable searching on actions column
                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: route("finance.category-types.datatable"),
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
                            '[data-kt-category-types-table-filter="search"]'
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
                                    <input class="form-check-input category-type-checkbox" type="checkbox" value="${row.id}" />
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
                                        <span class="text-muted fs-7">${row.description}</span>
                                    </div>
                                </div>`;
                        },
                    },
                    {
                        data: "categories",
                        render: function (data, type, row) {
                            if (!data) {
                                return '<span class="text-muted">Tidak ada kategori</span>';
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
                                        <a href="#" class="menu-link px-3" data-kt-category-types-table-filter="edit_row" data-category-type-id="${row.id}">Edit</a>
                                    </div>
                                    <div class="menu-item px-3">
                                        <a href="#" class="menu-link px-3" data-kt-category-types-table-filter="delete_row" data-category-type-id="${row.id}" data-category-type-name="${row.name}">Delete</a>
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
            '[data-kt-category-types-table-filter="search"]'
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
            '[data-kt-category-types-table-filter="delete_row"]',
            function (e) {
                e.preventDefault();

                const categoryTypeName = $(this).attr(
                    "data-category-type-name"
                );
                const categoryTypeId = $(this).attr("data-category-type-id");

                // SweetAlert2 pop up
                Swal.fire({
                    text:
                        "Yakin hendak menghapus category type " +
                        categoryTypeName +
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
                        // Delete category type via AJAX
                        $.ajax({
                            url: route(
                                "finance.category-types.destroy",
                                categoryTypeId
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
            '[data-kt-category-types-table-filter="edit_row"]',
            function (e) {
                e.preventDefault();

                const categoryTypeId = $(this).attr("data-category-type-id");

                // Load category type data for editing
                loadCategoryTypeForEdit(categoryTypeId);
            }
        );
    };

    // Load category type data for editing
    var loadCategoryTypeForEdit = function (categoryTypeId) {
        $.ajax({
            url: route("finance.category-types.show", categoryTypeId),
            type: "GET",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                if (response.success) {
                    const categoryType = response.data;

                    // Fill the update modal with category type data
                    $(
                        '#kt_modal_update_category_type_form input[name="name"]'
                    ).val(categoryType.name);
                    $(
                        '#kt_modal_update_category_type_form textarea[name="description"]'
                    ).val(categoryType.description);
                    $("#kt_modal_update_category_type").attr(
                        "data-category-type-id",
                        categoryType.id
                    );

                    // Show the update modal
                    $("#kt_modal_update_category_type").modal("show");
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
            '[data-kt-category-type-table-toolbar="base"]'
        );
        const toolbarSelected = document.querySelector(
            '[data-kt-category-type-table-toolbar="selected"]'
        );
        const selectedCountElement = document.querySelector(
            '[data-kt-category-type-table-select="selected_count"]'
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
            '[data-kt-category-type-table-select="delete_selected"]'
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
                    text: `Yakin hendak menghapus ${selectedIds.length} category type?`,
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
                        // Delete selected category types via AJAX
                        $.ajax({
                            url: route("finance.category-types.destroy-multiple"),
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

            table = document.querySelector("#kt_category_types_table");

            if (!table) {
                console.error("Table #kt_category_types_table not found");
                return;
            }

            initCategoryTypeTable();
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

// Category Type Modal Handlers
var KTCategoryTypesModal = (function () {
    var submitAddButton;

    // Init add category type modal
    var initAddCategoryType = function () {
        // Submit button handler
        submitAddButton = document.querySelector(
            "#kt_modal_add_category_type_submit"
        );
        submitAddButton.addEventListener("click", function (e) {
            e.preventDefault();

            // Prevent double submission
            if (submitAddButton.disabled) {
                return false;
            }

            // Simple client-side validation
            const nameInput = document.querySelector(
                '#kt_modal_add_category_type_form input[name="name"]'
            );
            if (!nameInput.value.trim()) {
                Swal.fire({
                    text: "Nama category type harus diisi",
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
                document.getElementById("kt_modal_add_category_type_form")
            );

            $.ajax({
                url: route("finance.category-types.store"),
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
                            $("#kt_modal_add_category_type").modal("hide");

                            // Reset form
                            document
                                .getElementById("kt_modal_add_category_type_form")
                                .reset();
                            clearFormErrors();

                            // Reload datatable
                            KTCategoryTypesListDatatable.refresh();
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
                                `#kt_modal_add_category_type_form input[name="${field}"]`
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
                "kt_modal_add_category_type_form"
            );
            const inputs = form.querySelectorAll("input, textarea");
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
            .querySelector("#kt_modal_add_category_type_cancel")
            .addEventListener("click", function (e) {
                e.preventDefault();

                Swal.fire({
                    text: "Yakin membatalkan pembuatan category type?",
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
                        $("#kt_modal_add_category_type").modal("hide");
                        document
                            .getElementById("kt_modal_add_category_type_form")
                            .reset();
                        clearFormErrors();
                    }
                });
            });

        // Close button handler
        document
            .querySelector("#kt_modal_add_category_type_close")
            .addEventListener("click", function (e) {
                e.preventDefault();
                $("#kt_modal_add_category_type").modal("hide");
                document
                    .getElementById("kt_modal_add_category_type_form")
                    .reset();
                clearFormErrors();
            });

        // Clear error when user starts typing
        document
            .querySelector(
                '#kt_modal_add_category_type_form input[name="name"]'
            )
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

    // Init update category type modal
    var initUpdateCategoryType = function () {
        // Submit button handler
        document
            .querySelector("#kt_modal_update_category_type_submit")
            .addEventListener("click", function (e) {
                e.preventDefault();

                // Prevent double submission
                if (this.disabled) {
                    return false;
                }

                const categoryTypeId = $("#kt_modal_update_category_type").attr(
                    "data-category-type-id"
                );
                const formData = new FormData(
                    document.getElementById(
                        "kt_modal_update_category_type_form"
                    )
                );

                // Show loading indication
                this.setAttribute("data-kt-indicator", "on");
                this.disabled = true;

                $.ajax({
                    url: route("finance.category-types.update", categoryTypeId),
                    type: "PUT",
                    data: {
                        name: formData.get("name"),
                        description: formData.get("description"),
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
                            .querySelector(
                                "#kt_modal_update_category_type_submit"
                            )
                            .removeAttribute("data-kt-indicator");
                        document.querySelector(
                            "#kt_modal_update_category_type_submit"
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
                                $("#kt_modal_update_category_type").modal("hide");

                                // Reload datatable
                                KTCategoryTypesListDatatable.refresh();
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
                            .querySelector(
                                "#kt_modal_update_category_type_submit"
                            )
                            .removeAttribute("data-kt-indicator");
                        document.querySelector(
                            "#kt_modal_update_category_type_submit"
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
            .querySelector("#kt_modal_update_category_type_cancel")
            .addEventListener("click", function (e) {
                e.preventDefault();
                $("#kt_modal_update_category_type").modal("hide");
            });

        // Close button handler
        document
            .querySelector("#kt_modal_update_category_type_close")
            .addEventListener("click", function (e) {
                e.preventDefault();
                $("#kt_modal_update_category_type").modal("hide");
            });
    };

    return {
        init: function () {
            initAddCategoryType();
            initUpdateCategoryType();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCategoryTypesListDatatable.init();
    KTCategoryTypesModal.init();
});

// Alternative initialization if KTUtil is not available
$(document).ready(function () {
    if (typeof KTCategoryTypesListDatatable !== "undefined") {
        KTCategoryTypesListDatatable.init();
    }
    if (typeof KTCategoryTypesModal !== "undefined") {
        KTCategoryTypesModal.init();
    }
});
