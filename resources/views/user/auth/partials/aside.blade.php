{{--
    Brand panel shown on the left of the auth split-screen.
    Pass $headline and $sub for page-specific copy.
--}}
<aside class="auth-aside">
    <div class="auth-aside-brand">Pure &amp; Preloved</div>

    <div>
        <h2 class="auth-aside-headline">{!! $headline !!}</h2>
        <p class="auth-aside-sub">{{ $sub }}</p>
    </div>

    <ul class="auth-points">
        <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            Authenticated, quality-checked pieces
        </li>
        <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            Secure checkout &amp; order tracking
        </li>
        <li>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            Save your favourites for later
        </li>
    </ul>
</aside>
