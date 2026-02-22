@extends('admin.layout.main')
@section('page-title')
    Order Management
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Order Management
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a>
                </li>
                <li class="breadcrumb-item active"><a href="{{route('admin.orders.index')}}">Order Management</a>
                </li>
            </ol>
        </nav>
        @include('partials.notification')
            <div class="row mb-3">
                <div class="col">
                    <h4>Orders</h4>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Search reference</label>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Reference</th>
                                    <th>User</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Created At</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->reference }}</td>
                                        <td>
                                            @if($order->user)
                                                {{ $order->user->name }}<br>
                                                <small>{{ $order->user->email }}</small>
                                            @else
                                                Guest
                                            @endif
                                        </td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td><span class="badge bg-secondary text-uppercase">{{ $order->status }}</span></td>
                                        <td>{{ $order->items_count }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No orders found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div>
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
