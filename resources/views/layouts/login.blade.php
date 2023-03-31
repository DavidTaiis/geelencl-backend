<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->
@include('layouts.partials.head')
<style  nonce="{{ csp_nonce() }}">
    .brand-icon-login {
        width: 150px;
    }
    .brand-title {
        text-align: center;
        width: 100%;
    }
</style>
<!-- end::Head -->
<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
<body>
<div id="container" class="cls-container">
    <!-- BACKGROUND IMAGE -->
    <!--===================================================-->
    <div id="bg-overlay"></div>

    @yield('content')
    <!-- LOGIN FORM -->
    <!--===================================================-->

    <!--===================================================-->
</div>
<!--===================================================-->
<!-- END OF CONTAINER -->

</body>
</html>



