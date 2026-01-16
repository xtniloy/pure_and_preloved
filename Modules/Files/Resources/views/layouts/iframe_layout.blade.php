<!DOCTYPE html>
<html lang="en">
<head>
    <!--<base href="./">-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="File uploader iframe">
    <meta name="author" content="Shahriar Parvez">
    <meta name="keyword" content="Bootstrap,Admin,Template,SCSS,HTML,RWD,Dashboard">
    <title>File Uploader</title>
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('panel/assets/favicon/apple-icon-57x57.png') }}">
    <link rel="manifest" href="{{ asset('panel/assets/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('panel/assets/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="{{ asset('panel/assets/vendors/simplebar/css/simplebar.css') }}">
    <!-- Main styles for this application-->
    <link href="{{ asset('panel/assets/css/style.css') }}" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link href="{{ asset('panel/assets/css/examples.css') }}" rel="stylesheet">
    <script src="{{ asset('panel/assets/js/config.js') }}"></script>
    <script src="{{ asset('panel/assets/js/color-modes.js') }}"></script>

    <link href="{{ asset('panel/assets/vendors/@coreui/chartjs/css/coreui-chartjs.css') }}" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>


<div class="wrapper d-flex flex-column min-vh-100">

    <div class="body flex-grow-1">
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
<!-- Plugins and scripts required by this view-->
<script src="{{ asset('panel/assets/vendors/chart.js/js/chart.umd.js') }}"></script>
<script src="{{ asset('panel/assets/vendors/@coreui/chartjs/js/coreui-chartjs.js') }}"></script>
<script src="{{ asset('panel/assets/vendors/@coreui/utils/js/index.js') }}"></script>
<script src="{{ asset('panel/assets/js/main.js') }}"></script>
<script>
</script>

@stack('js')

</body>
</html>
