@extends('user.account.layout')

@section('title', 'My Account')

@section('account-content')
    <div class="account-page-head">
        <h4>Welcome back, {{ strtok($user->name, ' ') }}</h4>
        <p>Here's what's happening with your account.</p>
    </div>

    {{-- Stats --}}
    <div class="row g-3">
        <div class="col-6 col-lg-3">
            <a href="{{ route('account.orders') }}" class="account-stat">
                <span class="account-stat-icon"><i class="lnr lnr-cart"></i></span>
                <span class="account-stat-text">
                    <span class="account-stat-value">{{ $orderStats['total'] }}</span>
                    <span class="account-stat-label">Total Orders</span>
                </span>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('account.orders', ['status' => 'in_progress']) }}" class="account-stat">
                <span class="account-stat-icon"><i class="lnr lnr-hourglass"></i></span>
                <span class="account-stat-text">
                    <span class="account-stat-value">{{ $orderStats['open'] }}</span>
                    <span class="account-stat-label">In Progress</span>
                </span>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('account.orders', ['status' => 'completed']) }}" class="account-stat">
                <span class="account-stat-icon"><i class="lnr lnr-checkmark-circle"></i></span>
                <span class="account-stat-text">
                    <span class="account-stat-value">{{ $orderStats['completed'] }}</span>
                    <span class="account-stat-label">Completed</span>
                </span>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a href="{{ route('wishlist.index') }}" class="account-stat">
                <span class="account-stat-icon"><i class="lnr lnr-heart"></i></span>
                <span class="account-stat-text">
                    <span class="account-stat-value">{{ $wishlistCount }}</span>
                    <span class="account-stat-label">Wishlist</span>
                </span>
            </a>
        </div>
    </div>

    {{-- Recent orders --}}
    <div class="account-card mt-4">
        <div class="account-card-header">
            <div>
                <h5>Recent Orders</h5>
                <p class="account-card-sub">Your latest purchases at a glance</p>
            </div>
            @if($recentOrders->isNotEmpty())
                <a class="account-card-link" href="{{ route('account.orders') }}">View all</a>
            @endif
        </div>
        @if($recentOrders->isEmpty())
            <div class="account-empty">
                <i class="lnr lnr-cart"></i>
                <h6>No orders yet</h6>
                <p>When you place your first order, it will show up here.</p>
                <a href="{{ route('shop.index') }}" class="btn btn-primary">Start Shopping</a>
            </div>
        @else
            <div class="order-row-list">
                @foreach($recentOrders as $order)
                    @include('user.account._order_row', ['order' => $order])
                @endforeach
            </div>
        @endif
    </div>

    {{-- Account snapshot --}}
    <div class="row g-4 mt-1">
        <div class="col-md-6 d-flex">
            <div class="account-card w-100">
                <div class="account-card-header">
                    <h5>Account Details</h5>
                    <a class="account-card-link" href="{{ route('account.profile') }}">Edit</a>
                </div>
                <div class="account-card-body">
                    <div class="account-detail-row">
                        <span class="label">Name</span>
                        <span class="value">{{ $user->name }}</span>
                    </div>
                    <div class="account-detail-row">
                        <span class="label">Email</span>
                        <span class="value">{{ $user->email }}</span>
                    </div>
                    <div class="account-detail-row">
                        <span class="label">Phone</span>
                        <span class="value">{{ $user->phone ?: 'Not added yet' }}</span>
                    </div>
                    <div class="account-detail-row">
                        <span class="label">Joined</span>
                        <span class="value">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex">
            <div class="account-card w-100">
                <div class="account-card-header">
                    <h5>Addresses</h5>
                    <a class="account-card-link" href="{{ route('account.addresses') }}">Manage</a>
                </div>
                <div class="account-card-body">
                    <span class="account-mini-label">Delivery address</span>
                    <p class="address-preview {{ $user->delivery_address ? '' : 'empty' }}">{{ $user->delivery_address ?: 'No delivery address saved yet.' }}</p>
                    <span class="account-mini-label mt-3">Billing address</span>
                    <p class="address-preview {{ $user->billing_address ? '' : 'empty' }}">{{ $user->billing_address ?: 'No billing address saved yet.' }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
