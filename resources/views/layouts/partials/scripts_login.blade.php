<!--begin::Base Scripts -->
<script src="{{ asset('metronic/vendors/base/vendors.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('metronic/demo/default/base/scripts.bundle.js') }}" type="text/javascript"></script>
<!--end::Base Scripts -->
<!--begin::Page Snippets -->
<script src="{{ asset('js/login.js') }}" type="text/javascript"></script>
<link href="{{asset("css/login_custom.css")}}" rel="stylesheet">
<!--end::Page Snippets -->
<script nonce="{{ csp_nonce() }}">
</script>