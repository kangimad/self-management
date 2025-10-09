<a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click"
    data-kt-menu-placement="bottom-end">
    Actions
    <i class="ki-outline ki-down fs-5 ms-1"></i>
</a>
<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
    data-kt-menu="true">
    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3" data-kt-first-record-table-filter="edit_row"
            data-first-record-id="{{ $result->id }}">Edit</a>
    </div>
    <div class="menu-item px-3">
        <a href="#" class="menu-link px-3 text-danger" data-kt-first-record-table-filter="delete_row"
            data-first-record-id="{{ $result->id }}" data-first-record-name="{{ $result->name }}">Delete</a>
    </div>
</div>
