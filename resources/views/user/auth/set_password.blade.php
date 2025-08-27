@extends('user.auth.layout.main')
@section('page-title')
    Set New Password
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            @include('partials.notification')
            <div class="card mb-4 mx-4">
                <div class="card-body p-4">
                    <h1>Set password</h1>
                    <p class="text-body-secondary">Set a password to get access your account</p>

                    <form method="post">
                        @csrf
                        <div class="input-group mb-3">
                        <span class="input-group-text">
                            <svg class="icon">
                              <use xlink:href="{{asset('assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked')}}"></use>
                            </svg>
                        </span>
                            <input aria-label="Password" name="password" class="form-control" type="password" placeholder="Password">
                        </div>
                        <div class="input-group mb-4">
                        <span class="input-group-text">
                            <svg class="icon">
                              <use xlink:href="{{asset('assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked')}}"></use>
                            </svg>
                        </span>
                            <input aria-label="Repeat password" name="confirm_password" class="form-control" type="password" placeholder="Repeat password">
                        </div>
                        <button type="submit" class="btn btn-block btn-success">Save</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
