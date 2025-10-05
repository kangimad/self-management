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
        <!--begin::Layout-->
        <div class="d-flex flex-column flex-lg-row">
            <!--begin::Sidebar-->
            <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                <!--begin::Card-->
                <div class="card mb-5 mb-xl-8">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin::Summary-->
                        <!--begin::User Info-->
                        <div class="d-flex flex-center flex-column py-5">
                            <!--begin::Avatar-->
                            <div class="symbol symbol-100px symbol-circle mb-7">
                                @php
                                    $imageUrl = $user->image_url; // misalnya accessor di model yang menghasilkan full URL
                                    $hasImage = $user->image && File::exists(public_path('storage/' . $user->image));
                                @endphp
                                @if ($hasImage && $imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $user->name }}"
                                        style="width:100px; height:100px; object-fit:cover; object-position:center;" />
                                @else
                                    <div
                                        class="symbol-label bg-light-primary text-primary d-flex align-items-center justify-content-center">
                                        {{ $user->initials }}
                                    </div>
                                @endif
                            </div>
                            <!--end::Avatar-->
                            <!--begin::Name-->
                            <a href="#"
                                class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">{{ $user->name }}</a>
                            <!--end::Name-->
                            <!--begin::Position-->
                            <div class="mb-9">
                                <!--begin::Badge-->
                                <div class="badge badge-lg badge-light-primary d-inline">{{ $user->role_summary }}</div>
                                <!--begin::Badge-->
                            </div>
                            <!--end::Position-->
                            <!--begin::Info-->
                            <!--end::Info-->
                        </div>
                        <!--end::User Info-->
                        <!--end::Summary-->
                        <!--begin::Details toggle-->
                        <div class="d-flex flex-stack fs-4 py-3">
                            <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details"
                                role="button" aria-expanded="false" aria-controls="kt_user_view_details">Details
                                <span class="ms-2 rotate-180">
                                    <i class="ki-outline ki-down fs-3"></i>
                                </span>
                            </div>
                            <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit customer details">
                                <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_update_details">Edit</a>
                            </span>
                        </div>
                        <!--end::Details toggle-->
                        <div class="separator"></div>
                        <!--begin::Details content-->
                        <div id="kt_user_view_details" class="collapse show">
                            <div class="pb-5 fs-6">
                                <!--begin::Details item-->
                                <div class="fw-bold mt-5">Email</div>
                                <div class="text-gray-600">
                                    <a href="#" class="text-gray-600 text-hover-primary">{{ $user->email }}</a>
                                </div>
                                <!--begin::Details item-->
                                <!--begin::Details item-->
                                <div class="fw-bold mt-5">Last Login</div>
                                <div class="text-gray-600">10 Mar 2024, 8:43 pm</div>
                                <!--begin::Details item-->
                            </div>
                        </div>
                        <!--end::Details content-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Sidebar-->
            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-15">
                <!--begin:::Tabs-->
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8">
                    <!--begin:::Tab item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                            href="#kt_user_view_overview_tab">Overview</a>
                    </li>
                    <!--end:::Tab item-->
                    <!--begin:::Tab item-->
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-kt-countup-tabs="true" data-bs-toggle="tab"
                            href="#kt_user_view_overview_security">Security</a>
                    </li>
                    <!--end:::Tab item-->
                    <!--begin:::Tab item-->
                    <li class="nav-item ms-auto">
                        <a href="#" class="btn btn-danger ps-7" data-kt-users-detail="delete_row"
                            data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                            Hapus Pengguna
                        </a>
                    </li>
                    <!--end:::Tab item-->
                </ul>
                <!--end:::Tabs-->
                <!--begin:::Tab content-->
                <div class="tab-content" id="myTabContent">
                    <!--begin:::Tab pane-->
                    <div class="tab-pane fade show active" id="kt_user_view_overview_tab" role="tabpanel">
                        <!--begin::Card-->
                        <div class="card card-flush mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header mt-6">
                                <!--begin::Card title-->
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">User's Schedule</h2>
                                    <div class="fs-6 fw-semibold text-muted">2 upcoming meetings</div>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_add_schedule">
                                        <i class="ki-outline ki-brush fs-3"></i>Add Schedule</button>
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body p-9 pt-4">
                                <!--begin::Dates-->
                                <ul class="nav nav-pills d-flex flex-nowrap hover-scroll-x py-2">
                                    <!--begin::Date-->
                                    <li class="nav-item me-1">
                                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary"
                                            data-bs-toggle="tab" href="#kt_schedule_day_0">
                                            <span class="opacity-50 fs-7 fw-semibold">Su</span>
                                            <span class="fs-6 fw-bolder">21</span>
                                        </a>
                                    </li>
                                    <!--end::Date-->
                                    <!--begin::Date-->
                                    <li class="nav-item me-1">
                                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary active"
                                            data-bs-toggle="tab" href="#kt_schedule_day_1">
                                            <span class="opacity-50 fs-7 fw-semibold">Mo</span>
                                            <span class="fs-6 fw-bolder">22</span>
                                        </a>
                                    </li>
                                    <!--end::Date-->
                                    <!--begin::Date-->
                                    <li class="nav-item me-1">
                                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary"
                                            data-bs-toggle="tab" href="#kt_schedule_day_2">
                                            <span class="opacity-50 fs-7 fw-semibold">Tu</span>
                                            <span class="fs-6 fw-bolder">23</span>
                                        </a>
                                    </li>
                                    <!--end::Date-->
                                    <!--begin::Date-->
                                    <li class="nav-item me-1">
                                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary"
                                            data-bs-toggle="tab" href="#kt_schedule_day_3">
                                            <span class="opacity-50 fs-7 fw-semibold">We</span>
                                            <span class="fs-6 fw-bolder">24</span>
                                        </a>
                                    </li>
                                    <!--end::Date-->
                                    <!--begin::Date-->
                                    <li class="nav-item me-1">
                                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary"
                                            data-bs-toggle="tab" href="#kt_schedule_day_4">
                                            <span class="opacity-50 fs-7 fw-semibold">Th</span>
                                            <span class="fs-6 fw-bolder">25</span>
                                        </a>
                                    </li>
                                    <!--end::Date-->
                                    <!--begin::Date-->
                                    <li class="nav-item me-1">
                                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary"
                                            data-bs-toggle="tab" href="#kt_schedule_day_5">
                                            <span class="opacity-50 fs-7 fw-semibold">Fr</span>
                                            <span class="fs-6 fw-bolder">26</span>
                                        </a>
                                    </li>
                                    <!--end::Date-->
                                    <!--begin::Date-->
                                    <li class="nav-item me-1">
                                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary"
                                            data-bs-toggle="tab" href="#kt_schedule_day_6">
                                            <span class="opacity-50 fs-7 fw-semibold">Sa</span>
                                            <span class="fs-6 fw-bolder">27</span>
                                        </a>
                                    </li>
                                    <!--end::Date-->
                                    <!--begin::Date-->
                                    <li class="nav-item me-1">
                                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary"
                                            data-bs-toggle="tab" href="#kt_schedule_day_7">
                                            <span class="opacity-50 fs-7 fw-semibold">Su</span>
                                            <span class="fs-6 fw-bolder">28</span>
                                        </a>
                                    </li>
                                    <!--end::Date-->
                                    <!--begin::Date-->
                                    <li class="nav-item me-1">
                                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary"
                                            data-bs-toggle="tab" href="#kt_schedule_day_8">
                                            <span class="opacity-50 fs-7 fw-semibold">Mo</span>
                                            <span class="fs-6 fw-bolder">29</span>
                                        </a>
                                    </li>
                                    <!--end::Date-->
                                    <!--begin::Date-->
                                    <li class="nav-item me-1">
                                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary"
                                            data-bs-toggle="tab" href="#kt_schedule_day_9">
                                            <span class="opacity-50 fs-7 fw-semibold">Tu</span>
                                            <span class="fs-6 fw-bolder">30</span>
                                        </a>
                                    </li>
                                    <!--end::Date-->
                                    <!--begin::Date-->
                                    <li class="nav-item me-1">
                                        <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary"
                                            data-bs-toggle="tab" href="#kt_schedule_day_10">
                                            <span class="opacity-50 fs-7 fw-semibold">We</span>
                                            <span class="fs-6 fw-bolder">31</span>
                                        </a>
                                    </li>
                                    <!--end::Date-->
                                </ul>
                                <!--end::Dates-->
                                <!--begin::Tab Content-->
                                <div class="tab-content">
                                    <!--begin::Day-->
                                    <div id="kt_schedule_day_0" class="tab-pane fade show">
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">9 Degree
                                                    Project Estimation Meeting</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Caleb Donaldson</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">16:30 - 17:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Dashboard
                                                    UI/UX Design Review</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Mark Randall</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Creative
                                                    Content Initiative</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Mark Randall</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Sales Pitch
                                                    Proposal</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Peter Marcus</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">16:30 - 17:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Dashboard
                                                    UI/UX Design Review</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Caleb Donaldson</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                    </div>
                                    <!--end::Day-->
                                    <!--begin::Day-->
                                    <div id="kt_schedule_day_1" class="tab-pane fade show active">
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">14:30 - 15:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Marketing
                                                    Campaign Discussion</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Yannis Gloverson</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">9 Degree
                                                    Project Estimation Meeting</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Terry Robins</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">9 Degree
                                                    Project Estimation Meeting</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Peter Marcus</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Marketing
                                                    Campaign Discussion</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Michael Walters</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">16:30 - 17:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Marketing
                                                    Campaign Discussion</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Bob Harris</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                    </div>
                                    <!--end::Day-->
                                    <!--begin::Day-->
                                    <div id="kt_schedule_day_2" class="tab-pane fade show">
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">9 Degree
                                                    Project Estimation Meeting</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Terry Robins</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">10:00 - 11:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Development
                                                    Team Capacity Review</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Naomi Hayabusa</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">10:00 - 11:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">9 Degree
                                                    Project Estimation Meeting</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">David Stevenson</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Dashboard
                                                    UI/UX Design Review</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Sean Bean</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                    </div>
                                    <!--end::Day-->
                                    <!--begin::Day-->
                                    <div id="kt_schedule_day_3" class="tab-pane fade show">
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">16:30 - 17:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Project
                                                    Review & Testing</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Naomi Hayabusa</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Lunch &
                                                    Learn Catch Up</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Terry Robins</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">11:00 - 11:45
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Committee
                                                    Review Approvals</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Yannis Gloverson</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Creative
                                                    Content Initiative</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">David Stevenson</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                    </div>
                                    <!--end::Day-->
                                    <!--begin::Day-->
                                    <div id="kt_schedule_day_4" class="tab-pane fade show">
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Lunch &
                                                    Learn Catch Up</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Karina Clarke</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Lunch &
                                                    Learn Catch Up</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">David Stevenson</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Sales Pitch
                                                    Proposal</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Mark Randall</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Development
                                                    Team Capacity Review</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Terry Robins</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Marketing
                                                    Campaign Discussion</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Peter Marcus</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                    </div>
                                    <!--end::Day-->
                                    <!--begin::Day-->
                                    <div id="kt_schedule_day_5" class="tab-pane fade show">
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">11:00 - 11:45
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Creative
                                                    Content Initiative</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Sean Bean</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">10:00 - 11:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Development
                                                    Team Capacity Review</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Mark Randall</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">9 Degree
                                                    Project Estimation Meeting</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Sean Bean</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">14:30 - 15:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Sales Pitch
                                                    Proposal</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Kendell Trevor</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Lunch &
                                                    Learn Catch Up</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Bob Harris</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                    </div>
                                    <!--end::Day-->
                                    <!--begin::Day-->
                                    <div id="kt_schedule_day_6" class="tab-pane fade show">
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Lunch &
                                                    Learn Catch Up</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Caleb Donaldson</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Project
                                                    Review & Testing</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Bob Harris</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Lunch &
                                                    Learn Catch Up</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Kendell Trevor</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                    </div>
                                    <!--end::Day-->
                                    <!--begin::Day-->
                                    <div id="kt_schedule_day_7" class="tab-pane fade show">
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">14:30 - 15:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Team Backlog
                                                    Grooming Session</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Naomi Hayabusa</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Project
                                                    Review & Testing</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">David Stevenson</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Project
                                                    Review & Testing</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Sean Bean</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                    </div>
                                    <!--end::Day-->
                                    <!--begin::Day-->
                                    <div id="kt_schedule_day_8" class="tab-pane fade show">
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Development
                                                    Team Capacity Review</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Terry Robins</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">11:00 - 11:45
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Sales Pitch
                                                    Proposal</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Michael Walters</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">14:30 - 15:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Team Backlog
                                                    Grooming Session</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Terry Robins</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">14:30 - 15:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Sales Pitch
                                                    Proposal</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Walter White</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                    </div>
                                    <!--end::Day-->
                                    <!--begin::Day-->
                                    <div id="kt_schedule_day_9" class="tab-pane fade show">
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">10:00 - 11:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Project
                                                    Review & Testing</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Naomi Hayabusa</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">14:30 - 15:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Development
                                                    Team Capacity Review</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Walter White</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Project
                                                    Review & Testing</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Yannis Gloverson</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Sales Pitch
                                                    Proposal</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Yannis Gloverson</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                    </div>
                                    <!--end::Day-->
                                    <!--begin::Day-->
                                    <div id="kt_schedule_day_10" class="tab-pane fade show">
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">10:00 - 11:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Dashboard
                                                    UI/UX Design Review</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Mark Randall</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Sales Pitch
                                                    Proposal</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Sean Bean</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">11:00 - 11:45
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Project
                                                    Review & Testing</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Bob Harris</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                        <!--begin::Time-->
                                        <div class="d-flex flex-stack position-relative mt-6">
                                            <!--begin::Bar-->
                                            <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                                            </div>
                                            <!--end::Bar-->
                                            <!--begin::Info-->
                                            <div class="fw-semibold ms-5">
                                                <!--begin::Time-->
                                                <div class="fs-7 mb-1">10:00 - 11:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span>
                                                </div>
                                                <!--end::Time-->
                                                <!--begin::Title-->
                                                <a href="#"
                                                    class="fs-5 fw-bold text-gray-900 text-hover-primary mb-2">Marketing
                                                    Campaign Discussion</a>
                                                <!--end::Title-->
                                                <!--begin::User-->
                                                <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Walter White</a>
                                                </div>
                                                <!--end::User-->
                                            </div>
                                            <!--end::Info-->
                                            <!--begin::Action-->
                                            <a href="#"
                                                class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                            <!--end::Action-->
                                        </div>
                                        <!--end::Time-->
                                    </div>
                                    <!--end::Day-->
                                </div>
                                <!--end::Tab Content-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                        <!--begin::Tasks-->
                        <div class="card card-flush mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header mt-6">
                                <!--begin::Card title-->
                                <div class="card-title flex-column">
                                    <h2 class="mb-1">User's Tasks</h2>
                                    <div class="fs-6 fw-semibold text-muted">Total 25 tasks in backlog</div>
                                </div>
                                <!--end::Card title-->
                                <!--begin::Card toolbar-->
                                <div class="card-toolbar">
                                    <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#kt_modal_add_task">
                                        <i class="ki-outline ki-add-files fs-3"></i>Add Task</button>
                                </div>
                                <!--end::Card toolbar-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body d-flex flex-column">
                                <!--begin::Item-->
                                <div class="d-flex align-items-center position-relative mb-7">
                                    <!--begin::Label-->
                                    <div class="position-absolute top-0 start-0 rounded h-100 bg-secondary w-4px"></div>
                                    <!--end::Label-->
                                    <!--begin::Details-->
                                    <div class="fw-semibold ms-5">
                                        <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary">Create
                                            FureStibe branding logo</a>
                                        <!--begin::Info-->
                                        <div class="fs-7 text-muted">Due in 1 day
                                            <a href="#">Karina Clark</a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Details-->
                                    <!--begin::Menu-->
                                    <button type="button"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-outline ki-setting-3 fs-3"></i>
                                    </button>
                                    <!--begin::Task menu-->
                                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                                        data-kt-menu-id="kt-users-tasks">
                                        <!--begin::Header-->
                                        <div class="px-7 py-5">
                                            <div class="fs-5 text-gray-900 fw-bold">Update Status</div>
                                        </div>
                                        <!--end::Header-->
                                        <!--begin::Menu separator-->
                                        <div class="separator border-gray-200"></div>
                                        <!--end::Menu separator-->
                                        <!--begin::Form-->
                                        <form class="form px-7 py-5" data-kt-menu-id="kt-users-tasks-form">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="form-label fs-6 fw-semibold">Status:</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <select class="form-select form-select-solid" name="task_status"
                                                    data-kt-select2="true" data-placeholder="Select option"
                                                    data-allow-clear="true" data-hide-search="true">
                                                    <option></option>
                                                    <option value="1">Approved</option>
                                                    <option value="2">Pending</option>
                                                    <option value="3">In Process</option>
                                                    <option value="4">Rejected</option>
                                                </select>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Actions-->
                                            <div class="d-flex justify-content-end">
                                                <button type="button"
                                                    class="btn btn-sm btn-light btn-active-light-primary me-2"
                                                    data-kt-users-update-task-status="reset">Reset</button>
                                                <button type="submit" class="btn btn-sm btn-primary"
                                                    data-kt-users-update-task-status="submit">
                                                    <span class="indicator-label">Apply</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                            <!--end::Actions-->
                                        </form>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Task menu-->
                                    <!--end::Menu-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <div class="d-flex align-items-center position-relative mb-7">
                                    <!--begin::Label-->
                                    <div class="position-absolute top-0 start-0 rounded h-100 bg-secondary w-4px"></div>
                                    <!--end::Label-->
                                    <!--begin::Details-->
                                    <div class="fw-semibold ms-5">
                                        <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary">Schedule a
                                            meeting with FireBear CTO John</a>
                                        <!--begin::Info-->
                                        <div class="fs-7 text-muted">Due in 3 days
                                            <a href="#">Rober Doe</a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Details-->
                                    <!--begin::Menu-->
                                    <button type="button"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-outline ki-setting-3 fs-3"></i>
                                    </button>
                                    <!--begin::Task menu-->
                                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                                        data-kt-menu-id="kt-users-tasks">
                                        <!--begin::Header-->
                                        <div class="px-7 py-5">
                                            <div class="fs-5 text-gray-900 fw-bold">Update Status</div>
                                        </div>
                                        <!--end::Header-->
                                        <!--begin::Menu separator-->
                                        <div class="separator border-gray-200"></div>
                                        <!--end::Menu separator-->
                                        <!--begin::Form-->
                                        <form class="form px-7 py-5" data-kt-menu-id="kt-users-tasks-form">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="form-label fs-6 fw-semibold">Status:</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <select class="form-select form-select-solid" name="task_status"
                                                    data-kt-select2="true" data-placeholder="Select option"
                                                    data-allow-clear="true" data-hide-search="true">
                                                    <option></option>
                                                    <option value="1">Approved</option>
                                                    <option value="2">Pending</option>
                                                    <option value="3">In Process</option>
                                                    <option value="4">Rejected</option>
                                                </select>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Actions-->
                                            <div class="d-flex justify-content-end">
                                                <button type="button"
                                                    class="btn btn-sm btn-light btn-active-light-primary me-2"
                                                    data-kt-users-update-task-status="reset">Reset</button>
                                                <button type="submit" class="btn btn-sm btn-primary"
                                                    data-kt-users-update-task-status="submit">
                                                    <span class="indicator-label">Apply</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                            <!--end::Actions-->
                                        </form>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Task menu-->
                                    <!--end::Menu-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <div class="d-flex align-items-center position-relative mb-7">
                                    <!--begin::Label-->
                                    <div class="position-absolute top-0 start-0 rounded h-100 bg-secondary w-4px"></div>
                                    <!--end::Label-->
                                    <!--begin::Details-->
                                    <div class="fw-semibold ms-5">
                                        <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary">9 Degree
                                            Project Estimation</a>
                                        <!--begin::Info-->
                                        <div class="fs-7 text-muted">Due in 1 week
                                            <a href="#">Neil Owen</a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Details-->
                                    <!--begin::Menu-->
                                    <button type="button"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-outline ki-setting-3 fs-3"></i>
                                    </button>
                                    <!--begin::Task menu-->
                                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                                        data-kt-menu-id="kt-users-tasks">
                                        <!--begin::Header-->
                                        <div class="px-7 py-5">
                                            <div class="fs-5 text-gray-900 fw-bold">Update Status</div>
                                        </div>
                                        <!--end::Header-->
                                        <!--begin::Menu separator-->
                                        <div class="separator border-gray-200"></div>
                                        <!--end::Menu separator-->
                                        <!--begin::Form-->
                                        <form class="form px-7 py-5" data-kt-menu-id="kt-users-tasks-form">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="form-label fs-6 fw-semibold">Status:</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <select class="form-select form-select-solid" name="task_status"
                                                    data-kt-select2="true" data-placeholder="Select option"
                                                    data-allow-clear="true" data-hide-search="true">
                                                    <option></option>
                                                    <option value="1">Approved</option>
                                                    <option value="2">Pending</option>
                                                    <option value="3">In Process</option>
                                                    <option value="4">Rejected</option>
                                                </select>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Actions-->
                                            <div class="d-flex justify-content-end">
                                                <button type="button"
                                                    class="btn btn-sm btn-light btn-active-light-primary me-2"
                                                    data-kt-users-update-task-status="reset">Reset</button>
                                                <button type="submit" class="btn btn-sm btn-primary"
                                                    data-kt-users-update-task-status="submit">
                                                    <span class="indicator-label">Apply</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                            <!--end::Actions-->
                                        </form>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Task menu-->
                                    <!--end::Menu-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <div class="d-flex align-items-center position-relative mb-7">
                                    <!--begin::Label-->
                                    <div class="position-absolute top-0 start-0 rounded h-100 bg-secondary w-4px"></div>
                                    <!--end::Label-->
                                    <!--begin::Details-->
                                    <div class="fw-semibold ms-5">
                                        <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary">Dashboard
                                            UI & UX for
                                            Leafr CRM</a>
                                        <!--begin::Info-->
                                        <div class="fs-7 text-muted">Due in 1 week
                                            <a href="#">Olivia Wild</a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Details-->
                                    <!--begin::Menu-->
                                    <button type="button"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-outline ki-setting-3 fs-3"></i>
                                    </button>
                                    <!--begin::Task menu-->
                                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                                        data-kt-menu-id="kt-users-tasks">
                                        <!--begin::Header-->
                                        <div class="px-7 py-5">
                                            <div class="fs-5 text-gray-900 fw-bold">Update Status</div>
                                        </div>
                                        <!--end::Header-->
                                        <!--begin::Menu separator-->
                                        <div class="separator border-gray-200"></div>
                                        <!--end::Menu separator-->
                                        <!--begin::Form-->
                                        <form class="form px-7 py-5" data-kt-menu-id="kt-users-tasks-form">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="form-label fs-6 fw-semibold">Status:</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <select class="form-select form-select-solid" name="task_status"
                                                    data-kt-select2="true" data-placeholder="Select option"
                                                    data-allow-clear="true" data-hide-search="true">
                                                    <option></option>
                                                    <option value="1">Approved</option>
                                                    <option value="2">Pending</option>
                                                    <option value="3">In Process</option>
                                                    <option value="4">Rejected</option>
                                                </select>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Actions-->
                                            <div class="d-flex justify-content-end">
                                                <button type="button"
                                                    class="btn btn-sm btn-light btn-active-light-primary me-2"
                                                    data-kt-users-update-task-status="reset">Reset</button>
                                                <button type="submit" class="btn btn-sm btn-primary"
                                                    data-kt-users-update-task-status="submit">
                                                    <span class="indicator-label">Apply</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                            <!--end::Actions-->
                                        </form>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Task menu-->
                                    <!--end::Menu-->
                                </div>
                                <!--end::Item-->
                                <!--begin::Item-->
                                <div class="d-flex align-items-center position-relative">
                                    <!--begin::Label-->
                                    <div class="position-absolute top-0 start-0 rounded h-100 bg-secondary w-4px"></div>
                                    <!--end::Label-->
                                    <!--begin::Details-->
                                    <div class="fw-semibold ms-5">
                                        <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary">Mivy App
                                            R&D, Meeting with clients</a>
                                        <!--begin::Info-->
                                        <div class="fs-7 text-muted">Due in 2 weeks
                                            <a href="#">Sean Bean</a>
                                        </div>
                                        <!--end::Info-->
                                    </div>
                                    <!--end::Details-->
                                    <!--begin::Menu-->
                                    <button type="button"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                        data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        <i class="ki-outline ki-setting-3 fs-3"></i>
                                    </button>
                                    <!--begin::Task menu-->
                                    <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
                                        data-kt-menu-id="kt-users-tasks">
                                        <!--begin::Header-->
                                        <div class="px-7 py-5">
                                            <div class="fs-5 text-gray-900 fw-bold">Update Status</div>
                                        </div>
                                        <!--end::Header-->
                                        <!--begin::Menu separator-->
                                        <div class="separator border-gray-200"></div>
                                        <!--end::Menu separator-->
                                        <!--begin::Form-->
                                        <form class="form px-7 py-5" data-kt-menu-id="kt-users-tasks-form">
                                            <!--begin::Input group-->
                                            <div class="fv-row mb-10">
                                                <!--begin::Label-->
                                                <label class="form-label fs-6 fw-semibold">Status:</label>
                                                <!--end::Label-->
                                                <!--begin::Input-->
                                                <select class="form-select form-select-solid" name="task_status"
                                                    data-kt-select2="true" data-placeholder="Select option"
                                                    data-allow-clear="true" data-hide-search="true">
                                                    <option></option>
                                                    <option value="1">Approved</option>
                                                    <option value="2">Pending</option>
                                                    <option value="3">In Process</option>
                                                    <option value="4">Rejected</option>
                                                </select>
                                                <!--end::Input-->
                                            </div>
                                            <!--end::Input group-->
                                            <!--begin::Actions-->
                                            <div class="d-flex justify-content-end">
                                                <button type="button"
                                                    class="btn btn-sm btn-light btn-active-light-primary me-2"
                                                    data-kt-users-update-task-status="reset">Reset</button>
                                                <button type="submit" class="btn btn-sm btn-primary"
                                                    data-kt-users-update-task-status="submit">
                                                    <span class="indicator-label">Apply</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span
                                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                            <!--end::Actions-->
                                        </form>
                                        <!--end::Form-->
                                    </div>
                                    <!--end::Task menu-->
                                    <!--end::Menu-->
                                </div>
                                <!--end::Item-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Tasks-->
                    </div>
                    <!--end:::Tab pane-->
                    <!--begin:::Tab pane-->
                    <div class="tab-pane fade" id="kt_user_view_overview_security" role="tabpanel">
                        <!--begin::Card-->
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <!--begin::Card header-->
                            <div class="card-header border-0">
                                <!--begin::Card title-->
                                <div class="card-title">
                                    <h2>Profile</h2>
                                </div>
                                <!--end::Card title-->
                            </div>
                            <!--end::Card header-->
                            <!--begin::Card body-->
                            <div class="card-body pt-0 pb-5">
                                <!--begin::Table wrapper-->
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed gy-5"
                                        id="kt_table_users_login_session">
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                            <tr>
                                                <td>Password</td>
                                                <td>{{ str_repeat('*', 8) }}</td>
                                                <td class="text-end">
                                                    <button type="button"
                                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                        data-bs-toggle="modal" data-bs-target="#kt_modal_update_password">
                                                        <i class="ki-outline ki-pencil fs-3"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Role</td>
                                                <td>{{$user->role_summary}}</td>
                                                <td class="text-end">
                                                    <button type="button"
                                                        class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto"
                                                        data-bs-toggle="modal" data-bs-target="#kt_modal_update_role">
                                                        <i class="ki-outline ki-pencil fs-3"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Table wrapper-->
                            </div>
                            <!--end::Card body-->
                        </div>
                        <!--end::Card-->
                    </div>
                    <!--end:::Tab pane-->
                </div>
                <!--end:::Tab content-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Layout-->

        <!--begin::Modal - Update user details-->
        <div class="modal fade" id="kt_modal_update_details" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Form-->
                    <form class="form" action="#" id="kt_modal_update_user_form"
                        data-user-id="{{ $user->id }}">
                        <!--begin::Modal header-->
                        <div class="modal-header" id="kt_modal_update_user_header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">Perbarui Detail Pengguna</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                                <i class="ki-outline ki-cross fs-1"></i>
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body py-10 px-lg-17">
                            <!--begin::Scroll-->
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_update_user_scroll"
                                data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                data-kt-scroll-max-height="auto"
                                data-kt-scroll-dependencies="#kt_modal_update_user_header"
                                data-kt-scroll-wrappers="#kt_modal_update_user_scroll" data-kt-scroll-offset="300px">
                                <!--begin::User toggle-->
                                <div class="fw-bolder fs-3 rotate collapsible mb-7" data-bs-toggle="collapse"
                                    href="#kt_modal_update_user_user_info" role="button" aria-expanded="false"
                                    aria-controls="kt_modal_update_user_user_info">Informasi Pengguna
                                    <span class="ms-2 rotate-180">
                                        <i class="ki-outline ki-down fs-3"></i>
                                    </span>
                                </div>
                                <!--end::User toggle-->
                                <!--begin::User form-->
                                <div id="kt_modal_update_user_user_info" class="collapse show">
                                    <!--begin::Input group-->
                                    <div class="mb-7">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold mb-2">
                                            <span>Perbarui Avatar</span>
                                            <span class="ms-1" data-bs-toggle="tooltip"
                                                title="Allowed file types: png, jpg, jpeg.">
                                                <i class="ki-outline ki-information fs-7"></i>
                                            </span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Image input wrapper-->
                                        <div class="mt-1">
                                            <!--begin::Image placeholder-->
                                            @php
                                                // Ambil URL gambar dari accessor di model User (misal getImageUrlAttribute)
                                                $imageUrl = $user->image_url ?? null;
                                                $hasImage =
                                                    $user->image &&
                                                    Illuminate\Support\Facades\File::exists(
                                                        public_path('storage/' . $user->image),
                                                    );
                                            @endphp

                                            <style>
                                                .image-input-placeholder {
                                                    background-image: url('{{ asset('template/assets/media/svg/avatars/blank.svg') }}');
                                                }

                                                [data-bs-theme="dark"] .image-input-placeholder {
                                                    background-image: url('{{ asset('template/assets/media/svg/avatars/blank-dark.svg') }}');
                                                }
                                            </style>

                                            <!--begin::Image input-->
                                            <div class="image-input image-input-outline image-input-placeholder"
                                                data-kt-image-input="true">
                                                <!--begin::Preview existing avatar-->
                                                @if ($hasImage && $imageUrl)
                                                    <div class="image-input-wrapper img-fluid border"
                                                        style="
                                                        background-image: url('{{ $imageUrl }}');
                                                        background-size: cover;
                                                        background-position: center;
                                                        background-repeat: no-repeat;
                                                    ">
                                                    </div>
                                                @else
                                                    <div class="image-input-wrapper w-125px h-125px border bg-light-primary text-primary d-flex align-items-center justify-content-center fw-bold"
                                                        style="font-size: 2.5rem;">
                                                        {{ $user->initials }}
                                                    </div>
                                                @endif
                                                <!--end::Preview existing avatar-->

                                                <!--begin::Edit-->
                                                <label
                                                    class="btn btn-icon btn-circle btn-light-warning w-25px h-25px shadow"
                                                    data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                    title="Ubah foto">
                                                    <i class="ki-outline ki-pencil fs-7"></i>
                                                    <!--begin::Inputs-->
                                                    <input type="file" name="image" accept=".png, .jpg, .jpeg" />
                                                    <input type="hidden" name="image_remove" />
                                                    <!--end::Inputs-->
                                                </label>
                                                <!--end::Edit-->

                                                <!--begin::Cancel-->
                                                <span
                                                    class="btn btn-icon btn-circle btn-light-danger w-25px h-25px shadow"
                                                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                    title="Batal ganti">
                                                    <i class="ki-outline ki-cross fs-2"></i>
                                                </span>
                                                <!--end::Cancel-->

                                                <!--begin::Remove-->
                                                <span
                                                    class="btn btn-icon btn-circle btn-light-danger w-25px h-25px shadow"
                                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                    title="Hapus foto">
                                                    <i class="ki-outline ki-cross fs-2"></i>
                                                </span>
                                                <!--end::Remove-->
                                            </div>
                                            <!--end::Image input-->

                                            <!--end::Image input-->
                                        </div>
                                        <!--end::Image input wrapper-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold mb-2">Nama</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="text" class="form-control form-control-solid" placeholder=""
                                            name="name" value="{{ $user->name }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-7">
                                        <!--begin::Label-->
                                        <label class="fs-6 fw-semibold mb-2">
                                            <span>Email</span>
                                            <span class="ms-1" data-bs-toggle="tooltip"
                                                title="Email address must be active">
                                                <i class="ki-outline ki-information fs-7"></i>
                                            </span>
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <input type="email" class="form-control form-control-solid" placeholder=""
                                            name="email" value="{{ $user->email }}" />
                                        <!--end::Input-->
                                    </div>
                                    <!--end::Input group-->
                                </div>
                                <!--end::Scroll-->
                            </div>
                            <!--end::Modal body-->
                            <!--begin::Modal footer-->
                            <div class="modal-footer flex-center">
                                <!--begin::Button-->
                                <button type="reset" class="btn btn-light me-3"
                                    data-kt-users-modal-action="cancel">Batal</button>
                                <!--end::Button-->
                                <!--begin::Button-->
                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                    <span class="indicator-label">Perbarui</span>
                                    <span class="indicator-progress">Silahkan tunggu...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                                <!--end::Button-->
                            </div>
                            <!--end::Modal footer-->
                        </div>
                        <!--end::Scroll-->
                    </form>
                    <!--end::Form-->
                </div>
            </div>
        </div>
        <!--end::Modal - Update user details-->

        <!--begin::Modal - Add schedule-->
        <div class="modal fade" id="kt_modal_add_schedule" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Add an Event</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <!--begin::Form-->
                        <form id="kt_modal_add_schedule_form" class="form" action="#">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold form-label mb-2">Event Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="event_name"
                                    value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mb-2">
                                    <span class="required">Date & Time</span>
                                    <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover"
                                        data-bs-html="true" data-bs-content="Select a date & time.">
                                        <i class="ki-outline ki-information fs-7"></i>
                                    </span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" placeholder="Pick date & time"
                                    name="event_datetime" id="kt_modal_add_schedule_datepicker" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold form-label mb-2">Event Organiser</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="event_org"
                                    value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold form-label mb-2">Send Event Details To</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input id="kt_modal_add_schedule_tagify" type="text"
                                    class="form-control form-control-solid" name="event_invitees"
                                    value="smith@kpmg.com, melody@altbox.com" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3"
                                    data-kt-users-modal-action="cancel">Discard</button>
                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                    <span class="indicator-label">Submit</span>
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
        <!--end::Modal - Add schedule-->

        <!--begin::Modal - Add task-->
        <div class="modal fade" id="kt_modal_add_task" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Add a Task</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <!--begin::Form-->
                        <form id="kt_modal_add_task_form" class="form" action="#">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fs-6 fw-semibold form-label mb-2">Task Name</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="text" class="form-control form-control-solid" name="task_name"
                                    value="" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mb-2">
                                    <span class="required">Task Due Date</span>
                                    <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover"
                                        data-bs-html="true" data-bs-content="Select a due date.">
                                        <i class="ki-outline ki-information fs-7"></i>
                                    </span>
                                </label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input class="form-control form-control-solid" placeholder="Pick date"
                                    name="task_duedate" id="kt_modal_add_task_datepicker" />
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="fs-6 fw-semibold form-label mb-2">Task Description</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <textarea class="form-control form-control-solid rounded-3"></textarea>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3"
                                    data-kt-users-modal-action="cancel">Discard</button>
                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                    <span class="indicator-label">Submit</span>
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
        <!--end::Modal - Add task-->

        <!--begin::Modal - Update password-->
        <div class="modal fade" id="kt_modal_update_password" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Update Password</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <!--begin::Form-->
                        <form id="kt_modal_update_password_form" class="form" action="#"
                            data-user-id="{{ $user->id }}">
                            <!--begin::Input group=-->
                            <div class="fv-row mb-10">
                                <label class="required form-label fs-6 mb-2">Current Password</label>
                                <input class="form-control form-control-lg form-control-solid" type="password"
                                    placeholder="" name="current_password" autocomplete="off" />
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Input group-->
                            <div class="mb-10 fv-row" data-kt-password-meter="true">
                                <!--begin::Wrapper-->
                                <div class="mb-1">
                                    <!--begin::Label-->
                                    <label class="form-label fw-semibold fs-6 mb-2">New Password</label>
                                    <!--end::Label-->
                                    <!--begin::Input wrapper-->
                                    <div class="position-relative mb-3">
                                        <input class="form-control form-control-lg form-control-solid" type="password"
                                            placeholder="" name="new_password" autocomplete="off" />
                                        <span
                                            class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                            data-kt-password-meter-control="visibility">
                                            <i class="ki-outline ki-eye-slash fs-1"></i>
                                            <i class="ki-outline ki-eye d-none fs-1"></i>
                                        </span>
                                    </div>
                                    <!--end::Input wrapper-->
                                    <!--begin::Meter-->
                                    <div class="d-flex align-items-center mb-3"
                                        data-kt-password-meter-control="highlight">
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                    </div>
                                    <!--end::Meter-->
                                </div>
                                <!--end::Wrapper-->
                                <!--begin::Hint-->
                                <div class="text-muted">Gunakan 8 karakter atau lebih.
                                </div>
                                <!--end::Hint-->
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Input group=-->
                            <div class="fv-row mb-10">
                                <label class="form-label fw-semibold fs-6 mb-2">Confirm New Password</label>
                                <input class="form-control form-control-lg form-control-solid" type="password"
                                    placeholder="" name="confirm_password" autocomplete="off" />
                            </div>
                            <!--end::Input group=-->
                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3"
                                    data-kt-users-modal-action="cancel">Batal</button>
                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                    <span class="indicator-label">Perbarui</span>
                                    <span class="indicator-progress">Silakan tunggu...
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
        <!--end::Modal - Update password-->

        <!--begin::Modal - Update role-->
        <div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
            <!--begin::Modal dialog-->
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <!--begin::Modal content-->
                <div class="modal-content">
                    <!--begin::Modal header-->
                    <div class="modal-header">
                        <!--begin::Modal title-->
                        <h2 class="fw-bold">Perbarui Pera User</h2>
                        <!--end::Modal title-->
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <!--end::Modal header-->
                    <!--begin::Modal body-->
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <!--begin::Form-->
                        <form id="kt_modal_update_role_form" class="form" action="#" data-user-id="{{ $user->id }}">
                            <!--begin::Input group-->
                            <div class="fv-row mb-7" data-user-roles="{{ json_encode($user->roles->pluck('id')->toArray()) }}">
                                <label class="fs-6 fw-semibold form-label mb-5">
                                    <span class="required">Pilih Role Pengguna</span>
                                </label>
                                @foreach ($roles as $role)
    <div class="d-flex flex-column mb-5">
        <div class="form-check form-check-custom form-check-solid align-items-start">
            <input
                class="form-check-input me-3 mt-1 role-radio-style"
                name="user_role[]"
                type="checkbox"
                id="kt_modal_update_role_option_{{ $loop->index }}"
                value="{{ $role->id }}"
                @checked(in_array($role->id, old('user_role', $user->roles->pluck('id')->toArray() ?? [])))
            />

            <label class="form-check-label" for="kt_modal_update_role_option_{{ $loop->index }}">
                <div class="fw-bold text-gray-800">{{ $role->name }}</div>

                @if ($role->permissions->count())
                    <div class="mt-2">
                        <div class="d-flex flex-wrap gap-1">
                            @foreach ($role->permissions as $permission)
                                <span class="badge bg-light text-gray-700 border border-secondary fw-normal fs-8">
                                    <i class="ki-duotone ki-check-circle fs-7 text-success me-1"></i>
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-gray-500 small fst-italic">Tidak ada permission</div>
                @endif
            </label>
        </div>
    </div>

    @if (!$loop->last)
        <div class="separator separator-dashed my-5"></div>
    @endif
@endforeach

                            </div>
                            <!--end::Input group-->
                            <!--begin::Actions-->
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3"
                                    data-kt-users-modal-action="cancel">Batal</button>
                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                    <span class="indicator-label">Simpan</span>
                                    <span class="indicator-progress">Mohon tunggu...
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
    {{-- Delete --}}
    <script>
        document.addEventListener('click', function(e) {
            const button = e.target.closest('[data-kt-users-detail="delete_row"]');
            if (!button) return;

            e.preventDefault();

            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');

            Swal.fire({
                text: `Yakin hendak menghapus pengguna dengan nama ${userName}?`,
                icon: "warning",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, batalkan",
                customClass: {
                    confirmButton: "btn fw-bold btn-danger",
                    cancelButton: "btn fw-bold btn-active-light-primary",
                },
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Disable tombol agar tidak double click
                    button.setAttribute('disabled', true);

                    $.ajax({
                        url: "{{ url('setting/user') }}/" + userId,
                        type: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        success: function(response) {
                            Swal.fire({
                                text: response.message || "Pengguna berhasil dihapus.",
                                icon: "success",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            }).then(() => {
                                // Redirect ke route setelah sukses
                                window.location.href =
                                    "{{ route('setting.user.index') }}";
                            });
                        },
                        error: function(xhr) {
                            const res = xhr.responseJSON;
                            Swal.fire({
                                text: res?.message ||
                                    "Terjadi kesalahan saat menghapus pengguna.",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "Ok, got it!",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                },
                            }).then(() => {
                                button.removeAttribute('disabled');
                            });
                        },
                    });
                }
            });
        });
    </script>

    {{-- <script src="{{ asset('template/assets/js/custom/apps/user-management/users/view/view.js') }}"></script> --}}
    <script src="{{ asset('template/assets/js/custom/apps/user-management/users/view/update-details-new.js') }}"></script>
    <script src="{{ asset('template/assets/js/custom/apps/user-management/users/view/add-schedule.js') }}"></script>
    <script src="{{ asset('template/assets/js/custom/apps/user-management/users/view/add-task.js') }}"></script>
    <script src="{{ asset('template/assets/js/custom/apps/user-management/users/view/update-password-new.js') }}"></script>
    <script src="{{ asset('template/assets/js/custom/apps/user-management/users/view/update-role-new.js') }}"></script>
@endpush
