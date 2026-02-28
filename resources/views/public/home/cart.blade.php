@extends('public.layouts.main')
@section('title')
    Cart
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
                            <li>Cart</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->
    <!-- cart area start -->
    <div class="cart-main-area mtb-50px">
        <div class="container">
            <h3 class="cart-page-title">Your cart items</h3>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <form action="{{ route('cart.update') }}" method="POST">
                        @csrf
                        <div class="table-content table-responsive cart-table-content">
                            <table>
                                <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(empty($cartItems))
                                    <tr>
                                        <td colspan="6" class="text-center">Your cart is empty.</td>
                                    </tr>
                                @else
                                    @foreach($cartItems as $item)
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
                                            <td class="product-price-cart"><span class="amount">${{ number_format($item['unit_price'], 2) }}</span></td>
                                            <td class="product-quantity">
                                                <div class="cart-plus-minus">
                                                    <input class="cart-plus-minus-box" type="text" name="quantities[{{ $item['product']->id }}]" value="{{ $item['quantity'] }}" />
                                                </div>
                                            </td>
                                            <td class="product-subtotal">${{ number_format($item['subtotal'], 2) }}</td>
                                            <td class="product-remove">
                                                @php $pId = $item['product']->id; @endphp
                                                <a href="#" onclick="event.preventDefault(); document.getElementById('remove-cart-{{ $pId }}').submit();">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4" class="text-end border-0"></td>
                                        <td class="product-subtotal text-center"><strong>Total:</strong></td>
                                        <td class="product-subtotal"><strong>${{ number_format($cartSubtotal, 2) }}</strong></td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="cart-shiping-update-wrapper">
                                    <div class="cart-shiping-update">
                                        <a href="{{ route('home') }}">Continue Shopping</a>
                                    </div>
                                    <div class="cart-clear">
                                        <button type="submit">Update Shopping Cart</button>
                                        <a href="{{ route('checkout.index') }}">Checkout</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Hidden forms for Remove and Clear actions (outside main form) --}}
                    @if(!empty($cartItems))
                        @foreach($cartItems as $item)
                            <form id="remove-cart-{{ $item['product']->id }}" action="{{ route('cart.remove', $item['product']->id) }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- cart area end -->
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
