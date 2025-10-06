<!DOCTYPE html>

<html lang="en">

<head>
    <title>Login - Self Management</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('template/my-self-management.png') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Saira+Stencil+One&display=swap" rel="stylesheet">
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('template/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('template/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <style>
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .is-invalid {
            border-color: #dc3545;
        }
    </style>
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
</head>

<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center bgi-no-repeat">
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <!--end::Theme mode setup on page load-->

    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Page bg image-->
        <style>
            body {
                background-image: url('{{ asset('template/assets/media/auth/bg4.jpg') }}');
            }

            [data-bs-theme="dark"] body {
                background-image: url('{{ asset('template/assets/media/auth/bg4-dark.jpg') }}');
            }
        </style>
        <!--end::Page bg image-->

        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column flex-column-fluid flex-lg-row">
            <!--begin::Aside-->
            <div class="d-flex flex-center w-lg-50 pt-15 pt-lg-0 px-10">
                <!--begin::Aside-->
                <div class="d-flex flex-center flex-lg-start flex-column">
                    <!--begin::Logo-->
                    <a href="index.html" class="d-flex align-items-center mb-7 text-decoration-none">
                        <img alt="Logo" class="h-50px" src="{{ asset('template/my-self-management.png') }}" />
                        <span class="ms-2 text-white mb-0"
                            style="font-family: 'Saira Stencil One', sans-serif; font-weight: 400; font-size: 30px;">
                            Self Management
                        </span>
                    </a>
                    <!--end::Logo-->
                    <!--begin::Title-->
                    <h2 class="text-white fw-normal m-0">Realisasikan "Husnud Tadbir Minal Ma'isyah"</h2>
                    <!--end::Title-->
                </div>
                <!--begin::Aside-->
            </div>
            <!--begin::Aside-->

            <!--begin::Body-->
            <div
                class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12 p-lg-20">
                <!--begin::Card-->
                <div class="bg-body d-flex flex-column align-items-stretch flex-center rounded-4 w-md-600px p-20">
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-center flex-column flex-column-fluid px-lg-10 pb-15 pb-lg-20">
                        <!--begin::Form-->
                        <form class="form w-100" id="kt_sign_in_form" method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Session Status -->
                            @if (session('status'))
                                <div class="alert alert-success mb-4">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <!--begin::Heading-->
                            <div class="text-center mb-11">
                                <!--begin::Title-->
                                <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
                                <!--end::Title-->
                                <!--begin::Subtitle-->
                                <div class="text-gray-500 fw-semibold fs-6">Your Social Campaigns</div>
                                <!--end::Subtitle=-->
                            </div>
                            <!--begin::Heading-->

                            <!--begin::Input group=-->
                            <div class="fv-row mb-8">
                                <!--begin::Email-->
                                <input type="email" placeholder="Email" name="email" autocomplete="username"
                                    class="form-control bg-transparent @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required autofocus />
                                <!--end::Email-->
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!--end::Input group=-->
                            <div class="fv-row mb-3">
                                <!--begin::Password-->
                                <input type="password" placeholder="Password" name="password"
                                    autocomplete="current-password"
                                    class="form-control bg-transparent @error('password') is-invalid @enderror"
                                    required />
                                <!--end::Password-->
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!--end::Input group=-->

                            <!-- Remember Me -->
                            <div class="fv-row mb-8">
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="remember" value="1" />
                                    <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">Remember
                                        me</span>
                                </label>
                            </div>

                            <!--begin::Submit button-->
                            <div class="d-grid mb-10">
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">Sign In</span>
                                    <!--end::Indicator label-->
                                    <!--begin::Indicator progress-->
                                    <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    <!--end::Indicator progress-->
                                </button>
                            </div>
                            <!--end::Submit button-->
                            <!--begin::Links-->
                            <div class="text-center">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="link-primary fw-semibold fs-6">
                                        Forgot your password?
                                    </a>
                                @endif
                                <div class="text-gray-500 text-center fw-semibold fs-6 mt-4">Not a Member yet?
                                    <a href="{{ route('register') }}" class="link-primary fw-semibold">Sign up</a>
                                </div>
                            </div>
                            <!--end::Links-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Wrapper-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->

    <!--begin::Javascript-->
    <script>
        var hostUrl = "template/assets/";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('template/assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('template/assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Custom Javascript(used for this page only) - Removed for Laravel validation-->
    <!--<script src="{{ asset('template/assets/js/custom/authentication/sign-in/general.js') }}"></script>-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#kt_sign_in_form');
            const submitButton = document.querySelector('#kt_sign_in_submit');

            if (form && submitButton) {
                form.addEventListener('submit', function() {
                    // Show loading indicator
                    submitButton.setAttribute('data-kt-indicator', 'on');
                    submitButton.disabled = true;

                    // Change button text
                    const label = submitButton.querySelector('.indicator-label');
                    const progress = submitButton.querySelector('.indicator-progress');
                    if (label && progress) {
                        label.style.display = 'none';
                        progress.style.display = 'inline-block';
                    }
                });
            }
        });
    </script>
    <!--end::Custom Javascript-->
    <!--end::Javascript-->
</body>

</html>
