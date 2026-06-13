<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Forgot Password | Admin</title>
    <link rel="stylesheet" href="{{ asset('panel/assets/vendors/simplebar/css/simplebar.css') }}">
    <link href="{{ asset('panel/assets/css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('panel/assets/js/config.js') }}"></script>
</head>
<body>
<div class="min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                @include('admin.partials.notification')
                <div class="card p-4">
                    <div class="card-body">
                        <h1>Forgot Password?</h1>
                        <p class="text-body-secondary">Enter your admin email to receive a password reset link.</p>

                        <form action="{{ route('admin.request_forget_password') }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text">
                                    <svg class="icon">
                                        <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-envelope-open') }}"></use>
                                    </svg>
                                </span>
                                <input class="form-control @error('email') is-invalid @enderror"
                                       type="email" name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Admin email" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-primary px-4" type="submit">Send Reset Link</button>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{ route('admin.login') }}" class="btn btn-link px-0">Back to Login</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('panel/assets/vendors/@coreui/coreui-pro/js/coreui.bundle.min.js') }}"></script>
<script src="{{ asset('panel/assets/vendors/simplebar/js/simplebar.min.js') }}"></script>
</body>
</html>
