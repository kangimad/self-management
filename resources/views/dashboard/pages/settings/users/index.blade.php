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
        <!--begin::Statistics Cards-->
        @if (isset($statistics))
            <div class="row g-5 g-xl-8 mb-5">
                <div class="col">
                    <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-xl-100"
                        style="background-color: #F1416C;background-image:url('{{ asset('template/assets/media/patterns/vector-1.png') }}')">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span
                                    class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistics['total'] ?? 0 }}</span>
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Total Users</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-flush h-xl-100" style="background-color: #7239EA">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span
                                    class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistics['online'] ?? 0 }}</span>
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Online Users</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-flush h-xl-100" style="background-color: #17C653">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span
                                    class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistics['verified'] ?? 0 }}</span>
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Verified Users</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card card-flush h-xl-100" style="background-color: #FFC700">
                        <div class="card-header pt-5">
                            <div class="card-title d-flex flex-column">
                                <span
                                    class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">{{ $statistics['unverified'] ?? 0 }}</span>
                                <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Unverified Users</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <!--end::Statistics Cards-->

        <div class="row g-5 g-xl-8">
            <div class="col-12">
                <div class="card card-flush h-lg-100">
                    <!--begin::Card header-->
                    <div class="card-header pt-7">
                        <!--begin::Card title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">{{ $metadata['page'] ?? '' }}
                                {{ $metadata['title'] ?? '' }}</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">{{ $metadata['desc'] ?? '' }}</span>
                        </h3>
                        <!--end::Card title-->
                    </div>
                    `<!--end::Card header-->

                    <div class="card-header border-0">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <!--begin::Search-->
                            <div class="d-flex align-items-center position-relative my-1">
                                <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                                <input type="text" data-kt-user-table-filter="search"
                                    class="form-control form-control-solid w-250px ps-13" placeholder="Search user" />
                            </div>
                            <!--end::Search-->
                        </div>
                        <!--begin::Card title-->

                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <!--begin::Filter-->
                                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                                    data-kt-menu-placement="bottom-end">
                                    <i class="ki-outline ki-filter fs-2"></i>Filter</button>
                                <!--begin::Menu 1-->
                                <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                    <!--begin::Header-->
                                    <div class="px-7 py-5">
                                        <div class="fs-5 text-gray-900 fw-bold">Filter Options</div>
                                    </div>
                                    <!--end::Header-->
                                    <!--begin::Separator-->
                                    <div class="separator border-gray-200"></div>
                                    <!--end::Separator-->
                                    <!--begin::Content-->
                                    <div class="px-7 py-5" data-kt-user-table-filter="form">
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">Role:</label>
                                            <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                                data-placeholder="Select option" data-allow-clear="true"
                                                data-kt-user-table-filter="role" data-hide-search="true">
                                                <option></option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">Status:</label>
                                            <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                                data-placeholder="Select option" data-allow-clear="true"
                                                data-kt-user-table-filter="status" data-hide-search="true">
                                                <option></option>
                                                <option value="online">Online</option>
                                                <option value="offline">Offline</option>
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Input group-->
                                        <div class="mb-10">
                                            <label class="form-label fs-6 fw-semibold">Email Verification:</label>
                                            <select class="form-select form-select-solid fw-bold" data-kt-select2="true"
                                                data-placeholder="Select option" data-allow-clear="true"
                                                data-kt-user-table-filter="verified" data-hide-search="true">
                                                <option></option>
                                                <option value="verified">Verified</option>
                                                <option value="unverified">Unverified</option>
                                            </select>
                                        </div>
                                        <!--end::Input group-->
                                        <!--begin::Actions-->
                                        <div class="d-flex justify-content-end">
                                            <button type="reset"
                                                class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6"
                                                data-kt-menu-dismiss="true"
                                                data-kt-user-table-filter="reset">Reset</button>
                                            <button type="submit" class="btn btn-primary fw-semibold px-6"
                                                data-kt-menu-dismiss="true"
                                                data-kt-user-table-filter="filter">Apply</button>
                                        </div>
                                        <!--end::Actions-->
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Menu 1-->
                                <!--end::Filter-->
                                <!--begin::Export-->
                                <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_export_users">
                                    <i class="ki-outline ki-exit-up fs-2"></i>Export</button>
                                <!--end::Export-->
                                <!--begin::Add user-->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_add_user">
                                    <i class="ki-outline ki-plus fs-2"></i>Tambah Pengguna</button>
                                <!--end::Add user-->
                            </div>
                            <!--end::Toolbar-->
                            <!--begin::Group actions-->
                            <div class="d-flex justify-content-end align-items-center d-none"
                                data-kt-user-table-toolbar="selected">
                                <div class="fw-bold me-5">
                                    <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected
                                </div>
                                <button type="button" class="btn btn-danger"
                                    data-kt-user-table-select="delete_selected">Delete Selected</button>
                            </div>
                            <!--end::Group actions-->
                            <!--begin::Modal - Adjust Balance-->
                            <div class="modal fade" id="kt_modal_export_users" tabindex="-1" aria-hidden="true">
                                <!--begin::Modal dialog-->
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header">
                                            <!--begin::Modal title-->
                                            <h2 class="fw-bold">Export Users</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                data-kt-users-modal-action="close">
                                                <i class="ki-outline ki-cross fs-1"></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>
                                        <!--end::Modal header-->
                                        <!--begin::Modal body-->
                                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                            <!--begin::Form-->
                                            <form id="kt_modal_export_users_form" class="form" action="#">
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="fs-6 fw-semibold form-label mb-2">Select Roles:</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <select name="role" data-control="select2"
                                                        data-placeholder="Select a role" data-hide-search="true"
                                                        class="form-select form-select-solid fw-bold">
                                                        <option></option>
                                                        <option value="Administrator">Administrator</option>
                                                        <option value="Analyst">Analyst</option>
                                                        <option value="Developer">Developer</option>
                                                        <option value="Support">Support</option>
                                                        <option value="Trial">Trial</option>
                                                    </select>
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="required fs-6 fw-semibold form-label mb-2">Select Export
                                                        Format:</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <select name="format" data-control="select2"
                                                        data-placeholder="Select a format" data-hide-search="true"
                                                        class="form-select form-select-solid fw-bold">
                                                        <option></option>
                                                        <option value="excel">Excel</option>
                                                        <option value="pdf">PDF</option>
                                                        <option value="cvs">CVS</option>
                                                        <option value="zip">ZIP</option>
                                                    </select>
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Actions-->
                                                <div class="text-center">
                                                    <button type="reset" class="btn btn-light me-3"
                                                        data-kt-users-export-action="cancel">Discard</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        data-kt-users-export-action="submit">
                                                        <span class="indicator-label">Export</span>
                                                        <span class="indicator-progress">Please wait...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                            <!--end::Modal - New Card-->
                            <!--begin::Modal - Add task-->
                            <div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
                                <!--begin::Modal dialog-->
                                <div class="modal-dialog modal-dialog-centered mw-650px">
                                    <!--begin::Modal content-->
                                    <div class="modal-content">
                                        <!--begin::Modal header-->
                                        <div class="modal-header" id="kt_modal_add_user_header">
                                            <!--begin::Modal title-->
                                            <h2 class="fw-bold">Tambah Pengguna</h2>
                                            <!--end::Modal title-->
                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                data-kt-users-modal-action="close">
                                                <i class="ki-outline ki-cross fs-1"></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>
                                        <!--end::Modal header-->
                                        <!--begin::Modal body-->
                                        <div class="modal-body px-5 my-7">
                                            <!--begin::Form-->
                                            <form id="kt_modal_add_user_form" class="form" action="#">
                                                <!--begin::Scroll-->
                                                <div class="d-flex flex-column scroll-y px-5 px-lg-10"
                                                    id="kt_modal_add_user_scroll" data-kt-scroll="true"
                                                    data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
                                                    data-kt-scroll-dependencies="#kt_modal_add_user_header"
                                                    data-kt-scroll-wrappers="#kt_modal_add_user_scroll"
                                                    data-kt-scroll-offset="300px">
                                                    <!--begin::Input group-->
                                                    <div class="fv-row mb-7">
                                                        <!--begin::Label-->
                                                        <label class="d-block fw-semibold fs-6 mb-5">Avatar</label>
                                                        <!--end::Label-->
                                                        <!--begin::Image placeholder-->
                                                        <style>
                                                            .image-input-placeholder {
                                                                background-image: url('public/templateassets/media/svg/files/blank-image.svg');
                                                            }

                                                            [data-bs-theme="dark"] .image-input-placeholder {
                                                                background-image: url('public/template/assets/media/svg/files/blank-image-dark.svg');
                                                            }
                                                        </style>
                                                        <!--end::Image placeholder-->
                                                        <!--begin::Image input-->
                                                        <div class="image-input image-input-outline image-input-placeholder"
                                                            data-kt-image-input="true">
                                                            <!--begin::Preview existing avatar-->
                                                            <div class="image-input-wrapper w-125px h-125px border"
                                                                style="background-image: url({{ asset('template/assets/media/avatars/blank.png') }});
                                                                background-size: cover; background-position: center center; background-repeat: no-repeat;
                                                                ">
                                                            </div>
                                                            <!--end::Preview existing avatar-->
                                                            <!--begin::Label-->
                                                            <label
                                                                class="btn btn-icon btn-circle btn-light-warning w-25px h-25px shadow"
                                                                data-kt-image-input-action="change"
                                                                data-bs-toggle="tooltip" title="Change avatar">
                                                                <i class="ki-outline ki-pencil fs-7"></i>
                                                                <!--begin::Inputs-->
                                                                <input type="file" name="image"
                                                                    accept=".png, .jpg, .jpeg, .gif" />
                                                                <input type="hidden" name="image_remove" />
                                                                <!--end::Inputs-->
                                                            </label>
                                                            <!--end::Label-->
                                                            <!--begin::Cancel-->
                                                            <span
                                                                class="btn btn-icon btn-circle btn-light-danger w-25px h-25px shadow"
                                                                data-kt-image-input-action="cancel"
                                                                data-bs-toggle="tooltip" title="Cancel avatar">
                                                                <i class="ki-outline ki-cross fs-2"></i>
                                                            </span>
                                                            <!--end::Cancel-->
                                                            <!--begin::Remove-->
                                                            <span
                                                                class="btn btn-icon btn-circle btn-light-danger w-25px h-25px shadow"
                                                                data-kt-image-input-action="remove"
                                                                data-bs-toggle="tooltip" title="Remove avatar">
                                                                <i class="ki-outline ki-cross fs-2"></i>
                                                            </span>
                                                            <!--end::Remove-->
                                                        </div>
                                                        <!--end::Image input-->
                                                        <!--begin::Hint-->
                                                        <div class="form-text">Ekstensi file: .png, .jpg, .jpeg.</div>
                                                        <!--end::Hint-->
                                                    </div>
                                                    <!--end::Input group-->
                                                    <!--begin::Input group-->
                                                    <div class="fv-row mb-7">
                                                        <!--begin::Label-->
                                                        <label class="required fw-semibold fs-6 mb-2">Nama Lengkap</label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="text" name="user_name"
                                                            class="form-control form-control-solid mb-3 mb-lg-0"
                                                            placeholder="Nama Lengkap" value="" />
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Input group-->
                                                    <!--begin::Input group-->
                                                    <div class="fv-row mb-7">
                                                        <!--begin::Label-->
                                                        <label class="required fw-semibold fs-6 mb-2">Email</label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="email" name="user_email"
                                                            class="form-control form-control-solid mb-3 mb-lg-0"
                                                            placeholder="example@domain.com" value="" />
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Input group-->
                                                    <!--begin::Input group-->
                                                    <div class="fv-row mb-7">
                                                        <!--begin::Label-->
                                                        <label class="required fw-semibold fs-6 mb-2">Password</label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="password" name="user_password"
                                                            class="form-control form-control-solid mb-3 mb-lg-0"
                                                            placeholder="Password" />
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Input group-->
                                                    <!--begin::Input group-->
                                                    <div class="fv-row mb-7">
                                                        <!--begin::Label-->
                                                        <label class="required fw-semibold fs-6 mb-2">Konfirmasi
                                                            Password</label>
                                                        <!--end::Label-->
                                                        <!--begin::Input-->
                                                        <input type="password" name="user_password_confirmation"
                                                            class="form-control form-control-solid mb-3 mb-lg-0"
                                                            placeholder="Konfirmasi Password" />
                                                        <!--end::Input-->
                                                    </div>
                                                    <!--end::Input group-->
                                                    <!--begin::Input group-->
                                                    <div class="fv-row mb-7">
                                                        <!--begin::Label-->
                                                        <label class="required fw-semibold fs-6 mb-2">Peran</label>
                                                        <!--end::Label-->
                                                        <!--begin::Select-->
                                                        <select name="user_role[]" class="form-select form-select-solid"
                                                            data-kt-select2="true" data-placeholder="Pilih peran..."
                                                            multiple>
                                                            <option value="">Pilih peran...</option>
                                                            @if (isset($roles))
                                                                @foreach ($roles as $role)
                                                                    <option value="{{ $role->name }}">
                                                                        {{ $role->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        <!--end::Select-->
                                                    </div>
                                                    <!--end::Input group-->
                                                </div>
                                                <!--end::Scroll-->
                                                <!--begin::Actions-->
                                                <div class="text-center pt-10">
                                                    <button type="reset" class="btn btn-light me-3"
                                                        data-kt-users-modal-action="cancel">Batal</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        data-kt-users-modal-action="submit">
                                                        <span class="indicator-label">Simpan</span>
                                                        <span class="indicator-progress">Silakan tunggu...
                                                            <span
                                                                class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                            <!--end::Modal - Add task-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>

                    <!--begin::Card body-->
                    <div class="card-body pt-2">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#kt_table_users .form-check-input" value="1" />
                                        </div>
                                    </th>
                                    <th class="w-50px pe-2">
                                        #
                                    </th>
                                    <th class="min-w-125px">Nama</th>
                                    <th class="min-w-125px">Peran</th>
                                    <th class="min-w-125px">Status Login</th>
                                    <th class="min-w-125px">Verified</th>
                                    <th class="min-w-125px">Tanggal Bergabung</th>
                                    <th class="text-end min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                {{-- Data akan dimuat via AJAX DataTable --}}
                                {{-- End Data --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Content-->
@endsection

@push('vendor-style')
    <link href="{{ asset('template/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
@endpush

@push('custom-style')
    <style>
        .symbol-label img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .symbol-label {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush

@push('vendor-script')
    <script src="{{ asset('template/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('custom-script')
    <!-- Laravel Route Helper -->
    <script>
        window.route = function(name, params = {}) {
            const routes = {
                'setting.user.datatable': '{{ route('setting.user.datatable') }}',
                'setting.user.store': '{{ route('setting.user.store') }}',
                'setting.user.destroy': '{{ url('setting/user') }}',
                'setting.user.destroy.multiple': '{{ route('setting.user.destroy.multiple') }}',
                'setting.user.export': '{{ route('setting.user.export') }}',
                'setting.user.roles': '{{ route('setting.user.roles') }}'
            };
            return routes[name] || name;
        };
    </script>

    <!-- Initialize Select2 globally -->
    <script>
        $(document).ready(function() {
            // Initialize all Select2 dropdowns
            $('[data-kt-select2="true"], [data-control="select2"]').each(function() {
                var $this = $(this);
                var config = {
                    placeholder: $this.data('placeholder') || 'Select an option...',
                    allowClear: !$this.prop('multiple'),
                    multiple: $this.prop('multiple'),
                    width: '100%'
                };

                // If dropdown is inside modal, set dropdownParent
                var $modal = $this.closest('.modal');
                if ($modal.length) {
                    config.dropdownParent = $modal;
                }

                $this.select2(config);
            });
        });
    </script>

    <!-- Debug Script (remove in production) -->
    {{-- <script src="{{ asset('template/assets/js/debug-user-management.js') }}"></script> --}}

    <!-- Updated User Management Scripts -->
    <script src="{{ asset('template/assets/js/custom/apps/user-management/users/list/table-updated.js') }}"></script>
    <script src="{{ asset('template/assets/js/custom/apps/user-management/users/list/export-updated.js') }}"></script>
    <script src="{{ asset('template/assets/js/custom/apps/user-management/users/list/add-updated.js') }}"></script>
@endpush
