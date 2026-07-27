@extends('admin.layout.main')
@section('page-title')
    Roles &amp; Access
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold">Roles &amp; Access</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Roles &amp; Access</li>
            </ol>
        </nav>

        @include('partials.notification')

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>Roles</strong>
                        <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-outline-secondary">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-plus') }}"></use>
                            </svg>Add Role
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="card border">
                            <div class="p-3">
                                <table class="table align-middle">
                                    <thead>
                                    <tr>
                                        <th>Role</th>
                                        <th>Permissions</th>
                                        <th>Admins</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($roles as $role)
                                        <tr>
                                            <td>
                                                <span class="fw-semibold">{{ $role->name }}</span>
                                                @if($role->name === \App\Support\AdminAccess::SUPER_ADMIN)
                                                    <span class="badge bg-dark ms-1">Full access</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($role->name === \App\Support\AdminAccess::SUPER_ADMIN)
                                                    <span class="text-muted">All permissions</span>
                                                @else
                                                    <span class="badge bg-info">{{ $role->permissions_count }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $adminCounts[$role->id] ?? 0 }}</td>
                                            <td class="text-end">
                                                @if($role->name === \App\Support\AdminAccess::SUPER_ADMIN)
                                                    <span class="text-muted small">Protected</span>
                                                @else
                                                    <a href="{{ route('admin.roles.edit', $role) }}"
                                                       class="btn btn-sm btn-outline-primary">Edit</a>
                                                    <form action="{{ route('admin.roles.destroy', $role) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Delete this role?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">No roles yet.</td>
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
    </div>
@endsection
