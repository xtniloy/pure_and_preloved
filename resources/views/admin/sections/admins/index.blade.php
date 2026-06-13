@extends('admin.layout.main')
@section('page-title')
    Admin Management
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold">Admin Management</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Admin Management</li>
            </ol>
        </nav>

        @include('partials.notification')

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between">
                        <strong>Admin list</strong>
                        <span class="small ms-1">Total: {{ $admins->total() ?? 0 }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mx-2 mb-3">
                            <div class="col-xxl-6 col-lg-8">
                                <form class="admin-search-form">
                                    <div class="input-group">
                                        <input type="text" name="q" class="form-control"
                                               value="{{ request()->q }}"
                                               placeholder="Search name or email..."
                                               @if(request()->q) autofocus @endif>

                                        <select name="status" class="form-control form-select search-fld-dropdown"
                                                aria-label="Select status">
                                            <option value="">All statuses</option>
                                            <option value="1" {{ request()->status == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ request()->status == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>

                                        <button class="btn btn-outline-secondary" type="reset"
                                                onclick="location.href='{{ route('admin.admins.index') }}';">
                                            <svg class="icon me-2">
                                                <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-x') }}"></use>
                                            </svg>Reset
                                        </button>

                                        <button class="btn btn-outline-secondary" type="submit">
                                            <svg class="icon me-2">
                                                <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-search') }}"></use>
                                            </svg>Search
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-auto ml-0 ml-lg-auto text-left text-lg-right mt-3 mt-lg-0">
                                <a href="{{ route('admin.admins.create') }}">
                                    <button class="btn btn-outline-secondary">
                                        <svg class="icon me-2">
                                            <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-user-follow') }}"></use>
                                        </svg>Add Admin
                                    </button>
                                </a>
                            </div>
                        </div>

                        <div class="card border">
                            <div class="p-3" role="tabpanel">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Activated</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($admins as $k => $admin)
                                        <tr>
                                            <td>{{ $k + $admins->firstItem() }}</td>
                                            <td>
                                                <a href="{{ route('admin.admins.edit', $admin) }}">{{ $admin->name }}</a>
                                                @if($admin->id === auth()->guard('admin')->id())
                                                    <span class="badge bg-info ms-1">You</span>
                                                @endif
                                            </td>
                                            <td>{{ $admin->email }}</td>
                                            <td>
                                                @if($admin->email_verified_at)
                                                    <span class="badge bg-success">Activated</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if((int)$admin->status === 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.admins.edit', $admin) }}"
                                                   class="btn btn-sm btn-outline-primary">Edit</a>

                                                @if($admin->id !== auth()->guard('admin')->id())
                                                    <form action="{{ route('admin.admins.destroy', $admin) }}"
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Delete this admin?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end mt-2">
                                    {{ $admins->onEachSide(5)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
