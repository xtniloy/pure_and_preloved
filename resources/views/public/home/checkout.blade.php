@extends('public.layouts.main')
@section('title')
    Checkout
@endsection
@section('meta')
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
                            <li>Checkout</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->

    <!-- checkout area start -->
    <div class="checkout-area mt-50px mb-40px">
        <div class="container">
            <form method="POST" action="{{ route('checkout.place') }}">
                @csrf
                <div class="row">

                    <!-- LEFT COLUMN: Billing Details -->
                    <div class="col-lg-7">
                        <div class="billing-info-wrap">
                            <h3>Billing Details</h3>
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="billing-info mb-20px">
                                        <label>First Name</label>
                                        <input type="text" name="billing_first_name" value="{{ old('billing_first_name') }}" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="billing-info mb-20px">
                                        <label>Last Name</label>
                                        <input type="text" name="billing_last_name" value="{{ old('billing_last_name') }}" />
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="billing-select mb-20px">
                                        <label>Country</label>
                                        <select name="billing_country">
                                            <option value="">Select a country</option>
                                            <option value="United Kingdom" @selected(old('billing_country') === 'United Kingdom')>United Kingdom</option>
                                            <option value="United States" @selected(old('billing_country') === 'United States')>United States</option>
                                            <option value="Canada" @selected(old('billing_country') === 'Canada')>Canada</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="billing-info mb-20px">
                                        <label>Street Address</label>
                                        <input class="billing-address" placeholder="House number and street name" type="text" name="billing_address" value="{{ old('billing_address', optional($user)->billing_address) }}" />
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="billing-info mb-20px">
                                        <label>Town / City</label>
                                        <input type="text" name="billing_city" value="{{ old('billing_city') }}" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="billing-info mb-20px">
                                        <label>Postcode / ZIP</label>
                                        <input type="text" name="billing_postcode" value="{{ old('billing_postcode') }}" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="billing-info mb-20px">
                                        <label>Phone</label>
                                        <input type="text" name="billing_phone" value="{{ old('billing_phone', optional($user)->phone) }}" />
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <div class="billing-info mb-20px">
                                        <label>Email Address</label>
                                        <input type="email" name="billing_email" value="{{ old('billing_email', optional($user)->email) }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="additional-info-wrap">
                                <h4>Additional information</h4>
                                <div class="additional-info">
                                    <label>Order notes</label>
                                    <textarea placeholder="Notes about your order, e.g. special notes for delivery." name="notes">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END LEFT COLUMN -->

                    <!-- RIGHT COLUMN: Order Summary -->
                    <div class="col-lg-5 mt-md-30px mt-lm-30px">
                        
                        <!-- CARD 1: Promo Code -->
                        <div class="discount-code-wrapper mb-30px">
                            <div class="title-wrap">
                                <h4 class="cart-bottom-title section-bg-gray">Apply Coupon</h4>
                            </div>
                            <div class="discount-code">
                                <p>Enter your coupon code if you have one.</p>
                                <div class="d-flex">
                                    <input type="text" name="coupon_code" placeholder="Coupon Code" style="height: 45px; border: 1px solid #ebebeb; padding: 0 15px; width: 100%;" />
                                    <button class="cart-btn-2 ms-2" type="button" style="white-space: nowrap; height: 45px; background-color: #242424; color: #fff; border: none; padding: 0 20px; font-weight: 700; text-transform: uppercase;">Apply</button>
                                </div>
                            </div>
                        </div>

                        <!-- CARD 2: Order Summary & Totals -->
                        <div class="your-order-area">
                            <h3>Your order</h3>
                            <div class="your-order-wrap gray-bg-4">
                                <div class="your-order-product-info">
                                    <div class="your-order-top">
                                        <ul>
                                            <li>Product</li>
                                            <li>Total</li>
                                        </ul>
                                    </div>
                                    <div class="your-order-middle">
                                        <ul>
                                            @foreach($items as $item)
                                                <li>
                                                    <span class="order-middle-left">
                                                        {{ $item['product']->name }} x {{ $item['quantity'] }}
                                                    </span>
                                                    <span class="order-price">
                                                        ${{ number_format($item['line_total'], 2) }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="your-order-bottom">
                                        <ul>
                                            <li class="your-order-shipping">Subtotal</li>
                                            <li>${{ number_format($subtotal, 2) }}</li>
                                        </ul>
                                    </div>
                                    
                                    <!-- Discount placeholder -->
                                    <div class="your-order-bottom">
                                        <ul>
                                            <li class="your-order-shipping">Discount</li>
                                            <li id="discount-display">-$0.00</li>
                                        </ul>
                                    </div>

                                    <!-- Shipping Options -->
                                    <div class="your-order-bottom total-shipping" style="padding: 20px 0; border-bottom: 1px solid #ebebeb;">
                                        <h5 style="font-size: 14px; font-weight: 700; margin-bottom: 10px;">Shipping Method</h5>
                                        <ul style="display: block;">
                                            @foreach($shippingMethods as $method)
                                                <li style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; width: 100%;">
                                                    <label style="margin: 0; cursor: pointer; display: flex; align-items: center;">
                                                        <input type="radio" name="shipping_method_id" value="{{ $method->id }}" 
                                                            {{ $loop->first ? 'checked' : '' }} 
                                                            onclick="updateGrandTotal({{ $method->charge }})"
                                                            style="width: auto; height: auto; margin-right: 10px;">
                                                        {{ $method->name }}
                                                    </label>
                                                    <span>{{ $method->charge > 0 ? '$' . number_format($method->charge, 2) : 'Free' }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="your-order-total">
                                        <ul>
                                            <li class="order-total">Grand Total</li>
                                            <li id="grand-total-display">${{ number_format($subtotal + ($shippingMethods->first()->charge ?? 0), 2) }}</li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="payment-method">
                                    <div class="payment-accordion element-mrg">
                                        <div class="panel-group" id="accordion">
                                            <div class="panel payment-accordion">
                                                <div class="panel-heading" id="method-three">
                                                    <h4 class="panel-title">
                                                        <a class="collapsed" data-bs-toggle="collapse" data-bs-parent="#accordion" href="#method3">
                                                            Cash on delivery
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="method3" class="panel-collapse collapse show">
                                                    <div class="panel-body">
                                                        <p>Pay with cash upon delivery.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="Place-order mt-25">
                            @foreach($items as $item)
                                <input type="hidden" name="items[{{ $item['product']->id }}]" value="{{ $item['quantity'] }}">
                            @endforeach
                            <a class="btn-hover" href="#" onclick="event.preventDefault(); this.closest('form').submit();">Place Order</a>
                        </div>
                    </div>
                    <!-- END RIGHT COLUMN -->

                </div>
            </form>
        </div>
    </div>
    <!-- checkout area end -->

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
    <!-- News letter area End -->
@endsection

@push('scripts')
 <script>
     function updateGrandTotal(shippingCharge) {
         var subtotal = parseFloat("{{ $subtotal }}");
         var grandTotal = subtotal + shippingCharge;
         $('#grand-total-display').text('$' + grandTotal.toFixed(2));
     }
 </script>
 @endpush
