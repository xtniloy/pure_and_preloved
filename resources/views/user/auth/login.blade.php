@extends('user.auth.layout.storefront')

@section('title', 'Login')

@section('content')
    <div class="auth-shell">

        {{-- Brand side --}}
        @include('user.auth.partials.aside', [
            'headline' => 'Welcome back to<br>timeless finds.',
            'sub' => 'Sign in to track orders, manage your wishlist and check out faster.',
        ])

        {{-- Form side --}}
        <main class="auth-main">
            <div class="auth-topbar">
                <a href="{{ route('home') }}" class="auth-logo">
                    <img src="{{ asset('assets/images/logo/logo.jpg.png') }}" alt="Pure &amp; Preloved">
                </a>
                <a href="{{ route('home') }}" class="auth-back">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Back to store
                </a>
            </div>

            <div class="auth-head">
                <h1>Sign in</h1>
                <p>Enter your details to access your account.</p>
            </div>

            @include('user.auth.partials.alerts')

            <form action="{{ route('auth') }}" method="post" novalidate>
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

                <div>
                    <label for="password" class="auth-label">Password</label>
                    <div class="auth-field">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input id="password" class="auth-input" type="password" name="password"
                               placeholder="Enter your password" autocomplete="current-password" required>
                        <button type="button" class="auth-toggle" data-toggle-password="password" aria-label="Show password">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div class="auth-row">
                    <label class="auth-check">
                        <input type="checkbox" value="remember_me" name="remember_me">
                        Remember me
                    </label>
                    <a href="{{ route('forget_password') }}" class="auth-link">Forgot password?</a>
                </div>

                <button class="auth-btn" type="submit">Sign in</button>
            </form>

            <p class="auth-foot">
                New customer? <a href="{{ route('registration') }}">Create an account</a>
            </p>
        </main>

    </div>
@endsection
