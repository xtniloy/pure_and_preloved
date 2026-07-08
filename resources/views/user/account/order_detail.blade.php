@extends('user.account.layout')

@section('title', 'Order ' . $order->reference)
@section('breadcrumb', 'Order ' . $order->reference)

@section('account-content')
    <a href="{{ route('account.orders') }}" class="account-back-link">
        <i class="lnr lnr-arrow-left"></i> Back to orders
    </a>

    <div class="account-page-head d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h4>Order {{ $order->reference }}</h4>
            <p>Placed on {{ $order->created_at->format('d M Y \a\t H:i') }}</p>
        </div>
        <span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span>
    </div>

    {{-- Status tracking --}}
    <div class="account-card mb-4">
        <div class="account-card-body">
            @if($order->status === 'cancelled')
                <div class="alert alert-danger mb-0">
                    This order has been <strong>cancelled</strong>. If you have any questions, please
                    <a href="{{ route('contact.index') }}" class="alert-link">contact us</a>.
                </div>
            @else
                @php
                    $steps = \App\Models\Order::PROGRESS_STATUSES;
                    $currentIndex = array_search($order->status, $steps, true);
                    if ($currentIndex === false) { $currentIndex = 0; }
                    $progress = count($steps) > 1 ? ($currentIndex / (count($steps) - 1)) * 100 : 0;
                @endphp
                <div class="order-tracker">
                    <div class="order-tracker-line"><span style="width: {{ $progress }}%"></span></div>
                    <div class="order-tracker-steps">
                        @foreach($steps as $i => $step)
                            <div class="order-step {{ $i <= $currentIndex ? 'is-done' : '' }} {{ $i === $currentIndex ? 'is-current' : '' }}">
                                <span class="order-step-dot">
                                    @if($i <= $currentIndex)
                                        &#10003;
                                    @else
                                        {{ $i + 1 }}
                                    @endif
                                </span>
                                <span class="order-step-label">{{ ucfirst($step) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- Items + totals --}}
        <div class="col-lg-8">
            <div class="account-card">
                <div class="account-card-header">
                    <h5>Items ({{ $order->items->count() }})</h5>
                </div>
                <div class="table-responsive">
                    <table class="order-items-table">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->items as $item)
                            @php
                                $thumb = $item->product?->thumbnailImage?->public_url
                                    ?? $item->product?->main_image?->public_url
                                    ?? asset('assets/images/product-image/8.jpg');
                            @endphp
                            <tr>
                                <td>
                                    <span class="d-flex align-items-center gap-3">
                                        <img src="{{ $thumb }}" alt="{{ $item->product_name }}" class="order-item-thumb">
                                        <span>
                                            <span class="order-item-name d-block">{{ $item->product_name }}</span>
                                            @if($item->product_sku)
                                                <span class="order-item-sku">SKU: {{ $item->product_sku }}</span>
                                            @endif
                                        </span>
                                    </span>
                                </td>
                                <td>${{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end fw-bold">${{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="order-totals">
                    <div class="order-totals-row">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="order-totals-row">
                        <span>Shipping ({{ ucfirst($order->shipping_method) }})</span>
                        <span>${{ number_format($order->shipping_charge, 2) }}</span>
                    </div>
                    <div class="order-totals-row grand">
                        <span>Total</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Billing + help --}}
        <div class="col-lg-4">
            <div class="account-card mb-4">
                <div class="account-card-header">
                    <h5>Billing Details</h5>
                </div>
                <div class="account-card-body">
                    <p class="order-address-name">{{ $order->billing_first_name }} {{ $order->billing_last_name }}</p>
                    <p class="order-address-text">
                        {{ $order->billing_address }}<br>
                        {{ $order->billing_city }}{{ $order->billing_postcode ? ', ' . $order->billing_postcode : '' }}<br>
                        {{ $order->billing_country }}
                    </p>
                    <hr class="account-divider">
                    <p class="order-contact-row"><i class="lnr lnr-phone-handset"></i>{{ $order->billing_phone }}</p>
                    <p class="order-contact-row mb-0"><i class="lnr lnr-envelope"></i>{{ $order->billing_email }}</p>
                    @if($order->notes)
                        <hr class="account-divider">
                        <span class="account-mini-label">Order notes</span>
                        <p class="order-address-text">{{ $order->notes }}</p>
                    @endif
                </div>
            </div>

            <div class="account-help-card">
                <i class="lnr lnr-question-circle"></i>
                <h6>Need help with this order?</h6>
                <p>Our support team is happy to assist with any questions about your order.</p>
                <a href="{{ route('contact.index') }}" class="btn btn-outline-primary btn-sm">Contact Us</a>
            </div>
        </div>
    </div>
@endsection
