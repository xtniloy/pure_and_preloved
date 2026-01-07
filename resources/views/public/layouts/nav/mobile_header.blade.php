<div class="mobile-header d-lg-none sticky-nav bg-white ptb-20px">
    <div class="container">
        <div class="row align-items-center">

            <!-- Header Logo Start -->
            <div class="col">
                <div class="header-logo">
                    <a href="{{ url('/') }}"><img class="img-responsive" src="{{ asset('assets/images/logo/logo.jpg.png') }}" alt="logo.jpg" /></a>
                </div>
            </div>
            <!-- Header Logo End -->

            <!-- Header Tools Start -->
            <div class="col-auto">
                <div class="header-tools justify-content-end">
                    <div class="cart-info d-flex align-self-center">
                        <a href="#offcanvas-wishlist" class="heart offcanvas-toggle"><i class="lnr lnr-heart"></i><span>Wishlist</span></a>
                        <a href="#offcanvas-cart" class="bag offcanvas-toggle"><i class="lnr lnr-cart"></i><span>My Cart</span></a>
                    </div>
                    <div class="mobile-menu-toggle">
                        <a href="#offcanvas-mobile-menu" class="offcanvas-toggle">
                            <svg viewBox="0 0 800 600">
                                <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                                <path d="M300,320 L540,320" id="middle"></path>
                                <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Header Tools End -->

        </div>
    </div>
</div>

<!-- Search Category Start -->
<div class="mobile-search-area d-lg-none mb-15px">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="search-element media-body">
                    <form class="d-flex" action="#">
                        <input type="text" placeholder="Enter your search key ... " />
                        <button><i class="lnr lnr-magnifier"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Search Category End -->
