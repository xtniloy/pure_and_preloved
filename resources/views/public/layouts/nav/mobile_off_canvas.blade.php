<!-- OffCanvas Wishlist Start -->
<div id="offcanvas-wishlist" class="offcanvas offcanvas-wishlist">
    <div class="inner">
        <div class="head">
            <span class="title">Wishlist</span>
            <button class="offcanvas-close">×</button>
        </div>
        <div class="body customScroll">
            <ul class="minicart-product-list">
                <li>
                    <a href="{{ url('single-product') }}" class="image"><img src="{{ asset('assets/images/product-image/1.jpg') }}" alt="Cart product Image"></a>
                    <div class="content">
                        <a href="{{ url('single-product') }}" class="title">Walnut Cutting Board</a>
                        <span class="quantity-price">1 x <span class="amount">$100.00</span></span>
                        <a href="#" class="remove">×</a>
                    </div>
                </li>
                <li>
                    <a href="{{ url('single-product') }}" class="image"><img src="{{ asset('assets/images/product-image/2.jpg') }}" alt="Cart product Image"></a>
                    <div class="content">
                        <a href="{{ url('single-product') }}" class="title">Lucky Wooden Elephant</a>
                        <span class="quantity-price">1 x <span class="amount">$35.00</span></span>
                        <a href="#" class="remove">×</a>
                    </div>
                </li>
                <li>
                    <a href="{{ url('single-product') }}" class="image"><img src="{{ asset('assets/images/product-image/3.jpg') }}" alt="Cart product Image"></a>
                    <div class="content">
                        <a href="{{ url('single-product') }}" class="title">Fish Cut Out Set</a>
                        <span class="quantity-price">1 x <span class="amount">$9.00</span></span>
                        <a href="#" class="remove">×</a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="foot">
            <div class="buttons">
                <a href="{{ url('wishlist') }}" class="btn btn-dark btn-hover-primary mt-30px">view wishlist</a>
            </div>
        </div>
    </div>
</div>
<!-- OffCanvas Wishlist End -->

<!-- OffCanvas Cart Start -->
<div id="offcanvas-cart" class="offcanvas offcanvas-cart">
    <div class="inner">
        <div class="head">
            <span class="title">Cart</span>
            <button class="offcanvas-close">×</button>
        </div>
        <div class="body customScroll">
            <ul class="minicart-product-list">
                <li>
                    <a href="{{ url('single-product') }}" class="image"><img src="{{ asset('assets/images/product-image/1.jpg') }}" alt="Cart product Image"></a>
                    <div class="content">
                        <a href="{{ url('single-product') }}" class="title">Walnut Cutting Board</a>
                        <span class="quantity-price">1 x <span class="amount">$100.00</span></span>
                        <a href="#" class="remove">×</a>
                    </div>
                </li>
                <li>
                    <a href="{{ url('/') }}single-product.html" class="image"><img src="{{ asset('assets/images/product-image/2.jpg') }}" alt="Cart product Image"></a>
                    <div class="content">
                        <a href="{{ url('/') }}single-product.html" class="title">Lucky Wooden Elephant</a>
                        <span class="quantity-price">1 x <span class="amount">$35.00</span></span>
                        <a href="#" class="remove">×</a>
                    </div>
                </li>
                <li>
                    <a href="{{ url('/') }}single-product.html" class="image"><img src="{{ asset('assets/images/product-image/3.jpg') }}" alt="Cart product Image"></a>
                    <div class="content">
                        <a href="{{ url('/') }}single-product.html" class="title">Fish Cut Out Set</a>
                        <span class="quantity-price">1 x <span class="amount">$9.00</span></span>
                        <a href="#" class="remove">×</a>
                    </div>
                </li>
            </ul>
        </div>
        <div class="foot">
            <div class="sub-total">
                <strong>Subtotal :</strong>
                <span class="amount">$144.00</span>
            </div>
            <div class="buttons">
                <a href="{{ url('/') }}cart.html" class="btn btn-dark btn-hover-primary mb-30px">view cart</a>
                <a href="{{ url('/') }}checkout.html" class="btn btn-outline-dark current-btn">checkout</a>
            </div>
            <p class="minicart-message">Free Shipping on All Orders Over $100!</p>
        </div>
    </div>
</div>
<!-- OffCanvas Cart End -->

<!-- OffCanvas Search Start -->
<div id="offcanvas-mobile-menu" class="offcanvas offcanvas-mobile-menu">
    <div class="inner customScroll">
        <div class="head">
            <span class="title">&nbsp; Menu</span>
            <button class="offcanvas-close">×</button>
        </div>
        <!--        <div class="offcanvas-menu-search-form">-->
        <!--            <form action="#">-->
        <!--                <input type="text" placeholder="Search...">-->
        <!--                <button><i class="lnr lnr-magnifier"></i></button>-->
        <!--            </form>-->
        <!--        </div>-->

        <ul class="nav nav-tabs mobile-big-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link nav-link-mobile active" id="women-tab" data-bs-toggle="tab" data-bs-target="#women" type="button" role="tab" aria-controls="women" aria-selected="true">Women</button>
            </li>
            <li class="nav-item mx-auto" role="presentation">
                <button class="nav-link nav-link-mobile" id="men-tab" data-bs-toggle="tab" data-bs-target="#men" type="button" role="tab" aria-controls="men" aria-selected="false">Men</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="women" role="tabpanel" aria-labelledby="women-tab">
                <div class="offcanvas-menu">
                    <ul>
                        {{--                            <li><a href="#"><span class="menu-text">Women Home</span></a>--}}
                        {{--                                <ul class="sub-menu">--}}
                        {{--                                    <li><a href="{{ url('/') }}index.html"><span class="menu-text">Home 1</span></a></li>--}}
                        {{--                                    <li><a href="{{ url('/') }}index-2.html"><span class="menu-text">Home 2</span></a></li>--}}
                        {{--                                    <li> <a href="{{ url('/') }}index-3.html"><span class="menu-text">Home 3</span></a></li>--}}
                        {{--                                    <li><a href="{{ url('/') }}index-4.html"><span class="menu-text">Home 4</span></a></li>--}}
                        {{--                                </ul>--}}
                        {{--                            </li>--}}
                        {{--                            <li><a href="#"><span class="menu-text">Shop</span></a>--}}
                        {{--                                <ul class="sub-menu">--}}
                        {{--                                    <li>--}}
                        {{--                                        <a href="#"><span class="menu-text">Shop Grid</span></a>--}}
                        {{--                                        <ul class="sub-menu">--}}
                        {{--                                            <li><a href="{{ url('/') }}shop-3-column.html">Shop Grid 3 Column</a></li>--}}
                        {{--                                            <li><a href="{{ url('/') }}shop-4-column.html">Shop Grid 4 Column</a></li>--}}
                        {{--                                            <li><a href="{{ url('/') }}shop-left-sidebar.html">Shop Grid Left Sidebar</a></li>--}}
                        {{--                                            <li><a href="{{ url('/') }}shop-right-sidebar.html">Shop Grid Right Sidebar</a></li>--}}
                        {{--                                        </ul>--}}
                        {{--                                    </li>--}}
                        {{--                                    <li>--}}
                        {{--                                        <a href="#"><span class="menu-text">Shop List</span></a>--}}
                        {{--                                        <ul class="sub-menu">--}}
                        {{--                                            <li><a href="shop-list.html">Shop List</a></li>--}}
                        {{--                                            <li><a href="shop-list-left-sidebar.html">Shop List Left Sidebar</a></li>--}}
                        {{--                                            <li><a href="shop-list-right-sidebar.html">Shop List Right Sidebar</a></li>--}}
                        {{--                                        </ul>--}}
                        {{--                                    </li>--}}
                        {{--                                    <li>--}}
                        {{--                                        <a href="#"><span class="menu-text">Shop Single</span></a>--}}
                        {{--                                        <ul class="sub-menu">--}}
                        {{--                                            <li><a href="single-product.html">Shop Single</a></li>--}}
                        {{--                                            <li><a href="single-product-variable.html">Shop Variable</a></li>--}}
                        {{--                                            <li><a href="single-product-affiliate.html">Shop Affiliate</a></li>--}}
                        {{--                                            <li><a href="single-product-group.html">Shop Group</a></li>--}}
                        {{--                                            <li><a href="single-product-tabstyle-2.html">Shop Tab 2</a></li>--}}
                        {{--                                            <li><a href="single-product-tabstyle-3.html">Shop Tab 3</a></li>--}}
                        {{--                                        </ul>--}}
                        {{--                                    </li>--}}
                        {{--                                    <li>--}}
                        {{--                                        <a href="#"><span class="menu-text">Shop Single</span></a>--}}
                        {{--                                        <ul class="sub-menu">--}}
                        {{--                                            <li><a href="single-product-slider.html">Shop Slider</a></li>--}}
                        {{--                                            <li><a href="single-product-gallery-left.html">Shop Gallery Left</a></li>--}}
                        {{--                                            <li><a href="single-product-gallery-right.html">Shop Gallery Right</a></li>--}}
                        {{--                                            <li><a href="single-product-sticky-left.html">Shop Sticky Left</a></li>--}}
                        {{--                                            <li><a href="single-product-sticky-right.html">Shop Sticky Right</a></li>--}}
                        {{--                                        </ul>--}}
                        {{--                                    </li>--}}
                        {{--                                </ul>--}}
                        {{--                            </li>--}}
                        {{--                            <li><a href="#"><span class="menu-text">Pages</span></a>--}}
                        {{--                                <ul class="sub-menu">--}}
                        {{--                                    <li><a href="about.html">About Page</a></li>--}}
                        {{--                                    <li><a href="cart.html">Cart Page</a></li>--}}
                        {{--                                    <li><a href="checkout.html">Checkout Page</a></li>--}}
                        {{--                                    <li><a href="compare.html">Compare Page</a></li>--}}
                        {{--                                    <li><a href="login.html">Login & Register Page</a></li>--}}
                        {{--                                    <li><a href="my-account.html">Account Page</a></li>--}}

                        {{--                                    <li><a href="empty-cart.html">Empty Cart Page</a></li>--}}
                        {{--                                    <li><a href="404.html">404 Page</a></li>--}}
                        {{--                                    <li><a href="wishlist.html">Wishlist Page</a></li>--}}
                        {{--                                </ul>--}}
                        {{--                            </li>--}}
                        {{--                            <li><a href="#"><span class="menu-text">Blog</span></a>--}}
                        {{--                                <ul class="sub-menu">--}}
                        {{--                                    <li><a href="#"><span class="menu-text">Blog Grid</span></a>--}}
                        {{--                                        <ul class="sub-menu">--}}
                        {{--                                            <li><a href="blog-grid-left-sidebar.html">Blog Grid Left Sidebar</a></li>--}}
                        {{--                                            <li><a href="blog-grid-right-sidebar.html">Blog Grid Right Sidebar</a></li>--}}
                        {{--                                        </ul>--}}
                        {{--                                    </li>--}}
                        {{--                                    <li><a href="#"><span class="menu-text">Blog List</span></a>--}}
                        {{--                                        <ul class="sub-menu">--}}
                        {{--                                            <li><a href="blog-list-left-sidebar.html">Blog List Left Sidebar</a></li>--}}
                        {{--                                            <li><a href="blog-list-right-sidebar.html">Blog List Right Sidebar</a></li>--}}
                        {{--                                        </ul>--}}
                        {{--                                    </li>--}}
                        {{--                                    <li><a href="#"><span class="menu-text">Blog Single</span></a>--}}
                        {{--                                        <ul class="sub-menu">--}}
                        {{--                                            <li><a href="blog-single-left-sidebar.html">Blog Single Left Sidebar</a></li>--}}
                        {{--                                            <li><a href="blog-single-right-sidebar.html">Blog Single Right Sidbar</a></li>--}}
                        {{--                                        </ul>--}}
                        {{--                                    </li>--}}
                        {{--                                </ul>--}}
                        {{--                            </li>--}}
                        {{--                            <li><a href="#"><span class="menu-text">Custom Menu</span></a>--}}
                        {{--                                <ul class="sub-menu">--}}
                        {{--                                    <li><a href="shop-4-column.html">Women Is Clothes & Fashion</a></li>--}}
                        {{--                                    <li><a href="shop-4-column.html">Simple Style</a></li>--}}
                        {{--                                    <li><a href="shop-4-column.html">Easy Style</a></li>--}}
                        {{--                                </ul>--}}
                        {{--                            </li>--}}

{{--                        @php--}}
{{--                            $categories = $activeGender == 'women' ? $womenCategories : $manCategories;--}}
{{--                        @endphp--}}
                        @foreach($womenCategories as $category)
                            <li >
                                <a href="#"> <span class="menu-text">{{ $category->name }}</span></a>
                                <ul class="sub-menu">
                                    <li>
                                        <ul>
                                            @foreach($category->children as $child)
                                                <li><a href="{{ url('/') }}index.html"><span class="menu-text">{{ $child->name }}</span></a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    {{--                                            <li class="mega-menu-title"><a href="#">{{ $category->name }}</a></li>--}}

                                </ul>
                            </li>
                        @endforeach


                        <li><a href="contact.html">Contact Us</a></li>
                    </ul>
                </div>
            </div>
            <div class="tab-pane fade " id="men" role="tabpanel" aria-labelledby="men-tab">
                <div class="offcanvas-menu">
                    <ul>
                        @foreach($manCategories as $category)
                            <li >
                                <a href="#"> <span class="menu-text">{{ $category->name }}</span></a>
                                <ul class="sub-menu">
                                    <li>
                                        <ul>
                                            @foreach($category->children as $child)
                                                <li><a href="{{ url('/') }}index.html"><span class="menu-text">{{ $child->name }}</span></a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    {{--                                            <li class="mega-menu-title"><a href="#">{{ $category->name }}</a></li>--}}

                                </ul>
                            </li>
                        @endforeach
                        <li><a href="contact.html">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- OffCanvas Menu End -->
        <div class="offcanvas-social mt-30px">
            <ul>
                <li>
                    <a href="https://facebook.com/PureAndPreloved"><i class="ion-social-facebook"></i></a>
                </li>
                <li>
                    <a href="https://x.com/pureandpreloved"><i class="ion-social-twitter"></i></a>
                </li>
                <li>
                    <a href="https://threads.com/pureandpreloved"><i class="ion-social-google"></i></a>
                </li>
                <li>
                    <a href="https://pinterest.com/pureandpreloved"><i class="ion-social-pinterest"></i></a>
                </li>
                <li>
                    <a href="https://instagram.com/pureandpreloved"><i class="ion-social-instagram"></i></a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- OffCanvas Search End -->

<div class="offcanvas-overlay"></div>
