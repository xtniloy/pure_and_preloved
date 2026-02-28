@extends('public.layouts.main')

@section('title')
    Order Success
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
                            <li>Order Success</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->

    <!-- Order Success Area Start -->
    <div class="checkout-area mt-50px mb-40px">
        <div class="container text-center mb-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="order-success-icon mb-3">
                        <i class="fa fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="mb-2">Thank You for Your Order!</h2>
                    <p class="mb-4">Your order has been placed successfully. Order Reference: <strong>{{ $order->reference }}</strong></p>
                    <p>A confirmation email has been sent to <strong>{{ $order->billing_email }}</strong>.</p>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <!-- Order Summary -->
                <div class="col-lg-7">
                    <div class="your-order-area">
                        <h3>Order Summary</h3>
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
                                        @foreach($order->items as $item)
                                            <li>
                                                <span class="order-middle-left">
                                                    {{ $item->product_name }} x {{ $item->quantity }}
                                                </span>
                                                <span class="order-price">
                                                    ${{ number_format($item->line_total, 2) }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="your-order-bottom">
                                    <ul>
                                        <li class="your-order-shipping">Shipping</li>
                                        <li>Free shipping</li>
                                    </ul>
                                </div>
                                <div class="your-order-total">
                                    <ul>
                                        <li class="order-total">Total</li>
                                        <li>${{ number_format($order->total, 2) }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing & Shipping Info -->
                <div class="col-lg-5 mt-md-30px mt-lm-30px">
                    <div class="billing-info-wrap">
                        <h3>Order Details</h3>
                        <div class="gray-bg-4 p-4">
                            <div class="mb-3">
                                <h5>Billing Address</h5>
                                <p>
                                    {{ $order->billing_first_name }} {{ $order->billing_last_name }}<br>
                                    {{ $order->billing_address }}<br>
                                    {{ $order->billing_city }}, {{ $order->billing_postcode }}<br>
                                    {{ $order->billing_country }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <h5>Contact Information</h5>
                                <p>
                                    <strong>Phone:</strong> {{ $order->billing_phone }}<br>
                                    <strong>Email:</strong> {{ $order->billing_email }}
                                </p>
                            </div>
                            @if($order->notes)
                                <div class="mb-3">
                                    <h5>Order Notes</h5>
                                    <p>{{ $order->notes }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="Place-order mt-25">
                            <a class="btn-hover" href="{{ route('home') }}">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Order Success Area End -->
@endsection
