@php
    $admin = auth('admin')->user();
    $unread = $admin ? $admin->unreadNotifications()->take(8)->get() : collect();
    $unreadCount = $admin ? $admin->unreadNotifications()->count() : 0;
@endphp
<li class="nav-item dropdown">
    <a class="nav-link" data-coreui-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
        <span class="d-inline-block my-1 mx-2 position-relative">
            <svg class="icon icon-lg">
                <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-bell') }}"></use>
            </svg>
            @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:.6rem;">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    <span class="visually-hidden">unread notifications</span>
                </span>
            @endif
        </span>
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg pt-0">
        <div class="dropdown-header bg-body-tertiary text-body-secondary fw-semibold rounded-top mb-2">
            You have {{ $unreadCount }} unread notification{{ $unreadCount === 1 ? '' : 's' }}
        </div>
        @forelse($unread as $notification)
            @php $data = $notification->data; @endphp
            <a class="dropdown-item d-flex align-items-start gap-2 py-2" href="{{ route('admin.notifications.read', $notification->id) }}">
                <svg class="icon me-1 text-{{ $data['color'] ?? 'secondary' }} mt-1">
                    <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#' . ($data['icon'] ?? 'cil-bell')) }}"></use>
                </svg>
                <span class="d-block">
                    <span class="d-block">{{ $data['title'] ?? 'Notification' }}</span>
                    <small class="text-body-secondary">{{ $data['message'] ?? '' }}</small>
                </span>
            </a>
        @empty
            <div class="dropdown-item text-center text-body-secondary py-3">No new notifications</div>
        @endforelse
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-center fw-semibold" href="{{ route('admin.notifications.index') }}">View all notifications</a>
    </div>
</li>
