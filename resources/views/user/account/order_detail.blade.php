@extends('public.layouts.main')
@section('title')
    Order {{ $order->reference }}
@endsection

@push('styles')
    <style>
        .order-tracker { position: relative; }
        .order-tracker::before {
            content: "";
            position: absolute;
            top: 19px;
            left: 9%;
            right: 9%;
            height: 2px;
            background: #e9ecef;
            z-index: 0;
        }
        .order-tracker > .order-step { position: relative; z-index: 1; }
        .order-step .order-step-dot {
            width: 38px;
            height: 38px;
        }
    </style>
@endpush

@section('content')
    <div class="container mt-50px mb-40px">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @include('partials.notification')
                @include('user.account._nav')

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Order {{ $order->reference }}</h4>
                    <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-secondary">Back to orders</a>
                </div>

                {{-- Status tracking --}}
                <div class="card mb-4">
                    <div class="card-body">
                        @if($order->status === 'cancelled')
                            <div class="alert alert-danger mb-0">
                                This order has been <strong>cancelled</strong>. If you have questions, please contact us.
                            </div>
                        @else
                            @php
                                $steps = \App\Models\Order::PROGRESS_STATUSES;
                                $currentIndex = array_search($order->status, $steps, true);
                                if ($currentIndex === false) { $currentIndex = 0; }
                            @endphp
                            <div class="d-flex justify-content-between order-tracker">
                                @foreach($steps as $i => $step)
                                    <div class="text-center flex-fill order-step">
                                        <div class="order-step-dot rounded-circle d-inline-flex align-items-center justify-content-center mb-2 {{ $i <= $currentIndex ? 'bg-success text-white' : 'bg-light text-muted border' }}">
                                            @if($i < $currentIndex)
                                                &checkmark;
                                            @else
                                                {{ $i + 1 }}
                                            @endif
                                        </div>
                                        <div class="small {{ $i <= $currentIndex ? 'fw-semibold' : 'text-muted' }}">{{ ucfirst($step) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">
                    {{-- Items --}}
                    <div class="col-lg-7 mb-4">
                        <div class="card h-100">
                            <div class="card-header">Items</div>
                            <div class="card-body p-0">
                                <table class="table align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th class="ps-3">Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th class="text-end pe-3">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td class="ps-3">
                                                {{ $item->product_name }}
                                                <div class="small text-muted">{{ $item->product_sku }}</div>
                                            </td>
                                            <td>${{ number_format($item->unit_price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end pe-3">${{ number_format($item->line_total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end">Subtotal</td>
                                        <td class="text-end pe-3">${{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">Shipping ({{ ucfirst($order->shipping_method) }})</td>
                                        <td class="text-end pe-3">${{ number_format($order->shipping_charge, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-semibold">Total</td>
                                        <td class="text-end pe-3 fw-semibold">${{ number_format($order->total, 2) }}</td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Order + billing info --}}
                    <div class="col-lg-5 mb-4">
                        <div class="card mb-3">
                            <div class="card-header">Order Info</div>
                            <div class="card-body">
                                <p class="mb-1"><strong>Reference:</strong> {{ $order->reference }}</p>
                                <p class="mb-1"><strong>Placed:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                                <p class="mb-0"><strong>Status:</strong>
                                    <span class="badge bg-{{ $order->status_color }} text-uppercase">{{ $order->status }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">Billing Details</div>
                            <div class="card-body">
                                <p class="mb-1">{{ $order->billing_first_name }} {{ $order->billing_last_name }}</p>
                                <p class="mb-1">{{ $order->billing_address }}</p>
                                <p class="mb-1">{{ $order->billing_city }}, {{ $order->billing_postcode }}</p>
                                <p class="mb-1">{{ $order->billing_country }}</p>
                                <p class="mb-1"><strong>Phone:</strong> {{ $order->billing_phone }}</p>
                                <p class="mb-0"><strong>Email:</strong> {{ $order->billing_email }}</p>
                                @if($order->notes)
                                    <hr>
                                    <p class="mb-0"><strong>Notes:</strong> {{ $order->notes }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
