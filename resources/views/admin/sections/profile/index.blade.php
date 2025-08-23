@extends('admin.layout.main')
@section('page-title')
    Dashboard
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">Profile</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="#" data-coreui-i18n="home">Home</a>
                </li>
                <li class="breadcrumb-item active"><span data-coreui-i18n="dashboard">Profile</span>
                </li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-4 mx-4">
                    <div class="card-body p-4">
                        <h1>Profile Update</h1>
                        <p class="text-body-secondary">Update your account access</p>
                        @include('partials.notification')
                        <form method="post">
                            @csrf
                            @error('name')
                            <span class="text-danger mx-1"> {{ $message }} </span>
                            @enderror
                            <div class="input-group mb-3">
                                <span class="input-group-text"><svg class="icon"><use
                                            xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use></svg></span>
                                <input class="form-control"
                                       type="text" name="name"
                                       value="{{ old('name', $user ? $user->name : '') }}"
                                       placeholder="Name" required>
                                <br>
                            </div>

                            @error('email')
                            <span class="text-danger mx-1"> {{ $message }} </span>
                            @enderror
                            <div class="input-group mb-3">
                                <span class="input-group-text"><svg class="icon"><use
                                            xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-envelope-open') }}"></use></svg></span>
                                <input class="form-control" type="email"
                                       value="{{ old('email', $user ? $user->email : '') }}"
                                       name="email" placeholder="Email" required>
                            </div>

                            @error('password')
                            <span class="text-danger mx-1"> {{ $message }} </span>
                            @enderror
                            <div class="input-group mb-3">
                                <span class="input-group-text"><svg class="icon"><use
                                            xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use></svg></span>
                                <input class="form-control" type="password" name="password" placeholder="Password">
                            </div>

                            @error('confirm_password')
                            <span class="text-danger mx-1"> {{ $message }} </span>
                            @enderror
                            <div class="input-group mb-4">
                                <span class="input-group-text"><svg class="icon"><use
                                            xlink:href="{{ asset('assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use></svg></span>
                                <input class="form-control" type="password" name="confirm_password" placeholder="Repeat password">
                            </div>
                            <button class="btn btn-block btn-success" type="submit">Update Account</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- /.row-->
    </div>
@endsection



