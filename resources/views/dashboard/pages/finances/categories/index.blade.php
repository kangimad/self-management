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
        <div class="card card-flush">
            <!--begin::Card header-->
            <div class="card-header mt-6">
                <!--begin::Card title-->
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1 me-5">
                        <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                        <input type="text" data-kt-first-record-table-filter="search"
                            class="form-control form-control-solid w-250px ps-13" placeholder="Search Data" />
                    </div>
                    <!--end::Search-->
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-first-record-table-toolbar="base">
                        <!--begin::Button-->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#kt_modal_add_first_record">
                            <i class="ki-outline ki-plus fs-3"></i>Tambah Data</button>
                        <!--end::Button-->
                    </div>
                    <!--end::Toolbar-->

                    <!--begin::Group actions-->
                    <div class="d-flex justify-content-end align-items-center d-none"
                        data-kt-first-record-table-toolbar="selected">
                        <div class="fw-bold me-5">
                            <span class="me-2" data-kt-first-record-table-select="selected_count"></span>Selected
                        </div>
                        <button type="button" class="btn btn-danger" data-kt-first-record-table-select="delete_selected">
                            Delete Selected
                        </button>
                    </div>
                    <!--end::Group actions-->
                </div>
                <!--end::Card toolbar-->
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_first_record_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="w-10px pe-2">
                                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                                        data-kt-check-target="#kt_first_record_table .form-check-input" value="1" />
                                </div>
                            </th>
                            <th class="">#</th>
                            <th class="">Name</th>
                            <th class="">Type</th>
                            <th class="">User</th>
                            <th class="">Created Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        {{-- Data akan dimuat via DataTables --}}
                    </tbody>
                </table>
                <!--end::Table-->
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->
        <!--begin::Modals-->
        <!--begin::Modal - Add category-->
        <div class="modal fade" id="kt_modal_add_first_record" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Tambah Data</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" id="kt_modal_add_first_record_close">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <!--begin::Form-->
                        <form id="kt_modal_add_first_record_form" class="form" action="#">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mb-2">
                                    <span class="required">Name</span>
                                    <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover"
                                        data-bs-html="true" data-bs-content="Nama harus diisi dan unik.">
                                        <i class="ki-outline ki-information fs-7"></i>
                                    </span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" placeholder="Masukkan nama" name="name"
                                    required />
                                <!--end::Input-->
                                <!--begin::Error message-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <!--end::Error message-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Types-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-5 fw-bold form-label mb-2">
                                    <span class="required">Type</span>
                                    <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover"
                                        data-bs-html="true"
                                        data-bs-content="Tipe harus diisi dan sesuai pilihan yang ada.">
                                        <i class="ki-outline ki-information fs-7"></i>
                                    </span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Select-->
                                <select class="form-select form-select-solid" data-control="select2"
                                    data-placeholder="Select an option" data-allow-clear="true" name="category_type_id"
                                    required>
                                    <option></option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Select-->
                                <!--begin::Error message-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <!--end::Error message-->
                            </div>
                            <!--end::Types-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mb-2">
                                    <span>Description</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea class="form-control form-control-solid" placeholder="Masukkan deskripsi (opsional)" name="description"
                                    rows="3"></textarea>
                                <!--end::Input-->
                                <!--begin::Error message-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <!--end::Error message-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::User-->
                            <!-- hidden input -->
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <!--end::User-->

                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3"
                                    id="kt_modal_add_first_record_cancel">Batal</button>
                                <button type="submit" class="btn btn-primary" id="kt_modal_add_first_record_submit">
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
        <!--end::Modal - Add category type-->
        <!--begin::Modal - Update category type-->
        <div class="modal fade" id="kt_modal_update_first_record" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Update Data</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" id="kt_modal_update_first_record_close">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <!--begin::Form-->
                        <form id="kt_modal_update_first_record_form" class="form" action="#">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mb-2">
                                    <span class="required">Name</span>
                                    <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover"
                                        data-bs-html="true" data-bs-content="Nama harus diisi dan unik.">
                                        <i class="ki-outline ki-information fs-7"></i>
                                    </span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" placeholder="Masukkan nama" name="name"
                                    required />
                                <!--end::Input-->
                                <!--begin::Error message-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <!--end::Error message-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Types-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-5 fw-bold form-label mb-2">
                                    <span class="required">Type</span>
                                    <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover"
                                        data-bs-html="true"
                                        data-bs-content="Tipe harus diisi dan sesuai pilihan yang ada.">
                                        <i class="ki-outline ki-information fs-7"></i>
                                    </span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Select-->
                                <select class="form-select form-select-solid" data-control="select2"
                                    data-placeholder="Select an option" data-allow-clear="true" name="category_type_id"
                                    required>
                                    <option></option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                <!--end::Select-->
                                <!--begin::Error message-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <!--end::Error message-->
                            </div>
                            <!--end::Types-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mb-2">
                                    <span>Description</span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea class="form-control form-control-solid" placeholder="Masukkan deskripsi (opsional)" name="description"
                                    rows="3"></textarea>
                                <!--end::Input-->
                                <!--begin::Error message-->
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <!--end::Error message-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::User-->
                            <!-- hidden input -->
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <!--end::User-->

                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3"
                                    id="kt_modal_update_first_record_cancel">Batal</button>
                                <button type="submit" class="btn btn-primary" id="kt_modal_update_first_record_submit">
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
        <!--end::Modal - Update permissions-->
        <!--end::Modals-->
    </div>
    <!--end::Content-->
@endsection

@push('vendor-style')
    <link href="{{ asset('template/assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
@endpush

@push('custom-style')
@endpush

@push('vendor-script')
    <script src="{{ asset('template/assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
@endpush

@push('custom-script')
    <script src="{{ asset('template/assets/js/self-management/finances/categories/datatable.js') }}"></script>
@endpush
