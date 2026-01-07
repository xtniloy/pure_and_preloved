<header class="header-wrapper">
    <div class="header-top bg-white  d-lg-block d-none">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2 d-flex" style="padding:10px 0;">
                    <div class="logo align-self-center ">
                        <a href="{{ url('/') }}"><img class="img-responsive-header" src="{{ asset('assets/images/logo/logo.jpg.png') }}" alt="logo.jpg" /></a>
                    </div>
                </div>
                <div class="col-md-3  d-flex align-items-end" style="margin-bottom: calc(-1 * var(--bs-nav-tabs-border-width));">
                    <ul class="nav nav-tabs menu-nav-tabs big-tabs">
                        <li class="nav-item">
                            <a class="nav-link menu-nav-link text-center fw-bold {{ $activeGender == 'women' ? 'active' : '' }}" aria-current="page" href="{{ url()->current() }}?gender=women">Women</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link menu-nav-link text-center fw-bold {{ $activeGender == 'man' ? 'active' : '' }}" href="{{ url()->current() }}?gender=man">Man</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-7 justify-content-end"  style="padding: 20px 0;">
                    <div class="header-right-element d-flex">
                        <div class="search-element media-body mr-20px">
                            <form class="d-flex" action="#">
                                <input type="text" placeholder="Enter your search key ... " />
                                <!--                                <button>Search</button>-->
                                <button><i class="lnr lnr-magnifier"></i></button>
                            </form>
                        </div>
                        <!--Cart info Start -->
                        <div class="header-tools d-flex">
                            <div class="cart-info d-flex align-self-center">
                                <a href="{{ url('/') }}#offcanvas-wishlist" class="heart offcanvas-toggle" data-count="5"><i class="lnr lnr-heart"></i><span></span></a>
                                <a href="{{ url('/') }}#offcanvas-cart" class="heart offcanvas-toggle" data-count="4"><i class="lnr lnr-cart"></i><span></span></a>
                                <a href="#" class=""><i class="lnr lnr-user" data-count="0"></i><span></span></a>
                            </div>
                        </div>
                    </div>
                    <!--Cart info End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Header Nav End -->

    <div class="header-menu bg-white sticky-nav d-lg-block d-none padding-0px">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-horizontal-menu">
                        <div class="d-flex justify-content-center">
                            <ul class="menu-content">
{{--                                <li class="active menu-dropdown">--}}
{{--                                    <a href="#">Home <i class="ion-ios-arrow-down"></i></a>--}}
{{--                                    <ul class="main-sub-menu">--}}
{{--                                        <li><a href="{{ url('/') }}">Home 1</a></li>--}}
{{--                                        <li><a href="{{ url('index-2') }}">Home 2</a></li>--}}
{{--                                        <li><a href="{{ url('index-3') }}">Home 3</a></li>--}}
{{--                                        <li><a href="{{ url('index-4') }}">Home 4</a></li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}
{{--                                <li class="menu-dropdown">--}}
{{--                                    <a href="#">Shop <i class="ion-ios-arrow-down"></i></a>--}}
{{--                                    <ul class="mega-menu-wrap">--}}
{{--                                        <li>--}}
{{--                                            <ul>--}}
{{--                                                <li class="mega-menu-title"><a href="#">Shop Grid</a></li>--}}
{{--                                                <li><a href="{{ url('shop-3-column') }}">Shop Grid 3 Column</a></li>--}}
{{--                                                <li><a href="{{ url('shop-4-column') }}">Shop Grid 4 Column</a></li>--}}
{{--                                                <li><a href="{{ url('shop-left-sidebar') }}">Shop Grid Left Sidebar</a></li>--}}
{{--                                                <li><a href="{{ url('shop-right-sidebar') }}">Shop Grid Right Sidebar</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <ul>--}}
{{--                                                <li class="mega-menu-title"><a href="#">Shop List</a></li>--}}
{{--                                                <li><a href="{{ url('shop-list') }}">Shop List</a></li>--}}
{{--                                                <li><a href="{{ url('shop-list-left-sidebar') }}">Shop List Left Sidebar</a></li>--}}
{{--                                                <li><a href="{{ url('shop-list-right-sidebar') }}">Shop List Right Sidebar</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <ul>--}}
{{--                                                <li class="mega-menu-title"><a href="#">Shop Single</a></li>--}}
{{--                                                <li><a href="{{ url('single-product') }}">Shop Single</a></li>--}}
{{--                                                <li><a href="{{ url('single-product-variable') }}">Shop Variable</a></li>--}}
{{--                                                <li><a href="{{ url('single-product-affiliate') }}">Shop Affiliate</a></li>--}}
{{--                                                <li><a href="{{ url('single-product-group') }}">Shop Group</a></li>--}}
{{--                                                <li><a href="{{ url('single-product-tabstyle-2') }}">Shop Tab 2</a></li>--}}
{{--                                                <li><a href="{{ url('single-product-tabstyle-3') }}">Shop Tab 3</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <ul>--}}
{{--                                                <li class="mega-menu-title"><a href="#">Shop Single</a></li>--}}
{{--                                                <li><a href="{{ url('single-product-slider') }}">Shop Slider</a></li>--}}
{{--                                                <li><a href="{{ url('single-product-gallery-left') }}">Shop Gallery Left</a></li>--}}
{{--                                                <li><a href="{{ url('single-product-gallery-right') }}">Shop Gallery Right</a></li>--}}
{{--                                                <li><a href="{{ url('single-product-sticky-left') }}">Shop Sticky Left</a></li>--}}
{{--                                                <li><a href="{{ url('single-product-sticky-right') }}">Shop Sticky Right</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </li>--}}
{{--                                        <li class="w-100">--}}
{{--                                            <ul class="banner-megamenu-wrapper d-flex">--}}
{{--                                                <li class="banner-wrapper mr-30px">--}}
{{--                                                    <a href="{{ url('single-product') }}"><img src="{{ asset('assets/images/menu-image/banner-menu2.jpg') }}" alt="" /></a>--}}
{{--                                                </li>--}}
{{--                                                <li class="banner-wrapper">--}}
{{--                                                    <a href="{{ url('single-product') }}"><img src="{{ asset('assets/images/menu-image/banner-menu3.jpg') }}" alt="" /></a>--}}
{{--                                                </li>--}}
{{--                                            </ul>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}
                                @php
                                    $categories = $activeGender == 'women' ? $womenCategories : $manCategories;
                                @endphp
                                @foreach($categories as $category)
                                    <li class="menu-dropdown">
                                        <a href="#">{{ $category->name }} <i class="ion-ios-arrow-down"></i></a>
                                        <ul class="mega-menu-wrap">
                                            <li>
                                                <ul>
                                                    @foreach($category->children as $child)
                                                        <li><a href="#">{{ $child->name }}</a></li>
                                                    @endforeach
                                                </ul>
                                            </li>
{{--                                            <li class="mega-menu-title"><a href="#">{{ $category->name }}</a></li>--}}

                                        </ul>
                                    </li>
                                @endforeach



{{--                                <li class="menu-dropdown">--}}
{{--                                    <a href="#">Categories <i class="ion-ios-arrow-down"></i></a>--}}
{{--                                    <ul class="mega-menu-wrap">--}}
{{--                                        @php--}}
{{--                                            $categories = $activeGender == 'women' ? $womenCategories : $manCategories;--}}
{{--                                        @endphp--}}
{{--                                        @foreach($categories as $category)--}}
{{--                                            <li>--}}
{{--                                                <ul>--}}
{{--                                                    <li class="mega-menu-title"><a href="#">{{ $category->name }}</a></li>--}}
{{--                                                    @foreach($category->children as $child)--}}
{{--                                                        <li><a href="#">{{ $child->name }}</a></li>--}}
{{--                                                    @endforeach--}}
{{--                                                </ul>--}}
{{--                                            </li>--}}
{{--                                        @endforeach--}}
{{--                                    </ul>--}}
{{--                                </li>--}}



{{--                                <li class="menu-dropdown">--}}
{{--                                    <a href="#">Pages <i class="ion-ios-arrow-down"></i></a>--}}
{{--                                    <ul class="main-sub-menu">--}}
{{--                                        <li><a href="{{ url('about') }}">About Page</a></li>--}}
{{--                                        <li><a href="{{ url('cart') }}">Cart Page</a></li>--}}
{{--                                        <li><a href="{{ url('checkout') }}">Checkout Page</a></li>--}}
{{--                                        <li><a href="{{ url('compare') }}">Compare Page</a></li>--}}
{{--                                        <li><a href="{{ url('login') }}">Login & Register Page</a></li>--}}
{{--                                        <li><a href="{{ url('my-account') }}">Account Page</a></li>--}}
{{--                                        <li><a href="{{ url('empty-cart') }}">Empty Cart Page</a></li>--}}
{{--                                        <li><a href="{{ url('404') }}">404 Page</a></li>--}}
{{--                                        <li><a href="{{ url('wishlist') }}">Wishlist Page</a></li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}
{{--                                <li class="menu-dropdown">--}}
{{--                                    <a href="#">Blog <i class="ion-ios-arrow-down"></i></a>--}}
{{--                                    <ul class="main-sub-menu">--}}
{{--                                        <li class="menu-dropdown position-static">--}}
{{--                                            <a href="#">Blog Grid <i class="ion-ios-arrow-right"></i></a>--}}
{{--                                            <ul class="main-sub-menu main-sub-menu-2">--}}
{{--                                                <li><a href="{{ url('blog-grid-left-sidebar') }}">Blog Grid Left Sidebar</a></li>--}}
{{--                                                <li><a href="{{ url('blog-grid-right-sidebar') }}">Blog Grid Right Sidebar</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </li>--}}
{{--                                        <li class="menu-dropdown position-static">--}}
{{--                                            <a href="#">Blog List <i class="ion-ios-arrow-right"></i></a>--}}
{{--                                            <ul class="main-sub-menu main-sub-menu-2">--}}
{{--                                                <li><a href="{{ url('blog-list-left-sidebar') }}">Blog List Left Sidebar</a></li>--}}
{{--                                                <li><a href="{{ url('blog-list-right-sidebar') }}">Blog List Right Sidebar</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </li>--}}
{{--                                        <li class="menu-dropdown position-static">--}}
{{--                                            <a href="#">Blog Single <i class="ion-ios-arrow-right"></i></a>--}}
{{--                                            <ul class="main-sub-menu main-sub-menu-2">--}}
{{--                                                <li><a href="{{ url('blog-single-left-sidebar') }}">Blog Single Left Sidebar</a></li>--}}
{{--                                                <li><a href="{{ url('blog-single-right-sidebar') }}">Blog Single Right Sidbar</a></li>--}}
{{--                                            </ul>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}
{{--                                <li class="menu-dropdown">--}}
{{--                                    <a href="#">Custom Menu <i class="ion-ios-arrow-down"></i></a>--}}
{{--                                    <ul class="mega-menu-wrap mega-menu-wrap-2">--}}
{{--                                        <li>--}}
{{--                                            <div class="custom-single-item">--}}
{{--                                                <h4><a href="{{ url('shop-4-column') }}">Women Is Clothes & Fashion</a></h4>--}}
{{--                                                <p>Shop Women Is Clothing And Accessories And Get Inspired By The Latest Fashion Trends.</p>--}}
{{--                                            </div>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <div class="custom-single-item">--}}
{{--                                                <h4><a href="{{ url('shop-4-column') }}">Simple Style</a></h4>--}}
{{--                                                <p>A New Flattering Style With All The Comfort Of Our Linen.</p>--}}
{{--                                            </div>--}}
{{--                                        </li>--}}
{{--                                        <li>--}}
{{--                                            <div class="custom-single-item">--}}
{{--                                                <h4><a href="{{ url('shop-4-column') }}">Easy Style</a></h4>--}}
{{--                                                <p>Endless Styling Possibilities In A Collection Full Of Versatile Pieces.</p>--}}
{{--                                            </div>--}}
{{--                                        </li>--}}
{{--                                    </ul>--}}
{{--                                </li>--}}
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            <!-- row -->
        </div>
        <!-- container -->
    </div>
    <!-- header menu -->
</header>
