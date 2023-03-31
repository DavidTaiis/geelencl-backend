<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'LANZA') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset("images/favicon/apple-touch-icon.png")}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset("images/favicon/favicon-32x32.png")}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset("images/favicon/favicon-16x16.png")}}">
    <link rel="manifest" href="{{asset("images/favicon/site.webmanifest")}}">
    <link rel="mask-icon" href="{{asset("images/favicon/safari-pinned-tab.svg")}}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!--STYLESHEET-->
    <!--=================================================-->

    <!--Open Sans Font [ OPTIONAL ]-->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>


    <!--Bootstrap Stylesheet [ REQUIRED ]-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" />

    <!--Nifty Stylesheet [ REQUIRED ]-->
    <link href="{{asset("nifty/css/nifty.min.css")}}" rel="stylesheet">

    <link href="{{asset('nifty/plugins/summernote/summernote.min.css')}}"
          rel="stylesheet">

    <!--Nifty Premium Icon [ DEMONSTRATION ]-->
    <link href="{{asset("nifty/css/demo/nifty-demo-icons.min.css")}}" rel="stylesheet">
    <link href="{{asset("nifty/css/themes/type-b/theme-dust.css")}}" rel="stylesheet">
    <link href="{{asset("css/custom.css")}}" rel="stylesheet">


    <!--JAVASCRIPT-->
    <!--=================================================-->

    <!--Pace - Page Load Progress Par [OPTIONAL]-->
    <link href="{{asset("nifty/plugins/pace/pace.min.css")}}" rel="stylesheet">
    <link href="{{asset("nifty/plugins/magic-check/css/magic-check.min.css")}}" rel="stylesheet">
    <link href="{{asset('nifty/plugins/datatables/media/css/dataTables.bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('nifty/plugins/datatables/extensions/Responsive/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('nifty/plugins/datatables/extensions/Responsive/css/responsive.dataTables.min.css')}}" rel="stylesheet">
    <link href="{{asset('nifty/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('js/plugins/bootstrap-toggle/css/bootstrap-toggle.css')}}" rel="stylesheet">
    <link href="{{asset('js/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet"/>
{{--    <link href="{{asset('nifty/plugins/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet"/>--}}



    @yield('additional-styles')
    @include('layouts.partials.scripts')

</head>