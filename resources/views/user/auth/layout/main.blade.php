<!DOCTYPE html>

<html lang="en">
<head>
    <!--<base href="./">-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="{{config('app.name')}} - Auth panel">
    <meta name="author" content="{{config('app.name')}}">
    <meta name="keyword" content="{{config('app.name')}},Login, Registration, Forget Password">
    <title>@yield('page-title')</title>
    @include('partials.favicon')
    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{asset('panel/assets/vendors/simplebar/css/simplebar.css')}}">
    <!-- Main styles for this application-->
    <link href="{{asset('panel/assets/css/style.css')}}" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="{{asset('panel/assets/css/examples.css')}}" rel="stylesheet">
    <script src="{{asset('panel/assets/js/config.js')}}"></script>
{{--    <script src="{{asset('panel/assets/js/color-modes.js')}}"></script>--}}
    @stack('css')
    <!-- Google Tag Manager-->

    <!-- End Google Tag Manager-->
</head>
<body>
<!-- Google Tag Manager (noscript)-->

<!-- End Google Tag Manager (noscript)-->
<div class="min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        @yield('content')
    </div>
</div>
<!-- CoreUI and necessary plugins-->
<script src="{{ asset('panel/assets/vendors/@coreui/coreui-pro/js/coreui.bundle.min.js') }}"></script>
<script src="{{ asset('panel/assets/vendors/simplebar/js/simplebar.min.js') }}"></script>
<script>
    const header = document.querySelector('header.header');

    document.addEventListener('scroll', () => {
        if (header) {
            header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
        }
    });
</script>
@stack('js')

</body>
</html>
