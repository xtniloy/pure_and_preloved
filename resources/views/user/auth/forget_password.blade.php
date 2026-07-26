@extends('user.auth.layout.storefront')

@section('title', 'Forgot Password')

@section('content')
    <div class="auth-shell">

        @include('user.auth.partials.aside', [
            'headline' => 'Forgot your<br>password?',
            'sub' => "It happens. Enter your email and we'll send you a link to set a new one.",
        ])

        <main class="auth-main">
            <div class="auth-topbar">
                <a href="{{ route('home') }}" class="auth-logo">
                    <img src="{{ asset('assets/images/logo/logo.jpg.png') }}" alt="Pure &amp; Preloved">
                </a>
                <a href="{{ route('login') }}" class="auth-back">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Back to sign in
                </a>
            </div>

            <div class="auth-head">
                <h1>Reset password</h1>
                <p>Enter your account email to request a password reset.</p>
            </div>

            @include('user.auth.partials.alerts')

            <form action="{{ route('request_forget_password') }}" method="post" novalidate>
                @csrf

                <div>
                    <label for="email" class="auth-label">Email address</label>
                    <div class="auth-field">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </span>
                        <input id="email" class="auth-input" type="email" name="email"
                               value="{{ old('email') }}" placeholder="you@example.com"
                               autocomplete="email" required autofocus>
                    </div>
                </div>

                <button class="auth-btn" type="submit">Send reset link</button>
            </form>

            <p class="auth-foot">
                Remembered it? <a href="{{ route('login') }}">Back to sign in</a>
            </p>
        </main>

    </div>
@endsection
