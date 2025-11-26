@extends('user.auth.layout.main')
@section('page-title')
    User Login
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-group d-block d-md-flex row">
                @include('partials.notification')
                <div class="card col-md-7 p-4 mb-0">
                    <div class="card-body">
                        <h1>Login</h1>
                        <p class="text-body-secondary">Sign In to your account</p>
                        <form action="{{ route('auth') }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                  <svg class="icon">
                                    <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                                  </svg>
                                </span>
                                <input aria-label="Email" class="form-control" type="email" name="email" placeholder="Email" required>
                            </div>
                            <div class="input-group mb-4">
                                <span class="input-group-text">
                                  <svg class="icon"><use
                                        xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use>
                                  </svg>
                                </span>
                                <input aria-label="Password" class="form-control" type="password" name="password" placeholder="Password" required>
                            </div>

                            <div class="input-group mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="remember_me" name="remember_me" id="remember_me">
                                    <label class="form-check-label" for="remember_me">
                                        Remember me
                                    </label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-primary px-4" type="submit">Login</button>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{route('forget_password')}}" class="btn btn-link px-0" type="button">Forgot password?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card col-md-5 text-white bg-primary py-5">
                    <div class="card-body text-center">
                        <div>
                            <h2>Sign up</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua.</p>
                            <a href="{{route('registration')}}" class="btn btn-lg btn-outline-light mt-3" type="button">Register Now!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
