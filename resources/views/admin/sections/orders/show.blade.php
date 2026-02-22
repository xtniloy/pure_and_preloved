@extends('admin.layout.main')
@section('page-title')
    Order Details
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Order Details
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a>
                </li>
                <li class="breadcrumb-item"><a href="{{route('admin.orders.index')}}">Order Management</a></li>
                <li class="breadcrumb-item active">Order #{{ $order->id }}</li>
            </ol>
        </nav>
        @include('partials.notification')
            <div class="row mb-3">
                <div class="col">
                    <h4>Order #{{ $order->id }} ({{ $order->reference }})</h4>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            Order Info
                        </div>
                        <div class="card-body">
                            <p><strong>Status:</strong> <span class="badge bg-secondary text-uppercase">{{ $order->status }}</span></p>
                            <p><strong>Total:</strong> ${{ number_format($order->total, 2) }}</p>
                            <p><strong>Placed at:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Customer
                        </div>
                        <div class="card-body">
                            @if($order->user)
                                <p><strong>Name:</strong> {{ $order->user->name }}</p>
                                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                                @if($order->user->phone)
                                    <p><strong>Phone:</strong> {{ $order->user->phone }}</p>
                                @endif
                            @else
                                <p>Guest order</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            Update Status
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            Items
                        </div>
                        <div class="card-body">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Unit price</th>
                                    <th>Quantity</th>
                                    <th>Line total</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($order->items as $item)
                                    <tr>
                                        <td>
                                            @if($item->product)
                                                {{ $item->product_name }}
                                            @else
                                                {{ $item->product_name }}
                                            @endif
                                        </td>
                                        <td>{{ $item->product_sku }}</td>
                                        <td>${{ number_format($item->unit_price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->line_total, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No items found for this order.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
