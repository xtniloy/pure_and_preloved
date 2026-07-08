{{-- One clickable order summary row (expects $order, optionally with items_count). --}}
<a href="{{ route('account.orders.show', $order->reference) }}" class="order-row">
    <span class="order-row-main">
        <span class="order-row-ref">{{ $order->reference }}</span>
        <span class="order-row-meta">
            {{ $order->created_at->format('d M Y') }}
            @if(isset($order->items_count))
                &middot; {{ $order->items_count }} item{{ $order->items_count == 1 ? '' : 's' }}
            @endif
        </span>
    </span>
    <span class="status-badge status-{{ $order->status }}">{{ $order->status }}</span>
    <span class="order-row-total">${{ number_format($order->total, 2) }}</span>
    <span class="order-row-arrow"><i class="lnr lnr-chevron-right"></i></span>
</a>
