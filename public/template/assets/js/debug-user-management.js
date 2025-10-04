// Debug script untuk user management
console.log("=== USER MANAGEMENT DEBUG SCRIPT ===");

// Check if required libraries are loaded
setTimeout(function () {
    console.log("=== DEPENDENCY CHECK ===");
    console.log("jQuery:", typeof $ !== "undefined" ? "✓ Loaded" : "✗ Missing");
    console.log(
        "DataTables:",
        typeof $.fn.DataTable !== "undefined" ? "✓ Loaded" : "✗ Missing"
    );
    console.log(
        "SweetAlert2:",
        typeof Swal !== "undefined" ? "✓ Loaded" : "✗ Missing"
    );
    console.log(
        "KTMenu:",
        typeof KTMenu !== "undefined" ? "✓ Loaded" : "✗ Missing"
    );
    console.log(
        "KTUtil:",
        typeof KTUtil !== "undefined" ? "✓ Loaded" : "✗ Missing"
    );
    console.log(
        "FormValidation:",
        typeof FormValidation !== "undefined" ? "✓ Loaded" : "✗ Missing"
    );

    // Check if table exists
    console.log("=== TABLE CHECK ===");
    const table = document.querySelector("#kt_table_users");
    console.log("Table element:", table ? "✓ Found" : "✗ Missing");

    if (table) {
        // Check if DataTable is initialized
        console.log(
            "DataTable initialized:",
            $.fn.DataTable.isDataTable(table) ? "✓ Yes" : "✗ No"
        );

        // Check for action buttons
        const actionButtons = table.querySelectorAll(
            '[data-kt-menu-trigger="click"]'
        );
        console.log("Action buttons found:", actionButtons.length);

        // Check for menus
        const menus = table.querySelectorAll('[data-kt-menu="true"]');
        console.log("Dropdown menus found:", menus.length);

        // Add test event listeners
        actionButtons.forEach((btn, index) => {
            btn.addEventListener("click", function (e) {
                console.log(`Action button ${index} clicked!`, e);
                e.preventDefault();
            });
        });

        // Check for delete buttons
        const deleteButtons = table.querySelectorAll(
            '[data-kt-users-table-filter="delete_row"]'
        );
        console.log("Delete buttons found:", deleteButtons.length);

        deleteButtons.forEach((btn, index) => {
            btn.addEventListener("click", function (e) {
                console.log(`Delete button ${index} clicked!`, e);
                const userId = btn.getAttribute("data-user-id");
                console.log("User ID:", userId);
            });
        });
    }

    // Check routes
    console.log("=== ROUTE CHECK ===");
    if (typeof window.route === "function") {
        console.log("Route helper:", "✓ Available");
        console.log("Datatable route:", window.route("setting.user.datatable"));
        console.log("Store route:", window.route("setting.user.store"));
        console.log("Destroy route:", window.route("setting.user.destroy"));
    } else {
        console.log("Route helper:", "✗ Missing");
    }
}, 1000);

// Monitor for new elements added to DOM
const observer = new MutationObserver(function (mutations) {
    mutations.forEach(function (mutation) {
        if (mutation.type === "childList") {
            mutation.addedNodes.forEach(function (node) {
                if (node.nodeType === 1) {
                    // Element node
                    const actionBtns = node.querySelectorAll
                        ? node.querySelectorAll(
                              '[data-kt-menu-trigger="click"]'
                          )
                        : [];
                    if (actionBtns.length > 0) {
                        console.log(
                            "New action buttons added:",
                            actionBtns.length
                        );
                    }
                }
            });
        }
    });
});

// Start observing
const tableContainer = document.querySelector("#kt_table_users");
if (tableContainer) {
    observer.observe(tableContainer, {
        childList: true,
        subtree: true,
    });
}
