<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Email Sent | Admin</title>
    <link rel="stylesheet" href="{{ asset('panel/assets/vendors/simplebar/css/simplebar.css') }}">
    <link href="{{ asset('panel/assets/css/style.css') }}" rel="stylesheet">
    <script src="{{ asset('panel/assets/js/config.js') }}"></script>
</head>
<body>
<div class="min-vh-100 d-flex flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                @include('admin.partials.notification')
                <div class="card p-4">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <svg class="icon" style="width:80px;height:80px;color:#2eb85c;">
                                <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-check-circle') }}"></use>
                            </svg>
                        </div>

                        <h1 class="text-success mb-3">Email Sent!</h1>
                        <p class="text-body-secondary mb-4">
                            We've sent a password reset link to <strong>{{ $admin->email }}</strong>.
                            Please check your inbox and follow the instructions.
                        </p>

                        <div class="alert alert-info mb-4">
                            <strong>Didn't receive the email?</strong><br>
                            Check your spam folder or wait a few minutes for the email to arrive.
                        </div>

                        <div class="mb-4">
                            <div id="timer-section">
                                <p class="text-body-secondary mb-3">
                                    You can request a new email in:
                                    <span id="countdown" class="fw-bold text-primary">3:00</span>
                                </p>
                            </div>

                            <div id="resend-section" style="display: none;">
                                <p class="text-body-secondary mb-3">Still haven't received the email?</p>
                                <form action="{{ route('admin.email_resend', $admin) }}" method="post" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary" id="resend-btn">
                                        <svg class="icon me-2">
                                            <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-envelope-closed') }}"></use>
                                        </svg>
                                        Resend Email
                                    </button>
                                </form>
                            </div>
                        </div>

                        <a href="{{ route('admin.login') }}" class="btn btn-primary px-4">
                            <svg class="icon me-2">
                                <use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-arrow-left') }}"></use>
                            </svg>
                            Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('panel/assets/vendors/@coreui/coreui-pro/js/coreui.bundle.min.js') }}"></script>
<script src="{{ asset('panel/assets/vendors/simplebar/js/simplebar.min.js') }}"></script>
<script>
    let timeLeft = {{ intval($remaining) }};
    const countdownEl = document.getElementById('countdown');
    const timerSection = document.getElementById('timer-section');
    const resendSection = document.getElementById('resend-section');

    function updateCountdown() {
        const m = Math.floor(timeLeft / 60);
        const s = timeLeft % 60;
        countdownEl.textContent = `${m}:${s.toString().padStart(2, '0')}`;

        if (timeLeft <= 0) {
            timerSection.style.display = 'none';
            resendSection.style.display = 'block';
            return;
        }
        timeLeft--;
        setTimeout(updateCountdown, 1000);
    }

    updateCountdown();
</script>
</body>
</html>
