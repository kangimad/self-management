<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="275px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_toggle">
    <!--begin::Sidebar nav-->
    <div class="app-sidebar-wrapper py-8 py-lg-10" id="kt_app_sidebar_wrapper">
        <!--begin::Nav wrapper-->
        <div id="kt_app_sidebar_nav_wrapper" class="d-flex flex-column px-8 px-lg-10 hover-scroll-y"
            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
            data-kt-scroll-dependencies="{default: false, lg: '#kt_app_header'}"
            data-kt-scroll-wrappers="#kt_app_sidebar, #kt_app_sidebar_wrapper"
            data-kt-scroll-offset="{default: '10px', lg: '40px'}">
            <!--begin::Links-->
            <div class="row g-5" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                @if (request()->routeIs('dashboard*'))
                @endif

                @if (request()->routeIs('finance*'))
                @endif

                @if (request()->routeIs('task*'))
                @endif

                @if (request()->routeIs('event*'))
                @endif

                @if (request()->routeIs('setting*'))
                    @can('user-list')
                        <!--begin::Col-->
                        <div class="col-12">
                            <!--begin::Link-->
                            <a href="{{ route('setting.user.index') }}"
                                class="btn btn-icon btn-outline btn-bg-light {{ request()->routeIs('setting.user*') ? 'btn-light-primary' : '' }} btn-flex justify-content-start w-100 h-100 border-gray-200 p-3"
                                data-kt-button="true">
                                <!--begin::Icon-->
                                <span class="me-2">
                                    <i class="ki-outline ki-user fs-1"></i>
                                </span>
                                <!--end::Icon-->

                                <!--begin::Label-->
                                <span class="fs-7 fw-bold">Pengguna</span>
                                <!--end::Label-->
                            </a>
                            <!--end::Link-->
                        </div>
                        <!--end::Col-->
                    @endcan

                    @can('role-list')
                        <!--begin::Col-->
                        <div class="col-12">
                            <!--begin::Link-->
                            <a href="{{ route('setting.role.index') }}"
                                class="btn btn-icon btn-outline btn-bg-light {{ request()->routeIs('setting.role*') ? 'btn-light-primary' : '' }} btn-flex justify-content-start w-100 h-100 border-gray-200 p-3"
                                data-kt-button="true">
                                <!--begin::Icon-->
                                <span class="me-2">
                                    <i class="ki-outline ki-shield-tick fs-1"></i>
                                </span>
                                <!--end::Icon-->

                                <!--begin::Label-->
                                <span class="fs-7 fw-bold">Role</span>
                                <!--end::Label-->
                            </a>
                            <!--end::Link-->
                        </div>
                        <!--end::Col-->
                    @endcan

                    @can('permission-list')
                        <!--begin::Col-->
                        <div class="col-12">
                            <!--begin::Link-->
                            <a href="{{ route('setting.permission.index') }}"
                                class="btn btn-icon btn-outline btn-bg-light {{ request()->routeIs('setting.permission*') ? 'btn-light-primary' : '' }} btn-flex justify-content-start w-100 h-100 border-gray-200 p-3"
                                data-kt-button="true">
                                <!--begin::Icon-->
                                <span class="me-2">
                                    <i class="ki-outline ki-key fs-1"></i>
                                </span>
                                <!--end::Icon-->

                                <!--begin::Label-->
                                <span class="fs-7 fw-bold">Permission</span>
                                <!--end::Label-->
                            </a>
                            <!--end::Link-->
                        </div>
                        <!--end::Col-->
                    @endcan
                @endif
            </div> <!--end::Links-->
        </div>
        <!--end::Nav wrapper-->
    </div>
    <!--end::Sidebar nav-->
</div>
