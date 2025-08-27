@extends('user.auth.layout.main')
@section('page-title')
    User Registration
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            @include('partials.notification')
            <div class="card mb-4 mx-4">
                <div class="card-body p-4">
                    <h1>Register</h1>
                    <p class="text-body-secondary">Create your account</p>
                    <form method="post">
                        @csrf
                        <div class="input-group mb-3">
                        <span class="input-group-text">
                            <svg class="icon">
                              <use xlink:href="{{asset('assets/vendors/@coreui/icons/svg/free.svg#cil-user')}}"></use>
                            </svg>
                        </span>

                            <input aria-label="Name" class="form-control" type="text" value="{{old('name')}}" name="name" placeholder="Name">
                        </div>

                        <div class="input-group mb-3">
                        <span class="input-group-text">
                    <svg class="icon">
                      <use xlink:href="{{asset('assets/vendors/@coreui/icons/svg/free.svg#cil-envelope-open')}}"></use>
                    </svg></span>
                            <input aria-label="Email" value="{{old('email')}}" class="form-control" name="email" type="email" placeholder="Email">
                        </div>

                        <div class="input-group mb-3">
                        <span class="input-group-text">
                    <svg class="icon">
                      <use xlink:href="{{asset('assets/vendors/@coreui/icons/svg/free.svg#cil-phone')}}"></use>
                    </svg></span>
                            <input aria-label="Phone" class="form-control" name="phone"
                                   pattern="^(\+8801[3-9][0-9]{8})|(01[3-9][0-9]{8})$"
                                   value="{{old('phone')}}"
                                   type="tel" placeholder="Phone number">
                        </div>

                        <div class="input-group mb-3">
                        <span class="input-group-text">
                    <svg class="icon">
                      <use xlink:href="{{asset('assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked')}}"></use>
                    </svg></span>
                            <input aria-label="Password" name="password" class="form-control" type="password" placeholder="Password">
                        </div>

                        <div class="input-group mb-4">
                        <span class="input-group-text">
                    <svg class="icon">
                      <use xlink:href="{{asset('assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked')}}"></use>
                    </svg></span>
                            <input class="form-control" name="confirm_password" type="password" aria-label="Repeat password" placeholder="Repeat password">
                        </div>

                        <button class="btn btn-block btn-success" type="submit">Create Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
