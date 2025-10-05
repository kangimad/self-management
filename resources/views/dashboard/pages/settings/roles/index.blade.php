@extends('dashboard.layouts.master')

@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar d-flex pb-3 pb-lg-5">
        <!--begin::Toolbar container-->
        <div class="d-flex flex-stack flex-row-fluid">
            <!--begin::Toolbar container-->
            <div class="d-flex flex-column flex-row-fluid">
                <!--begin::Toolbar wrapper-->
                <!--begin::Page title-->
                <div class="page-title d-flex align-items-center me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-lg-2x gap-2">
                        <span>{{ $metadata['title'] ?? '' }}</span>
                    </h1>
                    <!--end::Title-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->

            <!--begin::Breadcrumb-->
            <div class="d-flex align-self-center flex-center flex-shrink-0">
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold mb-3 fs-7">
                    @if (isset($metadata['bread1']) && $metadata['bread1'] != '')
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                            <a href="{{ $metadata['bread1_link'] ?? '' }}" class="text-white text-hover-primary">
                                {!! $metadata['bread1'] ?? '' !!}
                            </a>
                        </li>
                    @endif

                    @if (isset($metadata['bread2']) && $metadata['bread2'] != '')
                        <li class="breadcrumb-item">
                            <i class="ki-outline ki-right fs-7 text-gray-700 mx-n1"></i>
                        </li>
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                            <a href="{{ $metadata['bread2_link'] ?? '' }}" class="text-gray-700 text-hover-primary">
                                {!! $metadata['bread2'] ?? '' !!}
                            </a>
                        </li>
                    @endif

                    @if (isset($metadata['bread3']) && $metadata['bread3'] != '')
                        <li class="breadcrumb-item">
                            <i class="ki-outline ki-right fs-7 text-gray-700 mx-n1"></i>
                        </li>
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                            <a href="{{ $metadata['bread3_link'] ?? '' }}" class="text-gray-700 text-hover-primary">
                                {!! $metadata['bread3'] ?? '' !!}
                            </a>
                        </li>
                    @endif

                    @if (isset($metadata['bread4']) && $metadata['bread4'] != '')
                        <li class="breadcrumb-item">
                            <i class="ki-outline ki-right fs-7 text-gray-700 mx-n1"></i>
                        </li>
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                            <a href="{{ $metadata['bread4_link'] ?? '' }}" class="text-gray-700 text-hover-primary">
                                {!! $metadata['bread4'] ?? '' !!}
                            </a>
                        </li>
                    @endif

                    @if (isset($metadata['bread5']) && $metadata['bread5'] != '')
                        <li class="breadcrumb-item">
                            <i class="ki-outline ki-right fs-7 text-gray-700 mx-n1"></i>
                        </li>
                        <li class="breadcrumb-item text-gray-700 fw-bold lh-1">
                            <a href="{{ $metadata['bread5_link'] ?? '' }}" class="text-gray-700 text-hover-primary">
                                {!! $metadata['bread5'] ?? '' !!}
                            </a>
                        </li>
                    @endif

                    @if (isset($metadata['page']) && $metadata['page'] != '')
                        <li class="breadcrumb-item">
                            <i class="ki-outline ki-right fs-7 text-gray-700 mx-n1"></i>
                        </li>
                        <li class="breadcrumb-item text-gray-700">{{ $metadata['page'] ?? '' }}</li>
                    @endif
                </ul>
            </div>
            <!--end::Breadcrumb-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">

        <!--begin::Card-->
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header border-0 pt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                        <input type="text" data-kt-roles-table-filter="search"
                            class="form-control form-control-solid w-250px ps-13" placeholder="Cari role..." />
                    </div>
                    <!--end::Search-->
                </div>
                <!--begin::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-roles-table-toolbar="base">
                        <!--begin::Add role-->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_role">
                            <i class="ki-outline ki-plus fs-2"></i>Tambah Role
                        </button>
                        <!--end::Add role-->
                    </div>
                    <!--end::Toolbar-->
                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none"
                        data-kt-roles-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-roles-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-roles-table-select="delete_selected">Delete
                            Selected</button>
                    </div>
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_roles_table">
                    <thead>
                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_roles_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="">#</th>
                            <th class="min-w-125px">Role</th>
                            <th class="min-w-125px">Users</th>
                            <th class="min-w-125px">Created Date</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 fw-semibold">
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->

        <!--begin::Modals-->
        <!--begin::Modal - Add role-->
        <div class="modal fade" id="kt_modal_add_role" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-750px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Tambah Role</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary"
                            id="kt_modal_add_role_close">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </button>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body scroll-y mx-lg-5 my-7">
                        <!--begin::Form-->
                        <form id="kt_modal_add_role_form" class="form" action="#">
                            <!--begin::Scroll-->
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll"
                                data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_role_header"
                                data-kt-scroll-wrappers="#kt_modal_add_role_scroll" data-kt-scroll-offset="300px">
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold form-label mb-2">
                                        <span class="required">Nama Role</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" placeholder="Masukkan nama role"
                                        name="role_name" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Permissions-->
                                <div class="fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
                                    <!--end::Label-->
                                    <!--begin::Table wrapper-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                                            <!--begin::Table body-->
                                            <tbody class="text-gray-600 fw-semibold">
                                                @php
                                                    $groupedPermissions = [];
                                                    // Group permissions by category (using dash separator)
                                                    foreach ($permissions as $permission) {
                                                        // Safety check for permission object
                                                        if (is_object($permission) && isset($permission->name)) {
                                                            $parts = explode('-', $permission->name);
                                                            $category = ucfirst($parts[0] ?? 'general');
                                                            if (!isset($groupedPermissions[$category])) {
                                                                $groupedPermissions[$category] = [];
                                                            }
                                                            $groupedPermissions[$category][] = $permission;
                                                        } elseif (is_array($permission) && isset($permission['name'])) {
                                                            $parts = explode('-', $permission['name']);
                                                            $category = ucfirst($parts[0] ?? 'general');
                                                            if (!isset($groupedPermissions[$category])) {
                                                                $groupedPermissions[$category] = [];
                                                            }
                                                            $groupedPermissions[$category][] = (object) $permission;
                                                        }
                                                    }
                                                @endphp

                                                @if (count($groupedPermissions) > 0)
                                                    @foreach ($groupedPermissions as $category => $categoryPermissions)
                                                        <!--begin::Table row-->
                                                        <tr>
                                                            <td class="text-gray-800 fw-bold">{{ $category }}</td>
                                                            <td>
                                                                <!--begin::Wrapper-->
                                                                <div class="d-flex flex-column">
                                                                    <!--begin::Select All-->
                                                                    <div class="select-all-permission">
                                                                        <label
                                                                            class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" value=""
                                                                                id="kt_roles_select_all_add_{{ $loop->index }}" />
                                                                            <span
                                                                                class="form-check-label fw-semibold text-primary"
                                                                                for="kt_roles_select_all_add_{{ $loop->index }}">Select
                                                                                All {{ $category }}</span>
                                                                        </label>
                                                                    </div>
                                                                    <!--end::Select All-->

                                                                    <!--begin::Permissions Grid-->
                                                                    <div class="row g-2 permissions-grid">
                                                                        @foreach ($categoryPermissions as $permission)
                                                                            <div class="col-6">
                                                                                <label
                                                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                                                    <input class="form-check-input"
                                                                                        type="checkbox"
                                                                                        value="{{ $permission->name }}"
                                                                                        name="permissions[]" />
                                                                                    <span
                                                                                        class="form-check-label text-gray-700 fs-7">{{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}</span>
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <!--end::Permissions Grid-->
                                                                </div>
                                                                <!--end::Wrapper-->
                                                            </td>
                                                        </tr>
                                                        <!--end::Table row-->
                                                    @endforeach
                                                @else
                                                    <!--begin::Table row-->
                                                    <tr>
                                                        <td colspan="2" class="text-center text-muted">
                                                            No permissions available
                                                        </td>
                                                    </tr>
                                                    <!--end::Table row-->
                                                @endif
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
                                </div>
                                <!--end::Permissions-->
                            </div>
                            <!--end::Scroll-->
                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3"
                                    id="kt_modal_add_role_cancel">Batal</button>
                                <button type="submit" class="btn btn-primary" id="kt_modal_add_role_submit">
                                    <span class="indicator-label">Simpan</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Modal body-->
                </div>
                <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
        </div>
        <!--end::Modal - Add role-->

        <!--begin::Modal - Update role-->
        <div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-750px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Update Role</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary"
                            id="kt_modal_update_role_close">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </button>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body scroll-y mx-5 my-7">
                        <!--begin::Form-->
                        <form id="kt_modal_update_role_form" class="form" action="#">
                            <!--begin::Scroll-->
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_role_scroll"
                                data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                data-kt-scroll-max-height="auto"
                                data-kt-scroll-dependencies="#kt_modal_update_role_header"
                                data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px">
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold form-label mb-2">
                                        <span class="required">Nama Role</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" placeholder="Masukkan nama role"
                                        name="role_name" value="" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Permissions-->
                                <div class="fv-row">
                                    <!--begin::Label-->
                                    <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
                                    <!--end::Label-->
                                    <!--begin::Table wrapper-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fs-6 gy-5">
                                            <!--begin::Table body-->
                                            <tbody class="text-gray-600 fw-semibold">
                                                @if (count($groupedPermissions) > 0)
                                                    @foreach ($groupedPermissions as $category => $categoryPermissions)
                                                        <!--begin::Table row-->
                                                        <tr>
                                                            <td class="text-gray-800 fw-bold">{{ $category }}</td>
                                                            <td>
                                                                <!--begin::Wrapper-->
                                                                <div class="d-flex flex-column">
                                                                    <!--begin::Select All-->
                                                                    <div class="select-all-permission">
                                                                        <label
                                                                            class="form-check form-check-sm form-check-custom form-check-solid">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" value=""
                                                                                id="kt_roles_select_all_edit_{{ $loop->index }}" />
                                                                            <span
                                                                                class="form-check-label fw-semibold text-primary"
                                                                                for="kt_roles_select_all_edit_{{ $loop->index }}">Select
                                                                                All {{ $category }}</span>
                                                                        </label>
                                                                    </div>
                                                                    <!--end::Select All-->

                                                                    <!--begin::Permissions Grid-->
                                                                    <div class="row g-2 permissions-grid">
                                                                        @foreach ($categoryPermissions as $permission)
                                                                            <div class="col-6">
                                                                                <label
                                                                                    class="form-check form-check-sm form-check-custom form-check-solid">
                                                                                    <input class="form-check-input"
                                                                                        type="checkbox"
                                                                                        value="{{ $permission->name }}"
                                                                                        name="permissions[]" />
                                                                                    <span
                                                                                        class="form-check-label text-gray-700 fs-7">{{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}</span>
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <!--end::Permissions Grid-->
                                                                </div>
                                                                <!--end::Wrapper-->
                                                            </td>
                                                        </tr>
                                                        <!--end::Table row-->
                                                    @endforeach
                                                @else
                                                    <!--begin::Table row-->
                                                    <tr>
                                                        <td colspan="2" class="text-center text-muted">
                                                            No permissions available
                                                        </td>
                                                    </tr>
                                                    <!--end::Table row-->
                                                @endif
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
                                </div>
                                <!--end::Permissions-->
                            </div>
                            <!--end::Scroll-->
                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3"
                                    id="kt_modal_update_role_cancel">Batal</button>
                                <button type="submit" class="btn btn-primary" id="kt_modal_update_role_submit">
                                    <span class="indicator-label">Update</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            <!--end::Actions-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Modal body-->
                </div>
                <!--end::Modal content-->
            </div>
            <!--end::Modal dialog-->
        </div>
        <!--end::Modal - Update role-->
        <!--end::Modals-->
    </div>
    <!--end::Content-->
@endsection

@push('vendor-style')
    <link href="{{ asset('template/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
@endpush

@push('custom-style')
    <style>
        .permissions-grid .form-check {
            margin-bottom: 0.5rem;
        }

        .permissions-grid .form-check-label {
            font-size: 0.875rem;
            line-height: 1.25;
        }

        .select-all-permission {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 0.75rem;
            margin-bottom: 0.75rem;
        }
    </style>
@endpush

@push('vendor-script')
    <script src="{{ asset('template/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('custom-script')
    <script src="{{ asset('template/assets/js/custom/apps/user-management/roles/list/roles-list.js') }}"></script>
    <script src="{{ asset('template/assets/js/custom/apps/user-management/roles/list/roles-add.js') }}"></script>
    <script src="{{ asset('template/assets/js/custom/apps/user-management/roles/list/roles-update.js') }}"></script>
@endpush
