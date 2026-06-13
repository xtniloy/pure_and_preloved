@extends('public.layouts.main')
@section('page-title')
    User Dashboard
@endsection
@section('content')
    <div class="container mt-50px mb-40px">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @include('partials.notification')
            @include('user.account._nav')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Welcome, {{ $user->name }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    @if($user->phone)
                        <p><strong>Phone:</strong> {{ $user->phone }}</p>
                    @endif
                    <a href="{{ route('account.profile') }}" class="btn btn-outline-primary">Edit Profile</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    @if($orders->isNotEmpty())
                        <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
                    @endif
                </div>
                <div class="card-body">
                    @if($orders->isEmpty())
                        <p class="mb-0">You have no orders yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders->take(5) as $order)
                                    <tr>
                                        <td class="fw-semibold">{{ $order->reference }}</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td><span class="badge bg-{{ $order->status_color }} text-uppercase">{{ $order->status }}</span></td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('account.orders.show', $order->reference) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

