@extends('admin.layout.main')
@section('page-title')
    {{ $role ? 'Edit' : 'Create' }} Role
@endsection

@php
    $actionUrl = $role ? route('admin.roles.update', $role) : route('admin.roles.store');
    $method = $role ? 'PUT' : 'POST';
@endphp

@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold">{{ $role ? 'Edit' : 'Create' }} Role</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.roles.index') }}">Roles &amp; Access</a></li>
                <li class="breadcrumb-item active">{{ $role ? 'Edit' : 'Create' }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ $role ? 'Edit' : 'Create' }} Role</h5>
                    </div>
                    <div class="card-body">
                        @include('partials.notification')

                        <form action="{{ $actionUrl }}" method="post">
                            @method($method)
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-6 col-12">
                                    <label for="name">Role Name <b class="text-danger">*</b></label>
                                    <input type="text" required
                                           class="form-control @error('name') border-danger @enderror"
                                           id="name" name="name" placeholder="e.g. Order Manager"
                                           value="{{ old('name', $role->name ?? '') }}">
                                    @error('name')
                                    <span class="text-danger mx-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="fw-semibold mb-0">Permissions</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="check-all">
                                    <label class="form-check-label" for="check-all">Select all</label>
                                </div>
                            </div>

                            <div class="row">
                                @foreach($modules as $key => $module)
                                    <div class="col-md-4 col-sm-6 col-12 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input perm-check" type="checkbox"
                                                   id="perm-{{ $key }}"
                                                   name="permissions[]"
                                                   value="{{ $module['permission'] }}"
                                                   {{ in_array($module['permission'], old('permissions', $assigned)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm-{{ $key }}">
                                                {{ $module['label'] }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">Save Role</button>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary mt-3 ms-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const all = document.getElementById('check-all');
            const boxes = Array.from(document.querySelectorAll('.perm-check'));
            const sync = () => { all.checked = boxes.length && boxes.every(b => b.checked); };
            all.addEventListener('change', () => { boxes.forEach(b => b.checked = all.checked); });
            boxes.forEach(b => b.addEventListener('change', sync));
            sync();
        })();
    </script>
@endsection
