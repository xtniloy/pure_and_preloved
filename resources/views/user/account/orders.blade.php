@extends('public.layouts.main')
@section('title')
    My Orders
@endsection
@section('content')
    <div class="container mt-50px mb-40px">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @include('partials.notification')
                @include('user.account._nav')

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">My Orders</h5>
                    </div>
                    <div class="card-body">
                        @if($orders->isEmpty())
                            <div class="text-center py-5">
                                <p class="mb-3">You haven't placed any orders yet.</p>
                                <a href="{{ route('shop.index') }}" class="btn btn-primary">Start Shopping</a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                    <tr>
                                        <th>Reference</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="fw-semibold">{{ $order->reference }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>{{ $order->items_count }}</td>
                                            <td>${{ number_format($order->total, 2) }}</td>
                                            <td><span class="badge bg-{{ $order->status_color }} text-uppercase">{{ $order->status }}</span></td>
                                            <td class="text-end">
                                                <a href="{{ route('account.orders.show', $order->reference) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 d-flex justify-content-center">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
