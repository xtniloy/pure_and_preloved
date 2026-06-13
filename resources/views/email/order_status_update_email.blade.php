@extends('email.layout.main')
@php
    $status = $order->status;
    $showShipping = $status !== 'cancelled';

    switch ($status) {
        case 'shipped':
            $heading = 'Your order is on its way!';
            $message = 'Good news ' . $order->billing_first_name . ' — your order has been shipped and is on its way to you.';
            break;
        case 'completed':
            $heading = 'Your order is complete';
            $message = 'Your order has been completed. We hope you love your purchase!';
            break;
        case 'cancelled':
            $heading = 'Your order has been cancelled';
            $message = 'Your order ' . $order->reference . ' has been cancelled. If this is unexpected, please get in touch with us.';
            break;
        case 'paid':
            $heading = 'Payment received';
            $message = 'We\'ve received your payment for order ' . $order->reference . '. We\'ll start preparing it right away.';
            break;
        case 'processing':
            $heading = 'Your order is being processed';
            $message = 'We\'re currently preparing your order ' . $order->reference . '.';
            break;
        default:
            $heading = 'Order status updated';
            $message = 'The status of your order ' . $order->reference . ' has been updated.';
            break;
    }
@endphp
@section('content')
    <tr>
        <td style="line-height: 24px; font-size: 15px; width: 100%; margin: 0; padding: 40px;" align="left" bgcolor="#ffffff">
            <h1 class="h3 fw-700" style="padding-top: 0; padding-bottom: 0; font-weight: 700 !important; vertical-align: baseline; font-size: 28px; line-height: 33.6px; margin: 0;" align="left">{{ $heading }}</h1>

            <table class="s-4 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                <tbody><tr><td style="line-height: 16px; font-size: 15px; width: 100%; height: 16px; margin: 0;" align="left" width="100%" height="16">&#160;</td></tr></tbody>
            </table>

            <p style="line-height: 24px; font-size: 15px; width: 100%; margin: 0;" align="left">{{ $message }}</p>
            <p style="line-height: 24px; font-size: 15px; width: 100%; margin: 8px 0 0;" align="left">
                Order Reference: <strong>{{ $order->reference }}</strong><br>
                Current Status: <strong style="text-transform: uppercase;">{{ $order->status }}</strong>
            </p>

            <table class="s-6 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                <tbody><tr><td style="line-height: 24px; font-size: 24px; width: 100%; height: 24px; margin: 0;" align="left" width="100%" height="24">&#160;</td></tr></tbody>
            </table>

            <h2 style="font-size: 18px; font-weight: 700; margin: 0 0 12px;">Order Summary</h2>
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;" width="100%">
                <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td style="line-height: 22px; font-size: 14px; padding: 8px 0; border-bottom: 1px solid #e2e8f0;" align="left">
                            {{ $item->product_name }} <span style="color: #718096;">&times; {{ $item->quantity }}</span>
                        </td>
                        <td style="line-height: 22px; font-size: 14px; padding: 8px 0; border-bottom: 1px solid #e2e8f0; white-space: nowrap;" align="right">
                            ${{ number_format($item->line_total, 2) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="line-height: 22px; font-size: 14px; padding: 8px 0;" align="left">Subtotal</td>
                    <td style="line-height: 22px; font-size: 14px; padding: 8px 0;" align="right">${{ number_format($order->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td style="line-height: 22px; font-size: 14px; padding: 4px 0;" align="left">Shipping ({{ ucfirst($order->shipping_method) }})</td>
                    <td style="line-height: 22px; font-size: 14px; padding: 4px 0;" align="right">${{ number_format($order->shipping_charge, 2) }}</td>
                </tr>
                <tr>
                    <td style="line-height: 24px; font-size: 16px; font-weight: 700; padding: 10px 0; border-top: 2px solid #e2e8f0;" align="left">Total</td>
                    <td style="line-height: 24px; font-size: 16px; font-weight: 700; padding: 10px 0; border-top: 2px solid #e2e8f0;" align="right">${{ number_format($order->total, 2) }}</td>
                </tr>
                </tbody>
            </table>

            @if($showShipping)
                <table class="s-6 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                    <tbody><tr><td style="line-height: 24px; font-size: 24px; width: 100%; height: 24px; margin: 0;" align="left" width="100%" height="24">&#160;</td></tr></tbody>
                </table>

                <h2 style="font-size: 18px; font-weight: 700; margin: 0 0 12px;">{{ $status === 'shipped' ? 'Shipping To' : 'Delivery Address' }}</h2>
                <p style="line-height: 22px; font-size: 14px; margin: 0;" align="left">
                    {{ $order->billing_first_name }} {{ $order->billing_last_name }}<br>
                    {{ $order->billing_address }}<br>
                    {{ $order->billing_city }}, {{ $order->billing_postcode }}<br>
                    {{ $order->billing_country }}<br>
                    {{ $order->billing_phone }}
                </p>
            @endif

            <table class="s-6 w-full" role="presentation" border="0" cellpadding="0" cellspacing="0" style="width: 100%;" width="100%">
                <tbody><tr><td style="line-height: 24px; font-size: 24px; width: 100%; height: 24px; margin: 0;" align="left" width="100%" height="24">&#160;</td></tr></tbody>
            </table>

            <table class="ax-center" role="presentation" align="center" border="0" cellpadding="0" cellspacing="0" style="border-radius: 6px; border-collapse: separate !important; font-weight: 700 !important;">
                <tbody>
                <tr>
                    <td style="line-height: 24px; font-size: 15px; border-radius: 6px; font-weight: 700 !important; margin: 0;" align="center" bgcolor="#0d6efd">
                        <a href="{{ route('order.success', $order->reference) }}" style="color: #ffffff; font-size: 15px; font-family: Helvetica, Arial, sans-serif; text-decoration: none; border-radius: 6px; line-height: 20px; display: block; font-weight: 700 !important; white-space: nowrap; background-color: #0d6efd; padding: 12px 24px; border: 1px solid #0d6efd;">View Your Order</a>
                    </td>
                </tr>
                </tbody>
            </table>

            <br>
            <p>
                Thanks,<br>
                {{ \Illuminate\Support\Facades\Config::get('app.name') }} Team
            </p>
        </td>
    </tr>
@endsection
