@extends('admin.layout.main')
@section('page-title')
    {{ isset($admin) ? 'Update' : 'Create' }} Admin
@endsection

@php
    if (isset($admin)) {
        $actionUrl = route('admin.admins.update', $admin);
        $method = 'PATCH';
    } else {
        $actionUrl = route('admin.admins.store');
        $method = 'POST';
    }
@endphp

@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold">Admin {{ isset($admin) ? 'Update' : 'Create' }}</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.admins.index') }}">Admin Management</a></li>
                <li class="breadcrumb-item active">{{ isset($admin) ? 'Update' : 'Create' }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ isset($admin) ? 'Update' : 'Create' }} Admin</h5>

                        @if(isset($admin))
                            <div class="d-flex gap-2 align-items-center">
                                @if(!$admin->email_verified_at)
                                    <span class="badge bg-warning text-dark">Account not activated</span>
                                    <a class="btn btn-sm btn-primary"
                                       href="{{ route('admin.admins.email.resend', $admin) }}">
                                        Resend Activation Email
                                    </a>
                                @else
                                    <span class="badge bg-success">Account activated</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        @include('partials.notification')

                        <form action="{{ $actionUrl }}" method="post">
                            @method($method)
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="name">Full Name <b class="text-danger">*</b></label>
                                        <input type="text" required
                                               class="form-control @error('name') border-danger @enderror"
                                               id="name" name="name" placeholder="Full name"
                                               value="{{ old('name', $admin->name ?? '') }}">
                                        @error('name')
                                        <span class="text-danger mx-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email <b class="text-danger">*</b></label>
                                        <input type="email" required
                                               class="form-control @error('email') border-danger @enderror"
                                               id="email" name="email" placeholder="Email"
                                               value="{{ old('email', $admin->email ?? '') }}"
                                               {{ isset($admin) && $admin->id === auth()->guard('admin')->id() ? 'readonly' : '' }}>
                                        @error('email')
                                        <span class="text-danger mx-1">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @if(isset($admin))
                                <div class="row mb-3">
                                    <div class="col-md-6 col-12">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select id="status" name="status" class="form-control"
                                                    {{ $admin->id === auth()->guard('admin')->id() ? 'disabled' : '' }}>
                                                <option value="1" {{ old('status', $admin->status) == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="0" {{ old('status', $admin->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @if($admin->id === auth()->guard('admin')->id())
                                                <input type="hidden" name="status" value="{{ $admin->status }}">
                                                <small class="text-muted">You cannot change your own status.</small>
                                            @endif
                                            @error('status')
                                            <span class="text-danger mx-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @php
                                $isSelf = isset($admin) && $admin->id === auth()->guard('admin')->id();
                                $currentRoles = isset($admin) ? $admin->getRoleNames()->all() : [];
                            @endphp

                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="fw-semibold">Roles</label>
                                    @if($isSelf)
                                        <div class="text-muted small mb-2">You cannot change your own roles.</div>
                                        <div>
                                            @forelse($currentRoles as $roleName)
                                                <span class="badge bg-secondary me-1">{{ $roleName }}</span>
                                            @empty
                                                <span class="text-muted">No roles assigned.</span>
                                            @endforelse
                                        </div>
                                    @else
                                        <div class="row">
                                            @forelse($roles as $role)
                                                <div class="col-md-4 col-sm-6 col-12 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               id="role-{{ $role->id }}"
                                                               name="roles[]" value="{{ $role->name }}"
                                                               {{ in_array($role->name, old('roles', $currentRoles)) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="role-{{ $role->id }}">
                                                            {{ $role->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <span class="text-muted">No roles yet. </span>
                                                    @if(Route::has('admin.roles.create'))
                                                        <a href="{{ route('admin.roles.create') }}">Create one</a>.
                                                    @endif
                                                </div>
                                            @endforelse
                                        </div>
                                        @error('roles') <span class="text-danger mx-1">{{ $message }}</span> @enderror
                                        @error('roles.*') <span class="text-danger mx-1">{{ $message }}</span> @enderror
                                    @endif
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-2">Save changes</button>
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary mt-2 ms-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
