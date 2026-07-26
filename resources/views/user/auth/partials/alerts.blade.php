{{-- Validation errors + flashed session messages, styled for the auth screens. --}}
@if (isset($errors) && $errors->any())
    <div class="auth-alert auth-alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@foreach (['success' => 'auth-alert-success', 'error' => 'auth-alert-danger', 'warning' => 'auth-alert-danger', 'info' => 'auth-alert-info', 'message' => 'auth-alert-info'] as $key => $cls)
    @if (session($key))
        <div class="auth-alert {{ $cls }}">{!! session($key) !!}</div>
    @endif
@endforeach
