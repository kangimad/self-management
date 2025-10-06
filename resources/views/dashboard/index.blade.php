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

                <!--begin::Breadcrumb-->
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
                <!--end::Breadcrumb-->
            </div>
            <!--end::Toolbar container-->
            <!--begin::Actions-->
            <div class="d-flex align-self-center flex-center flex-shrink-0">
                <a href="#" class="btn btn-sm btn-success d-flex flex-center ms-3 px-4 py-3" data-bs-toggle="modal"
                    data-bs-target="#kt_modal_invite_friends">
                    <i class="ki-outline ki-plus-square fs-2"></i>
                    <span>Invite</span>
                </a>
                <a href="#" class="btn btn-sm btn-dark ms-3 px-4 py-3" data-bs-toggle="modal"
                    data-bs-target="#kt_modal_new_target">Create
                    <span class="d-none d-sm-inline">Target</span></a>
            </div>
            <!--end::Actions-->
        </div>
        <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Row-->
        <div class="row g-5 g-xl-8">
            <!-- KONTEN HALAMAN TARUH DISINI -->
            <div class="col-12">
                <div class="card card-flush h-lg-100">
                    <!--begin::Card header-->
                    <div class="card-header pt-7">
                        <!--begin::Card title-->
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-900">{{ $metadata['page'] ?? '' }} {{ $metadata['title'] ?? '' }}</span>
                            <span class="text-gray-400 mt-1 fw-semibold fs-6">{{ $metadata['desc'] ?? '' }}</span>
                        </h3>
                        <!--end::Card title-->
                    </div>
                    `<!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-2">
                        Content
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Content-->
@endsection
