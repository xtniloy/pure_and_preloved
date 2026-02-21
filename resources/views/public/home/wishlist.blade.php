@extends('public.layouts.main')
@section('title')
    Wishlist
@endsection
@section('meta')
{{--    <meta name="description" content="{{ $product->meta_description }}">--}}
{{--    <meta name="keywords" content="{{ $product->meta_keywords }}">--}}
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
                            <li>Wishlist</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->
    <!-- Wishlist area start -->
    <div class="cart-main-area mtb-50px">
        <div class="container">
            <h3 class="cart-page-title">Your wishlist items</h3>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="table-content table-responsive cart-table-content">
                        <table>
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Add To Cart</th>
                                <th>Remove</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(empty($wishlistItems))
                                <tr>
                                    <td colspan="5" class="text-center">Your wishlist is empty.</td>
                                </tr>
                            @else
                                @foreach($wishlistItems as $item)
                                    <tr>
                                        <td class="product-thumbnail">
                                            @if($item['gender'] && $item['category_slug'])
                                                <a href="{{ route('product.show', ['gender' => $item['gender'], 'category' => $item['category_slug'], 'product' => $item['product']->slug]) }}">
                                                    <img class="img-responsive" src="{{ $item['image_url'] }}" alt="{{ $item['product']->name }}" />
                                                </a>
                                            @else
                                                <img class="img-responsive" src="{{ $item['image_url'] }}" alt="{{ $item['product']->name }}" />
                                            @endif
                                        </td>
                                        <td class="product-name">
                                            @if($item['gender'] && $item['category_slug'])
                                                <a href="{{ route('product.show', ['gender' => $item['gender'], 'category' => $item['category_slug'], 'product' => $item['product']->slug]) }}">{{ $item['product']->name }}</a>
                                            @else
                                                {{ $item['product']->name }}
                                            @endif
                                        </td>
                                        <td class="product-price-cart">
                                            <span class="amount">
                                                @if($item['product']->sale_price)
                                                    ${{ number_format($item['product']->sale_price, 2) }}
                                                @else
                                                    ${{ number_format($item['product']->price, 2) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td class="product-wishlist-cart">
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-link p-0">add to cart</button>
                                            </form>
                                        </td>
                                        <td class="product-wishlist-cart">
                                            <form action="{{ route('wishlist.remove', $item['product']->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-link p-0 text-danger">remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Wishlist area end -->
    <!-- News letter area -->
    <div class="news-letter-area">
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
