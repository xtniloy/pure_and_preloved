<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Pure and Preloved')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow" />
    <meta name="description" content="@yield('meta_description', '')">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon/favicon.png') }}">

    <!-- Google Fonts (CDN is correct) -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Muli:wght@200;300;400;500;600;700;800;900&display=swap">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400..900&display=swap">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/linearicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/font-awesome.min.css') }}">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/slick.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @stack('styles')
</head>

<body>

{{-- Header --}}
@include('public.layouts.nav.header')

{{-- Mobile Header --}}
@include('public.layouts.nav.mobile_header')

@include('public.layouts.nav.mobile_off_canvas')

{{-- Page Content --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
@include('public.layouts.nav.footer')

{{-- Modals --}}
@include('public.modal.product_modal')

<!-- Vendor JS -->
<script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/jquery-migrate-3.3.2.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/modernizr-3.11.2.min.js') }}"></script>

<!-- Plugin JS -->
<script src="{{ asset('assets/js/plugins/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/slick.js') }}"></script>
<script src="{{ asset('assets/js/plugins/countdown.js') }}"></script>
<script src="{{ asset('assets/js/plugins/scrollup.js') }}"></script>
<script src="{{ asset('assets/js/plugins/elevateZoom.js') }}"></script>

<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}"></script>

<script>
$(document).ready(function() {
    $(document).on('click', '.quick_view', function(e) {
        e.preventDefault();
        var productId = $(this).data('product-id');
        var modalBody = $('#quickview-modal-body');

        if (!productId) return;

        // Show spinner
        modalBody.html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        const QUICK_VIEW_URL = "{{ route('product.quickview', ':id') }}";

        $.ajax({
            url: QUICK_VIEW_URL.replace(':id', productId),
            method: 'GET',
            success: function(response) {
                modalBody.html(response);

                // Re-initialize sliders
                $('.gallery-top').slick({
                    autoplay: false,
                    autoplaySpeed: 1000,
                    pauseOnHover: true,
                    arrows: false,
                    dots: false,
                    infinite: true,
                    fade: true,
                    asNavFor: '.gallery-thumbs',
                });
                $('.gallery-thumbs').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    arrows: true,
                    prevArrow: '<span class="prev"><i class="ion-ios-arrow-left"></i></span>',
                    nextArrow: '<span class="next"><i class="ion-ios-arrow-right"></i></span>',
                    dots: false,
                    infinite: true,
                    focusOnSelect: true,
                    loop: true,
                    asNavFor: '.gallery-top',
                });

                // Re-initialize cart plus minus
                var CartPlusMinus = $(".cart-plus-minus");
                CartPlusMinus.prepend('<div class="dec qtybutton">-</div>');
                CartPlusMinus.append('<div class="inc qtybutton">+</div>');
                $(".qtybutton").on("click", function() {
                    var $button = $(this);
                    var oldValue = $button.parent().find("input").val();
                    if ($button.text() === "+") {
                        var newVal = parseFloat(oldValue) + 1;
                    } else {
                        if (oldValue > 1) {
                            var newVal = parseFloat(oldValue) - 1;
                        } else {
                            newVal = 1;
                        }
                    }
                    $button.parent().find("input").val(newVal);
                });
            },
            error: function() {
                modalBody.html('<div class="alert alert-danger">Error loading product details.</div>');
            }
        });
    });
});
</script>

@stack('scripts')
</body>
</html>
