@extends('admin.layout.main')
@section('page-title')
    Notifications
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold">Notifications</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Notifications</li>
            </ol>
        </nav>
        @include('partials.notification')

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>All notifications</strong>
                <a href="{{ route('admin.notifications.read_all') }}" class="btn btn-sm btn-outline-secondary">Mark all as read</a>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @forelse($notifications as $notification)
                        @php
                            $data = $notification->data;
                            $isUnread = is_null($notification->read_at);
                        @endphp
                        <a href="{{ route('admin.notifications.read', $notification->id) }}"
                           class="list-group-item list-group-item-action d-flex align-items-start gap-3 {{ $isUnread ? 'bg-body-tertiary' : '' }}">
                            <svg class="icon icon-lg text-{{ $data['color'] ?? 'secondary' }} mt-1">
                                <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#' . ($data['icon'] ?? 'cil-bell')) }}"></use>
                            </svg>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <span class="{{ $isUnread ? 'fw-semibold' : '' }}">{{ $data['title'] ?? 'Notification' }}</span>
                                    <small class="text-medium-emphasis">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="small text-medium-emphasis">{{ $data['message'] ?? '' }}</div>
                            </div>
                            @if($isUnread)
                                <span class="badge bg-danger align-self-center">New</span>
                            @endif
                        </a>
                    @empty
                        <div class="text-center text-medium-emphasis py-5">You have no notifications.</div>
                    @endforelse
                </div>
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
