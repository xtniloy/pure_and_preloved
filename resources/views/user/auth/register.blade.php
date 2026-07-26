@extends('user.auth.layout.storefront')

@section('title', 'Create Account')

@section('content')
    <div class="auth-shell">

        @include('user.auth.partials.aside', [
            'headline' => 'Join<br>Pure &amp; Preloved.',
            'sub' => 'Create an account to shop preloved treasures, track orders and save your favourites.',
        ])

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
                <h1>Create your account</h1>
                <p>Join us — it only takes a minute.</p>
            </div>

            @include('user.auth.partials.alerts')

            <form action="{{ route('register') }}" method="post" novalidate>
                @csrf

                <div>
                    <label for="name" class="auth-label">Full name</label>
                    <div class="auth-field">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </span>
                        <input id="name" class="auth-input" type="text" name="name"
                               value="{{ old('name') }}" placeholder="Your name" autocomplete="name" required autofocus>
                    </div>
                </div>

                <div>
                    <label for="email" class="auth-label">Email address</label>
                    <div class="auth-field">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </span>
                        <input id="email" class="auth-input" type="email" name="email"
                               value="{{ old('email') }}" placeholder="you@example.com" autocomplete="email" required>
                    </div>
                </div>

                <div>
                    <label for="phone" class="auth-label">Phone number</label>
                    <div class="auth-field">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92Z"/></svg>
                        </span>
                        <input id="phone" class="auth-input" type="tel" name="phone"
                               value="{{ old('phone') }}" placeholder="01XXXXXXXXX"
                               pattern="^(\+8801[3-9][0-9]{8})|(01[3-9][0-9]{8})$" autocomplete="tel">
                    </div>
                </div>

                <div>
                    <label for="password" class="auth-label">Password</label>
                    <div class="auth-field">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input id="password" class="auth-input" type="password" name="password"
                               placeholder="Create a password" autocomplete="new-password" required>
                        <button type="button" class="auth-toggle" data-toggle-password="password" aria-label="Show password">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="confirm_password" class="auth-label">Confirm password</label>
                    <div class="auth-field">
                        <span class="field-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </span>
                        <input id="confirm_password" class="auth-input" type="password" name="confirm_password"
                               placeholder="Repeat your password" autocomplete="new-password" required>
                        <button type="button" class="auth-toggle" data-toggle-password="confirm_password" aria-label="Show password">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                </div>

                <button class="auth-btn" type="submit">Create account</button>
            </form>

            <p class="auth-foot">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </p>
        </main>

    </div>
@endsection
