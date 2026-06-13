<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Set Password | Admin</title>
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
                        <h1>Set Password</h1>
                        <p class="text-body-secondary">Create a secure password for your admin account.</p>

                        <form action="{{ route('admin.save_password', ['token' => $token]) }}" method="post">
                            @csrf

                            <div class="mb-3">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control @error('password') is-invalid @enderror"
                                           type="password" name="password"
                                           placeholder="New password" required autocomplete="new-password">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Min 8 characters with uppercase, lowercase, and a number.</small>
                            </div>

                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <svg class="icon">
                                            <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use>
                                        </svg>
                                    </span>
                                    <input class="form-control @error('confirm_password') is-invalid @enderror"
                                           type="password" name="confirm_password"
                                           placeholder="Confirm password" required>
                                    @error('confirm_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button class="btn btn-primary w-100" type="submit">Set Password</button>
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
