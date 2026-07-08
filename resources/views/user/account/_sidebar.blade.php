@php
    $accountUser = Auth::user();
    $sidebarWishlistCount = count(session('wishlist', []));
@endphp
<aside class="account-sidebar">
    <div class="account-user">
        <img class="account-user-avatar" src="{{ $accountUser->avatar_url }}" alt="{{ $accountUser->name }}">
        <div class="account-user-info">
            <span class="account-user-name">{{ $accountUser->name }}</span>
            <span class="account-user-email">{{ $accountUser->email }}</span>
        </div>
    </div>

    <nav class="account-menu">
        <a href="{{ route('user.dashboard') }}" class="{{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
            <i class="lnr lnr-home"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('account.orders') }}" class="{{ request()->routeIs('account.orders*') ? 'active' : '' }}">
            <i class="lnr lnr-cart"></i><span>My Orders</span>
        </a>
        <a href="{{ route('wishlist.index') }}">
            <i class="lnr lnr-heart"></i><span>Wishlist</span>
            @if($sidebarWishlistCount > 0)
                <span class="account-menu-count">{{ $sidebarWishlistCount }}</span>
            @endif
        </a>
        <a href="{{ route('account.addresses') }}" class="{{ request()->routeIs('account.addresses') ? 'active' : '' }}">
            <i class="lnr lnr-map-marker"></i><span>Addresses</span>
        </a>
        <a href="{{ route('account.profile') }}" class="{{ request()->routeIs('account.profile') ? 'active' : '' }}">
            <i class="lnr lnr-user"></i><span>Account Details</span>
        </a>
        <a href="{{ route('logout') }}" class="account-menu-logout">
            <i class="lnr lnr-exit"></i><span>Logout</span>
        </a>
    </nav>
</aside>
