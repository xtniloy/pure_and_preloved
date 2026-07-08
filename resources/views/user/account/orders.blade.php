@extends('user.account.layout')

@section('title', 'My Orders')
@section('breadcrumb', 'Orders')

@section('account-content')
    <div class="account-page-head">
        <h4>My Orders</h4>
        <p>Track, review and manage all your orders in one place.</p>
    </div>

    {{-- Status filter --}}
    <div class="account-filter mb-4">
        <a href="{{ route('account.orders') }}" class="{{ $status === null ? 'active' : '' }}">
            All <span class="count">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('account.orders', ['status' => 'in_progress']) }}" class="{{ $status === 'in_progress' ? 'active' : '' }}">
            In Progress <span class="count">{{ $counts['in_progress'] }}</span>
        </a>
        <a href="{{ route('account.orders', ['status' => 'completed']) }}" class="{{ $status === 'completed' ? 'active' : '' }}">
            Completed <span class="count">{{ $counts['completed'] }}</span>
        </a>
        <a href="{{ route('account.orders', ['status' => 'cancelled']) }}" class="{{ $status === 'cancelled' ? 'active' : '' }}">
            Cancelled <span class="count">{{ $counts['cancelled'] }}</span>
        </a>
    </div>

    @if($orders->isEmpty())
        <div class="account-card">
            <div class="account-empty">
                <i class="lnr lnr-cart"></i>
                @if($status)
                    <h6>No {{ str_replace('_', ' ', $status) }} orders</h6>
                    <p>You don't have any orders matching this filter.</p>
                    <a href="{{ route('account.orders') }}" class="btn btn-outline-primary">Show All Orders</a>
                @else
                    <h6>No orders yet</h6>
                    <p>When you place your first order, it will show up here.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary">Start Shopping</a>
                @endif
            </div>
        </div>
    @else
        <div class="account-card">
            <div class="order-row-list">
                @foreach($orders as $order)
                    @include('user.account._order_row', ['order' => $order])
                @endforeach
            </div>
        </div>

        @if($orders->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        @endif
    @endif
@endsection
