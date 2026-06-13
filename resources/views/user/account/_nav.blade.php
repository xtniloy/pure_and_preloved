<ul class="nav nav-pills gap-2 mb-4">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('account.orders') || request()->routeIs('account.orders.show') ? 'active' : '' }}" href="{{ route('account.orders') }}">My Orders</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('account.profile') ? 'active' : '' }}" href="{{ route('account.profile') }}">Profile</a>
    </li>
    <li class="nav-item ms-auto">
        <a class="nav-link text-danger" href="{{ route('logout') }}">Logout</a>
    </li>
</ul>
