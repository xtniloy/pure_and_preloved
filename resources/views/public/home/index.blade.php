@extends('public.layouts.main')
@section('title')
    Dashboard
@endsection
@section('content')
    <!-- Slider Start -->
    <div class="slider-area">
        <div class="hero-slider-wrapper">
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="assets/images/slider-image/sample-1.webp">
                <div class="container align-self-center">
                    <div class="slider-content-1 slider-animated-1 text-left pl-60px">
                        <h1 class="animated color-black">
                            Women <br />
                            Beautiful Jewellery
                        </h1>
                        <p class="animated color-gray">Pure and Preloved.</p>
                        <a href="shop-4-column.html" class="shop-btn animated">SHOP NOW</a>
                    </div>
                </div>
            </div>
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="assets/images/slider-image/sample-1.webp">
                <div class="container align-self-center">
                    <div class="slider-content-1 slider-animated-2 text-left pl-60px">
                        <h1 class="animated color-black">
                            Men's <br />
                            Beautiful Jewellery
                        </h1>
                        <p class="animated color-gray">Pure and Preloved.</p>
                        <a href="shop-4-column.html" class="shop-btn animated">SHOP NOW</a>
                    </div>
                </div>
            </div>
            <!-- Single Slider  -->
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="assets/images/slider-image/sample-1.webp">
                <div class="container align-self-center">
                    <div class="slider-content-1 slider-animated-3 text-left pl-60px">
                        <h1 class="animated color-black">
                            Women <br />
                            Beautiful Jewellery
                        </h1>
                        <p class="animated color-gray">Pure and Preloved.</p>
                        <a href="shop-4-column.html" class="shop-btn animated">SHOP NOW</a>
                    </div>
                </div>
            </div>
            <!-- Single Slider  -->
        </div>
    </div>
    <!-- Slider End -->

    <!-- Static Area Start -->
    <div class="static-area mtb-50px">
        <div class="container">
            <div class="static-area-wrap">
                <div class="row">
                    <!-- Static Single Item Start -->
                    <div class="col-lg-3 col-xs-12 col-md-6 col-sm-6 mb-md-30px mb-lm-30px">
                        <div class="single-static">
                            <img src="{{ asset('assets/images/icons/static-icons-1.png') }}" alt="" class="img-responsive" />
                            <div class="single-static-meta">
                                <h4>Free Shipping</h4>
                                <p>On all orders over $75.00</p>
                            </div>
                        </div>
                    </div>
                    <!-- Static Single Item End -->
                    <!-- Static Single Item Start -->
                    <div class="col-lg-3 col-xs-12 col-md-6 col-sm-6 mb-md-30px mb-lm-30px">
                        <div class="single-static">
                            <img src="{{ asset('assets/images/icons/static-icons-2.png') }}" alt="" class="img-responsive" />
                            <div class="single-static-meta">
                                <h4>Free Returns</h4>
                                <p>Returns are free within 9 days</p>
                            </div>
                        </div>
                    </div>
                    <!-- Static Single Item End -->
                    <!-- Static Single Item Start -->
                    <div class="col-lg-3 col-xs-12 col-md-6 col-sm-6 mb-sm-30px">
                        <div class="single-static">
                            <img src="{{ asset('assets/images/icons/static-icons-3.png') }}" alt="" class="img-responsive" />
                            <div class="single-static-meta">
                                <h4>100% Payment Secure</h4>
                                <p>Your payment are safe with us.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Static Single Item End -->
                    <!-- Static Single Item Start -->
                    <div class="col-lg-3 col-xs-12 col-md-6 col-sm-6 ">
                        <div class="single-static">
                            <img src="{{ asset('assets/images/icons/static-icons-4.png') }}" alt="" class="img-responsive" />
                            <div class="single-static-meta">
                                <h4>Support 24/7</h4>
                                <p>Contact us 24 hours a day</p>
                            </div>
                        </div>
                    </div>
                    <!-- Static Single Item End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Static Area End -->

    <div class="module-featured-area mtb-50px">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img src="assets/images/product-image/summersale-june.jpg" alt="">
                        <div class="module-feature-info">
                            <h5>TEMPEST NATURE</h5>
                            <span>INSPIRED JEWELLERY</span>
                            <button class="show-btn">Shop Now</button>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6">
                    <a href="#" class="module-featured-item">
                        <img src="assets/images/product-image/summersale-june.jpg" alt="">
                        <div class="module-feature-info">
                            <h5>TEMPEST NATURE</h5>
                            <span>INSPIRED JEWELLERY</span>
                            <button class="show-btn">Shop Now</button>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>


    <div class="feature-area mtb-50px">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="px-3">Featured Products</h2>
            </div>
            <div class="feature-product-wrap d-flex">
                <a href="#" class="feature-product-item d-block">
                    <div class="feature-product-photo">
                        <img  src="assets/images/product-image/4.jpg" alt="" />
                    </div>
                    <div class="feature-product-info">
                        <h3>BUY 2, GET 30% OFF </h3>
                        <span>ON IMERAKI</span>
                    </div>
                </a>
                <a href="#" class="feature-product-item d-block">
                    <div class="feature-product-photo">
                        <img  src="assets/images/product-image/5.jpg" alt="" />
                    </div>
                    <div class="feature-product-info">
                        <h3>BUY 2, GET 30% OFF </h3>
                        <span>ON IMERAKI</span>
                    </div>
                </a>
                <a href="#" class="feature-product-item d-block">
                    <div class="feature-product-photo">
                        <img  src="assets/images/product-image/6.jpg" alt="" />
                    </div>
                    <div class="feature-product-info">
                        <h3>BUY 2, GET 30% OFF </h3>
                        <span>ON IMERAKI</span>
                    </div>
                </a>
                <a href="#" class="feature-product-item d-block">
                    <div class="feature-product-photo">
                        <img  src="assets/images/product-image/7.jpg" alt="" />
                    </div>
                    <div class="feature-product-info">
                        <h3>BUY 2, GET 30% OFF </h3>
                        <span>ON IMERAKI</span>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Feature Area Start -->
    <div class="feature-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title text-center">
                                <h2 class="px-3"> <strong>WE ALSO RECOMMEND</strong>  FOR YOU</h2>
                            </div>
                        </div>
                    </div>
                    <div class="feature-slider-wrapper slider-nav-style-1 d-none d-lg-block">
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center mb-30px">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/4.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/5.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                        <li class="new">-12%</li>
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>Pure and Preloved</span></a>
                                        <h2><a href="single-product.html" class="product-link">CANDY Sky Silver CZ Flower Earrings</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center mb-30px">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/8.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/9.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>Pure and Preloved</span></a>
                                        <h2><a href="single-product.html" class="product-link">Silver Hearts Paw Print Engraving T-Bar Necklace</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center mb-30px">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/12.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/13.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>Pure and Preloved</span></a>
                                        <h2><a href="single-product.html" class="product-link">Connect Silver Drop Stone Charm Pendant</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center mb-30px">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/16.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/17.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>Pure and Preloved</span></a>
                                        <h2><a href="single-product.html" class="product-link">Connect Gold Blue Stone Charm Pendant</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#" class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center mb-30px">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/20.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/21.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>Pure and Preloved</span></a>
                                        <h2><a href="single-product.html" class="product-link">Gold and Silver Perl Stone Charm Pendant</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="cart-btn">
                                        <a href="#"  class="add-to-curt" title="Add to cart">Add to cart</a>
                                    </div>
                                </div>
                            </article>
                            <!-- Single Item -->
                        </div>
                    </div>

                    <!-- only mobile view -->

                    <div class="product-show-mobile d-block d-lg-none">
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center mb-30px">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/4.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/5.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                        <li class="new">-12%</li>
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Edifier H840 Audiophile</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </article>
                            <!-- Single Item -->
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center mb-30px">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/8.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/9.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">SoundBox Pro Portable</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="old-price">$23.90</li>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </article>
                            <!-- Single Item -->
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center mb-30px">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/12.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/13.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                        <h2><a href="single-product.html" class="product-link">Naham WiFi HD 1080P</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </article>
                            <!-- Single Item -->
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center mb-30px">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/16.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/17.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>GRAPHIC CORNER</span></a>
                                        <h2><a href="single-product.html" class="product-link">Polk Audio T30 Speaker</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </article>
                            <!-- Single Item -->
                        </div>
                        <div class="slider-single-item">
                            <!-- Single Item -->
                            <article class="list-product text-center mb-30px">
                                <div class="product-inner">
                                    <div class="img-block">
                                        <a href="single-product.html" class="thumbnail">
                                            <img class="first-img" src="assets/images/product-image/20.jpg" alt="" />
                                            <img class="second-img" src="assets/images/product-image/21.jpg" alt="" />
                                        </a>
                                        <div class="add-to-link">
                                            <ul>
                                                <li>
                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                        <i class="lnr lnr-magnifier"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="wishlist.html" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                </li>
                                                <li>
                                                    <a href="compare.html" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <ul class="product-flag">
                                    </ul>
                                    <div class="product-decs">
                                        <a class="inner-link" href="shop-4-column.html"><span>STUDIO DESIGN</span></a>
                                        <h2><a href="single-product.html" class="product-link">Numkuda USB 2.0 Gamepad</a></h2>
                                        <div class="pricing-meta">
                                            <ul>
                                                <li class="current-price">$21.51</li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </article>
                            <!-- Single Item -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner Area End -->


    <div class="full-product-banner" style="background:url(assets/images/banner-image/homepage-shopallbrands.jpg);">
        <div class="container">
            <div class="product-content text-center">
                <h2>SHOP ALL BRANDS</h2>
                <p>A stunning range of women's jewellery</p>
                <a href="#" class="show-btn d-inline-block bg-white text-black fw-bold">Shop Now</a>
            </div>
        </div>
    </div>


    <div class="module-featured-area mtb-50px">
        <div class="container">
            <div class="common-title text-center mb-4">
                <h2>PERSONALISED  <strong>JEWELLERY</strong> </h2>
                <p>Discover beautiful personalised jewellery with <strong>FREE</strong>  engraving of your choice!</p>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img src="assets/images/product-image/EngravingAll.jpg" alt="">
                        <div class="module-feature-info">
                            <h3 class="mb-2">YOUR PETS <strong>NOSE PRINT</strong></h3>
                            <button class="show-btn">Shop Now</button>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6">
                    <a href="#" class="module-featured-item">
                        <img src="assets/images/product-image/summersale-june.jpg" alt="">
                        <div class="module-feature-info">
                            <h3 class="mb-2">YOUR PETS <strong>NOSE PRINT</strong></h3>
                            <button class="show-btn">Shop Now</button>
                        </div>
                    </a>
                </div>
            </div>

        </div>
        <div class="container product-container-wrap ">
            <div class="row mt-4">
                <div class="col-lg-3 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img src="assets/images/product-image/homeengraving.jpg" alt="">
                        <div class="module-feature-info">
                            <h6 class="mb-2">YOUR PETS <strong>NOSE PRINT</strong></h6>
                            <span>WITH YOUR MESSAGE</span>
                            <button class="show-btn">Shop</button>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img src="assets/images/product-image/homeengraving.jpg" alt="">
                        <div class="module-feature-info">
                            <h6 class="mb-2">YOUR PETS <strong>NOSE PRINT</strong></h6>
                            <span>WITH YOUR MESSAGE</span>
                            <button class="show-btn">Shop</button>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img src="assets/images/product-image/homeengraving.jpg" alt="">
                        <div class="module-feature-info">
                            <h6 class="mb-2">YOUR PETS <strong>NOSE PRINT</strong></h6>
                            <span>WITH YOUR MESSAGE</span>
                            <button class="show-btn">Shop</button>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="#" class="module-featured-item">
                        <img src="assets/images/product-image/homeengraving.jpg" alt="">
                        <div class="module-feature-info">
                            <h6 class="mb-2">YOUR PETS <strong>NOSE PRINT</strong></h6>
                            <span>WITH YOUR MESSAGE</span>
                            <button class="show-btn">Shop</button>
                        </div>
                    </a>
                </div>

            </div>

        </div>
    </div>

    <div class="full-product-banner NOMINATION nominatin-banner " style="background:url(assets/images/banner-image/homepage-nomo24.jpg);">
        <div class="container">
            <div class="product-content text-center">
                <p class="text-black pb-2">NEW IN!</p>
                <h2 class="text-black">ENGRAVABLE NOMINATION JEWELLERY</h2>
                <a href="#" class="show-btn d-inline-block text-white fw-bold">Shop Now</a>
            </div>
        </div>
    </div>


    <div class="feature-area mtb-50px">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="px-3">SHOP BY TYPE</h2>
            </div>

            <div class="feature-product-wrap d-flex">
                <a href="#" class="feature-product-item d-block">
                    <div class="feature-product-photo">
                        <img src="assets/images/product-image/4.jpg" alt="">
                    </div>
                    <div class="feature-product-info">
                        <h3>NECKLACES </h3>
                    </div>
                </a>
                <a href="#" class="feature-product-item d-block">
                    <div class="feature-product-photo">
                        <img src="assets/images/product-image/5.jpg" alt="">
                    </div>
                    <div class="feature-product-info">
                        <h3>NECKLACES </h3>
                    </div>
                </a>
                <a href="#" class="feature-product-item d-block">
                    <div class="feature-product-photo">
                        <img src="assets/images/product-image/6.jpg" alt="">
                    </div>
                    <div class="feature-product-info">
                        <h3>NECKLACES </h3>
                    </div>
                </a>
                <a href="#" class="feature-product-item d-block">
                    <div class="feature-product-photo">
                        <img src="assets/images/product-image/7.jpg" alt="">
                    </div>
                    <div class="feature-product-info">
                        <h3>RING </h3>
                    </div>
                </a>

                <a href="#" class="feature-product-item d-block">
                    <div class="feature-product-photo">
                        <img src="assets/images/product-image/4.jpg" alt="">
                    </div>
                    <div class="feature-product-info">
                        <h3>NECKLACES </h3>
                    </div>
                </a>

            </div>

        </div>


    </div>


    <div class="module-featured-area mtb-50px">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img class="rounded-1" src="assets/images/product-image/woman.jpg" alt="">
                        <div class="module-feature-info">
                            <h3 class="text-white text-center pb-4"> <strong>WOMEN'S </strong> <br>  JEWELLERY</h5>
                                <button class="show-btn">Shop Now</button>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img class="rounded-1" src="assets/images/product-image/christmas24-childrens.jpg" alt="">
                        <div class="module-feature-info">
                            <h3 class="text-black text-center pb-4"> <strong>CHILDREN'S </strong> <br>  JEWELLERY</h5>
                                <button class="show-btn">Shop Now</button>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4">
                    <a href="#" class="module-featured-item">
                        <img class="rounded-1" src="assets/images/product-image/man.jpg" alt="">
                        <div class="module-feature-info">
                            <h3 class="text-white text-center pb-4"> <strong>MEN'S </strong> <br>  JEWELLERY</h5>
                                <button class="show-btn">Shop Now</button>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>



    <div class="full-product-banner NOMINATION nominatin-banner "
         style="background:url(assets/images/banner-image/homepage-newdesign24.jpg);">
        <div class="container">
            <div class="product-content text-center">
                <strong class="d-inline-flex py-1 px-3 fs-2 mb-2 bg-black text-white w"> 20% OFF</strong>
                <h2 class="text-black fw-bold">9CT GOLD JEWELLERY</h2>
                <p class="text-black pb-2">ETERNALLY BEAUTIFUL JEWELLERY</p>
                <a href="#" class="show-btn d-inline-block text-white fw-bold">Shop Now</a>
            </div>
        </div>
    </div>


    <div class="feature-area mtb-50px">
        <div class="feature-product-wrap container d-flex">
            <a href="#" class="feature-product-item d-block">
                <div class="feature-product-photo">
                    <img src="assets/images/product-image/4.jpg" alt="">
                </div>
                <div class="feature-product-info">
                    <h3>NECKLACES </h3>
                </div>
            </a>
            <a href="#" class="feature-product-item d-block">
                <div class="feature-product-photo">
                    <img src="assets/images/product-image/5.jpg" alt="">
                </div>
                <div class="feature-product-info">
                    <h3>NECKLACES </h3>
                </div>
            </a>
            <a href="#" class="feature-product-item d-block">
                <div class="feature-product-photo">
                    <img src="assets/images/product-image/6.jpg" alt="">
                </div>
                <div class="feature-product-info">
                    <h3>NECKLACES </h3>
                </div>
            </a>
            <a href="#" class="feature-product-item d-block">
                <div class="feature-product-photo">
                    <img src="assets/images/product-image/7.jpg" alt="">
                </div>
                <div class="feature-product-info">
                    <h3>RING </h3>
                </div>
            </a>

            <a href="#" class="feature-product-item d-block">
                <div class="feature-product-photo">
                    <img src="assets/images/product-image/4.jpg" alt="">
                </div>
                <div class="feature-product-info">
                    <h3>NECKLACES </h3>
                </div>
            </a>

        </div>
    </div>



    <div class="module-featured-area shop-gift-area">
        <div class="container">
            <div class="common-title text-center mb-4">
                <h2>  <strong>SHOP</strong>  THE PERFECT GIFT </h2>
                <p>Give a gift they'll cherish forever, we have something to suit every style & any occasion.</p>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img class="rounded-1" src="assets/images/product-image/newdesign24.jpg" alt="">
                        <div class="module-feature-info">
                            <h4 class="text-white text-center pb-3"> <strong>BIRTHDAY </strong> GIFT</h4>
                            <button class="show-btn fw-bold bg-white text-black">Shop Now</button>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img class="rounded-1" src="assets/images/product-image/christmas24-childrens.jpg" alt="">
                        <div class="module-feature-info">
                            <h4 class="text-black text-center pb-3"> <strong>BIRTHSTONE </strong>  JEWELLERY</h4>
                            <button class="show-btn fw-bold">Shop Now</button>
                        </div>
                    </a>
                </div>

                <div class="col-lg-4">
                    <a href="#" class="module-featured-item">
                        <img class="rounded-1" src="assets/images/product-image/newdesign25.jpg" alt="">
                        <div class="module-feature-info">
                            <h4 class="text-white text-center pb-3"> <strong>OFFERS</strong> | DON'T MISS OUT</h4>
                            <button class="show-btn fw-bold bg-white text-black">Shop Now</button>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>


    <div class="feature-area mtb-50px">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="px-3"> <strong>INSPIRATION!</strong> CLICK TO SHOP</h2>
            </div>
            <div class="row">
                <div class="col-lg-3 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img class="rounded-1" src="assets/images/product-image/social-3.jpg" alt="">
                    </a>
                </div>
                <div class="col-lg-3 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img class="rounded-1" src="assets/images/product-image/christmas24-childrens.jpg" alt="">
                    </a>
                </div>

                <div class="col-lg-3 mb-3 mb-lg-0">
                    <a href="#" class="module-featured-item">
                        <img class="rounded-1" src="assets/images/product-image/man.jpg" alt="">
                    </a>
                </div>

                <div class="col-lg-3">
                    <a href="#" class="module-featured-item">
                        <img class="rounded-1" src="assets/images/product-image/social-5.jpg" alt="">
                    </a>
                </div>


            </div>
        </div>
    </div>

    <div class="feature-area jg-magazine-area bg-black">
        <div class="container">
            <div class="common-title text-center mb-4">
                <h2 class="text-white">  JG MAGAZINE  </h2>
            </div>
        </div>
        <div class="feature-product-wrap container d-flex">
            <a href="#" class="feature-product-item d-block">
                <div class="feature-product-photo">
                    <img src="assets/images/product-image/4.jpg" alt="">
                </div>
                <div class="feature-product-info">
                    <h3>NECKLACES </h3>
                </div>
            </a>
            <a href="#" class="feature-product-item d-block">
                <div class="feature-product-photo">
                    <img src="assets/images/product-image/5.jpg" alt="">
                </div>
                <div class="feature-product-info">
                    <h3>NECKLACES </h3>
                </div>
            </a>
            <a href="#" class="feature-product-item d-block">
                <div class="feature-product-photo">
                    <img src="assets/images/product-image/6.jpg" alt="">
                </div>
                <div class="feature-product-info">
                    <h3>NECKLACES </h3>
                </div>
            </a>
            <a href="#" class="feature-product-item d-block">
                <div class="feature-product-photo">
                    <img src="assets/images/product-image/7.jpg" alt="">
                </div>
                <div class="feature-product-info">
                    <h3>RING </h3>
                </div>
            </a>

            <a href="#" class="feature-product-item d-block">
                <div class="feature-product-photo">
                    <img src="assets/images/product-image/4.jpg" alt="">
                </div>
                <div class="feature-product-info">
                    <h3>NECKLACES </h3>
                </div>
            </a>

        </div>
    </div>

    <div class="follow-us-area">
        <div class="container">
            <div class="section-title text-center">
                <h2 class="px-3"> <strong>FOLLOW</strong> US ON SOCIAL!</h2>
            </div>

            <div class="social-icon">
                <ul class="d-flex justify-content-center align-items-center">
                    <li class="facebook">
                        <a href="#"><i class="ion-social-facebook"></i></a>
                    </li>
                    <li class="twitter">
                        <a href="#"><i class="ion-social-twitter"></i></a>
                    </li>
                    <li class="google">
                        <a href="#"><i class="ion-social-google"></i></a>
                    </li>
                    <li class="youtube">
                        <a href="#"><i class="ion-social-youtube"></i></a>
                    </li>
                    <li class="instagram">
                        <a href="#"><i class="ion-social-instagram"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="greed-Jewellery-area">
        <div class="container">
            <div class="common-title text-uppercase text-center mb-4">
                <h2> Pure and Preloved Jewellery </h2>
            </div>

            <div class="row">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <p>At Pure and Preloved we have a passion for jewellery and we love bringing you the latest women's and men's jewellery from top UK jewellery brands and leading brands from around the world, such as Nomination, THOMAS SABO, Coeur De Lion, and Chlobo.</p>
                </div>
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <p>We can help you express your unique style and also switch it up depending on your mood. We have an amazing selection of jewellery from charms to wedding rings and a wide range of styles including unique gold and silver jewellery from our exclusive Pure and Preloved collections.</p>
                </div>


                <div class="col-lg-4">
                    <p>As engraving experts, we can personalise your favourite brands to create a truly memorable gift. We've engraved over 250,000 charms to create unique keepsakes! So why not browse online to find the perfect gift you've been searching for</p>
                </div>

            </div>

        </div>
    </div>

    <!-- News letter area -->
    <div class="news-letter-area bg-black">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-4 mb-md-30px mb-lm-30px">
                    <div class="title-newsletter">
                        <h2>Sign Up For Newsletters</h2>
                        <p class="des">Be the First to Know. Sign up for newsletter today !</p>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8">
                    <div id="mc_embed_signup" class="subscribe-form">
                        <form
                            id="mc-embedded-subscribe-form"
                            class="validate"
                            novalidate=""
                            target="_blank"
                            name="mc-embedded-subscribe-form"
                            method="post"
                            action="http://devitems.us11.list-manage.com/subscribe/post?u=6bbb9b6f5827bd842d9640c82&amp;id=05d85f18ef"
                        >
                            <div id="mc_embed_signup_scroll" class="mc-form">
                                <input class="email" type="email" required="" placeholder="Enter your email here.." name="EMAIL" value="" />
                                <div class="mc-news" aria-hidden="true">
                                    <input type="text" value="" tabindex="-1" name="b_6bbb9b6f5827bd842d9640c82_05d85f18ef" />
                                </div>
                                <div class="clear">
                                    <input id="mc-embedded-subscribe" class="button" type="submit" name="subscribe" value="Sign Up" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- News letter area  End -->
@endsection
