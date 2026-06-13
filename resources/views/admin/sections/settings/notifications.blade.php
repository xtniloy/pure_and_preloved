@extends('admin.layout.main')
@section('page-title')
    Notification Settings
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold">Notification Settings</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
                <li class="breadcrumb-item active">Notifications</li>
            </ol>
        </nav>
        @include('partials.notification')

        <div class="card mb-4">
            <div class="card-header">
                <strong>Who receives which notifications</strong>
                <div class="small text-medium-emphasis mt-1">Choose how each admin is alerted. <strong>Email</strong> sends a message to the admin's address; <strong>Web</strong> shows an in-app notification in the bell menu.</div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.notifications.update') }}" method="POST">
                    @csrf

                    @foreach($admins as $admin)
                        <div class="card border mb-3">
                            <div class="card-header bg-body-tertiary">
                                <strong>{{ $admin->name }}</strong>
                                <span class="text-medium-emphasis small ms-2">{{ $admin->email }}</span>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead>
                                        <tr>
                                            <th>Notification</th>
                                            <th class="text-center" style="width:120px;">Email</th>
                                            <th class="text-center" style="width:120px;">Web</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($types as $typeKey => $typeLabel)
                                            @php
                                                $setting = $admin->notificationSettings->firstWhere('type', $typeKey);
                                                $mailOn = $setting ? $setting->mail : true;  // opt-out default
                                                $webOn = $setting ? $setting->web : true;
                                            @endphp
                                            <tr>
                                                <td>{{ $typeLabel }}</td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="prefs[{{ $admin->id }}][{{ $typeKey }}][mail]"
                                                               value="1" {{ $mailOn ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-switch d-inline-block">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="prefs[{{ $admin->id }}][{{ $typeKey }}][web]"
                                                               value="1" {{ $webOn ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
@endsection
