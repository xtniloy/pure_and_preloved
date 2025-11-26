@extends('user.auth.layout.main')
@section('page-title')
    Email Success
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card-group d-block d-md-flex row">
                @include('partials.notification')

                <div class="card col-md-12 p-4 mb-0">
                    <div class="card-body text-center">
                        <!-- Success Icon -->
                        <div class="mb-4">
                            <svg class="icon icon-5xl text-success" style="width: 80px; height: 80px;">
                                <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-check-circle')}}"></use>
                            </svg>
                        </div>

                        <!-- Success Message -->
                        <h1 class="text-success mb-3">Email Sent Successfully!</h1>
                        <p class="text-body-secondary mb-4">
                            We've sent a verification email to your email address <strong>{{$user->email??""}}</strong>.
                            Please check your inbox and follow the instructions to complete the process.
                        </p>

                        <!-- Email Info -->
                        <div class="alert alert-info mb-4">
                            <strong>Didn't receive the email?</strong><br>
                            Check your spam folder or wait a few minutes for the email to arrive.
                        </div>

                        <!-- Timer and Resend Button -->
                        <div class="mb-4">
                            <div id="timer-section">
                                <p class="text-body-secondary mb-3">
                                    You can request a new email in: <span id="countdown" class="fw-bold text-primary">3:00</span>
                                </p>
                            </div>

                            <div id="resend-section" style="display: none;">
                                <p class="text-body-secondary mb-3">
                                    Still haven't received the email?
                                </p>
                                <form action="{{route('verification.send',$user)}}" method="post" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary" id="resend-btn">
                                        <svg class="icon me-2">
                                            <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-envelope-closed')}}"></use>
                                        </svg>
                                        Resend Email
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row justify-content-center">
                            <div class="col-auto">
                                <a href="{{route('login')}}" class="btn btn-primary px-4">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-arrow-left')}}"></use>
                                    </svg>
                                    Back to Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        // 3-minute countdown timer (180 seconds)
        let timeLeft = {{intval($remainingTime)}};
        const countdownElement = document.getElementById('countdown');
        const timerSection = document.getElementById('timer-section');
        const resendSection = document.getElementById('resend-section');

        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                // Hide timer, show resend button
                timerSection.style.display = 'none';
                resendSection.style.display = 'block';
                return;
            }

            timeLeft--;
            setTimeout(updateCountdown, 1000);
        }

        // Start the countdown when page loads
        updateCountdown();

        // Handle resend button click
        document.getElementById('resend-btn').addEventListener('click', function(e) {
            // Disable button temporarily to prevent spam
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Sending...';
            this.closest('form').submit();
            // Re-enable after form submission (you can modify this based on your needs)
            setTimeout(() => {
                this.disabled = false;
                this.innerHTML = '<svg class="icon me-2"><use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-envelope-closed')}}"></use></svg>Resend Email';

            }, 3000);
        });

        // Header scroll effect (from original)
        (() => {
            const header = document.querySelector('header.header');
            document.addEventListener('scroll', () => {
                if (header) {
                    header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
                }
            });
        })();
    </script>

@endpush
