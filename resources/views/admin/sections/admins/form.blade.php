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

                            <button type="submit" class="btn btn-primary mt-2">Save changes</button>
                            <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary mt-2 ms-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
