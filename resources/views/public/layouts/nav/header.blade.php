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
                                <a href="#offcanvas-wishlist" class="heart offcanvas-toggle" data-count="5"><i class="lnr lnr-heart"></i><span></span></a>
                                <a href="#offcanvas-cart" class="heart offcanvas-toggle" data-count="4"><i class="lnr lnr-cart"></i><span></span></a>
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
                                @php
                                    $categories = $activeGender == 'women' ? $womenCategories : $manCategories;
                                @endphp
                                @foreach($categories as $category)
                                    <li class="menu-dropdown">
                                        <a href="#">{{ $category->name }} <i class="ion-ios-arrow-down"></i></a>
                                        <ul class="mega-menu-wrap">
                                            <li class="mega-menu-inner">
                                                <!-- LEFT COLUMN -->
                                                <div class="mega-menu-left">
                                                    <ul>
                                                        <li class="mega-menu-title">
                                                            <a href="#">{{ $category->name }}</a>
                                                        </li>

                                                        @foreach($category->children as $child)
                                                            <li>
                                                                <a href="#">{{ $child->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                <!-- RIGHT COLUMN -->
                                                <div class="mega-menu-right">
                                                    @php
                                                        $megaMenuImages = [];
                                                        if($category->asset && $category->asset->url){
                                                            $megaMenuImages[] = $category->asset->url;
                                                        }
                                                        foreach($category->children as $child){
                                                            if(count($megaMenuImages) >= 3) break;
                                                            if($child->asset && $child->asset->url){
                                                                $megaMenuImages[] = $child->asset->url;
                                                            }
                                                        }
                                                    @endphp

                                                    @foreach($megaMenuImages as $imgUrl)
                                                        <div class="mega-img">
                                                            <a href="#">
                                                                <img src="{{ $imgUrl }}">
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </li>
                                        </ul>
                                    </li>
                                @endforeach
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
