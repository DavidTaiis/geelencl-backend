<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->
@include('layouts.partials.head')
<!-- end::Head -->

<!--TIPS-->
<!--You may remove all ID or Class names which contain "demo-", they are only used for demonstration. -->
<body>
<div id="container" class="effect aside-float aside-bright mainnav-lg">
    <!--NAVBAR-->
    <!--===================================================-->
@include('layouts.partials.header')
<!--===================================================-->
    <!--END NAVBAR-->
    <div class="boxed">
        <!--CONTENT CONTAINER-->
        <!--===================================================-->
        <div id="content-container">
            <div id="page-head">

                <!--Page Title-->
                <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
            @yield('page_title')
            <!--End page title-->
            </div>
            <!--Page content-->
            <!--===================================================-->
            <div id="page-content">
                @yield('content')
            </div>
            <!--===================================================-->
            <!--End page content-->

        </div>
        <!--===================================================-->
        <!--END CONTENT CONTAINER-->

        <!--MAIN NAVIGATION-->
        <!--===================================================-->
    @include('layouts.partials.sidebar')
    <!--===================================================-->
        <!--END MAIN NAVIGATION-->

    </div>

    <!-- FOOTER -->
    <!--===================================================-->
@include('layouts.partials.footer')
<!--===================================================-->
    <!-- END FOOTER -->
    <!-- SCROLL PAGE BUTTON -->
    <!--===================================================-->
    <button class="scroll-top btn">
        <i class="pci-chevron chevron-up"></i>
    </button>
    <!--===================================================-->
</div>
<!--===================================================-->
<!-- END OF CONTAINER -->
</body>
</html>




