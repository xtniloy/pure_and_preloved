@extends('user.auth.layout.storefront')

@section('title', 'Email Sent')

@section('content')
    <div class="auth-shell auth-shell--single">
        <main class="auth-main auth-center">
            <a href="{{ route('home') }}" class="auth-logo" style="display:inline-block; margin-bottom: 26px;">
                <img src="{{ asset('assets/images/logo/logo.jpg.png') }}" alt="Pure &amp; Preloved" style="height:40px;">
            </a>

            <div class="auth-success-icon">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </div>

            <div class="auth-head">
                <h1>Check your inbox</h1>
                <p>
                    We've sent a verification email to
                    <strong style="color:var(--auth-ink);">{{ $user->email ?? 'your email address' }}</strong>.
                    Follow the link inside to complete the process.
                </p>
            </div>

            @include('user.auth.partials.alerts')

            <div class="auth-note">
                <strong>Didn't get the email?</strong><br>
                Check your spam folder, or wait a moment for it to arrive.
            </div>

            <div id="timer-section">
                <p class="auth-help">You can request a new email in <span id="countdown" class="auth-countdown">3:00</span></p>
            </div>

            <div id="resend-section" style="display:none;">
                <form action="{{ route('verification.send', $user) }}" method="post">
                    @csrf
                    <button type="submit" class="auth-btn auth-btn-outline auth-btn-inline" id="resend-btn">Resend email</button>
                </form>
            </div>

            <p class="auth-foot">
                <a href="{{ route('login') }}">← Back to sign in</a>
            </p>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        let timeLeft = {{ intval($remainingTime ?? 180) }};
        const countdownEl = document.getElementById('countdown');
        const timerSection = document.getElementById('timer-section');
        const resendSection = document.getElementById('resend-section');

        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            if (countdownEl) countdownEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                if (timerSection) timerSection.style.display = 'none';
                if (resendSection) resendSection.style.display = 'block';
                return;
            }
            timeLeft--;
            setTimeout(updateCountdown, 1000);
        }
        updateCountdown();

        const resendBtn = document.getElementById('resend-btn');
        if (resendBtn) {
            resendBtn.addEventListener('click', function () {
                this.disabled = true;
                this.textContent = 'Sending…';
                this.closest('form').submit();
            });
        }
    </script>
@endpush
