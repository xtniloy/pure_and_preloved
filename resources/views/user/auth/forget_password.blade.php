@extends('user.auth.layout.main')
@section('page-title')
    Forget Password
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            @include('partials.notification')
            <div class="card mb-4 mx-4">
                <div class="card-body p-4">
                    <h1>Forgot Password?</h1>
                    <p class="text-body-secondary">Enter your email to request to set new password.</p>
                    <form method="post">
                        @csrf

                        <div class="input-group mb-3">
                        <span class="input-group-text">
                    <svg class="icon">
                      <use xlink:href="{{asset('assets/vendors/@coreui/icons/svg/free.svg#cil-envelope-open')}}"></use>
                    </svg></span>
                            <input aria-label="Email" value="{{old('email')}}" class="form-control" name="email" type="email" placeholder="Email">
                        </div>

                        <button class="btn btn-block btn-success" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
