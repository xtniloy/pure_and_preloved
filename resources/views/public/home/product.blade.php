@extends('public.layouts.main')
@section('title')
    {{ $product->meta_title ?? $product->name }}
@endsection
@section('meta')
    <meta name="description" content="{{ $product->meta_description }}">
    <meta name="keywords" content="{{ $product->meta_keywords }}">
@endsection
@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="#">{{ ucfirst($gender) }}</a></li>
                            <li><a href="#">{{ $category->name }}</a></li>
                            <li>{{ $product->name }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->
    <!-- Shop details Area start -->
    <section class="product-details-area mtb-60px ">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="product-details-img product-details-tab">
                        <div class="zoompro-wrap zoompro-2">
                            @if($product->assets && $product->assets->count() > 0)
                                @foreach($product->assets as $key => $asset)
                                    <div class="zoompro-border zoompro-span">
                                        <img class="zoompro" src="{{ $asset->public_url }}" data-zoom-image="{{ $asset->public_url }}" alt="{{ $product->name }}" />
                                    </div>
                                @endforeach
                            @else
                                <div class="zoompro-border zoompro-span">
                                    <img class="zoompro" src="{{ asset('assets/images/product-image/8.jpg') }}" alt="Placeholder" />
                                </div>
                            @endif
                        </div>
                        <div id="gallery" class="product-dec-slider-2">
                            @if($product->assets && $product->assets->count() > 0)
                                @foreach($product->assets as $asset)
                                    <div class="single-slide-item">
                                        <img class="img-responsive" data-image="{{ $asset->public_url }}" data-zoom-image="{{ $asset->public_url }}" src="{{ $asset->public_url}}" alt="{{ $product->name }}" />
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12">
                    <div class="product-details-content">
                        <p class="reference">SKU: <span> {{ $product->sku }}</span></p>
                        <h2>{{ $product->name }}</h2>
                        <div class="pricing-meta">
                            <ul>
                                @if($product->sale_price)
                                    <li class="old-price">${{ $product->price }}</li>
                                    <li class="cuttent-price">${{ $product->sale_price }}</li>
                                    <li class="discount-flag">Sale</li>
                                @else
                                    <li class="cuttent-price">${{ $product->price }}</li>
                                @endif
                            </ul>
                        </div>
                        <div class="pro-details-list">
                            <p>{{ Str::limit($product->description, 150) }}</p>
                        </div>

                        <div class="pro-details-quality mt-0px">
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="cart-plus-minus">
                                    <input class="cart-plus-minus-box" type="number" name="quantity" value="1" min="1" />
                                </div>
                                <div class="pro-details-cart btn-hover mb-2">
                                    <button type="submit">Add To Cart</button>
                                </div>
                            </form>
                            <form action="{{ route('checkout.index') }}" method="GET">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <div class="pro-details-cart btn-hover">
                                    <button type="submit">Order Now</button>
                                </div>
                            </form>
                        </div>
                        <div class="pro-details-wish-com">
                            <div class="pro-details-wishlist">
                                <form action="{{ route('wishlist.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-link p-0">
                                        <i class="ion-android-favorite-outline"></i>Add to wishlist
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="pro-details-social-info">
                            <span>Share</span>
                            <div class="social-info">
                                <ul>
                                    <li>
                                        <a href="#"><i class="ion-social-facebook"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="ion-social-twitter"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="ion-social-google"></i></a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="ion-social-instagram"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="pro-details-policy">
                            <ul>
                                <li><img src="{{ asset('assets/images/icons/policy.png') }}" alt="" /><span>Security Policy (Edit With Customer Reassurance Module)</span></li>
                                <li><img src="{{ asset('assets/images/icons/policy-2.png') }}" alt="" /><span>Delivery Policy (Edit With Customer Reassurance Module)</span></li>
                                <li><img src="{{ asset('assets/images/icons/policy-3.png') }}" alt="" /><span>Return Policy (Edit With Customer Reassurance Module)</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop details Area End -->
    <!-- product details description area start -->
    <div class="description-review-area mb-60px">
        <div class="container">
            <div class="description-review-wrapper">
                <div class="description-review-topbar nav">
                    <a data-bs-toggle="tab" href="#des-details1">Description</a>
                    <a class="active" data-bs-toggle="tab" href="#des-details2">Product Details</a>
                </div>
                <div class="tab-content description-review-bottom">
                    <div id="des-details2" class="tab-pane active">
                        <div class="product-anotherinfo-wrapper">
                            <ul>
                                <li><span>Weight</span> {{ $product->weight ?? 'N/A' }}</li>
                                <li><span>Dimensions</span> N/A</li> <!-- Placeholder, add if needed -->
                                <li><span>Materials</span> {{ $product->material ?? 'N/A' }}</li>
                                <li><span>Carat</span> {{ $product->carat ?? 'N/A' }}</li>
                                <li><span>Condition</span> {{ $product->condition ?? 'N/A' }}</li>
                            </ul>
                        </div>
                    </div>
                    <div id="des-details1" class="tab-pane">
                        <div class="product-description-wrapper">
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- product details description area end -->

    <!-- Arrivals Area Start -->
    <div class="arrival-area single-product-nav mb-20px">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h2 class="section-heading">Other Products In The Same Category:</h2>
                    </div>
                </div>
            </div>
            <!-- Arrivel slider start -->
            <div class="arrival-slider-wrapper slider-nav-style-1">
                @foreach($relatedProducts as $related)
                <div class="slider-single-item">
                    <!-- Single Item -->
                    <article class="list-product text-center">
                        <div class="product-inner">
                            <div class="img-block">
                                <a href="{{ route('product.show', ['gender' => $gender, 'category' => $category->slug, 'product' => $related->slug]) }}" class="thumbnail">
                                    @if($related->main_image)
                                        <img class="first-img" src="{{ $related->main_image->public_url }}" alt="{{ $related->name }}" />
                                        <img class="second-img" src="{{ $related->main_image->public_url }}" alt="{{ $related->name }}" />
                                    @else
                                        <img class="first-img" src="{{ asset('assets/images/product-image/4.jpg') }}" alt="Placeholder" />
                                    @endif
                                </a>
                                <div class="add-to-link">
                                    <ul>
                                        <li>
                                            <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal" data-product-id="{{ $related->id }}">
                                                <i class="lnr lnr-magnifier"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ route('wishlist.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $related->id }}">
                                                <button type="submit" class="btn btn-link p-0" title="Add to Wishlist">
                                                    <i class="lnr lnr-heart"></i>
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <a href="compare.html" title="Add to Compare"><i class="lnr lnr-sync"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <ul class="product-flag">
                                <li class="new">New</li>
                            </ul>
                            <div class="product-decs">
                                <a class="inner-link" href="#"><span>{{ $category->name }}</span></a>
                                <h2><a href="{{ route('product.show', ['gender' => $gender, 'category' => $category->slug, 'product' => $related->slug]) }}" class="product-link">{{ $related->name }}</a></h2>
{{--                                <div class="rating-product">--}}
{{--                                    <i class="ion-android-star"></i>--}}
{{--                                    <i class="ion-android-star"></i>--}}
{{--                                    <i class="ion-android-star"></i>--}}
{{--                                    <i class="ion-android-star"></i>--}}
{{--                                    <i class="ion-android-star"></i>--}}
{{--                                </div>--}}
                                <div class="pricing-meta">
                                    <ul>
                                        @if($related->sale_price)
                                            <li class="old-price">${{ $related->price }}</li>
                                            <li class="current-price">${{ $related->sale_price }}</li>
                                            <li class="discount-flag">Sale</li>
                                        @else
                                            <li class="current-price">${{ $related->price }}</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <div class="cart-btn">
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $related->id }}">
                                    <input type="hidden" name="quantity" value="1" />
                                    <button type="submit" class="add-to-curt" title="Add to cart">Add to cart</button>
                                </form>
                            </div>
                        </div>
                    </article>
                    <!-- Single Item -->
                </div>
                @endforeach
            </div>
            <!-- Arrivel slider end -->
        </div>
    </div>
    <!-- Arrivals Area End -->
@endsection
