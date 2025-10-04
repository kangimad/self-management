<script>
    var hostUrl = "template/assets/";
</script>

<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{ asset('template/assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('template/assets/js/scripts.bundle.js') }}"></script>
<!--end::Global Javascript Bundle-->

<!--begin::Vendors Javascript(used for this page only)-->
@stack('vendor-script')
<!--end::Vendors Javascript-->

<!--begin::Custom Javascript(used for this page only)-->
@stack('custom-script')
<!--end::Custom Javascript-->
