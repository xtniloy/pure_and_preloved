@extends('user.account.layout')

@section('title', 'Account Details')
@section('breadcrumb', 'Account Details')

@section('account-content')
    <div class="account-page-head">
        <h4>Account Details</h4>
        <p>Manage your personal information, password and account.</p>
    </div>

    {{-- Personal information --}}
    <div class="account-card mb-4">
        <div class="account-card-header">
            <div>
                <h5>Personal Information</h5>
                <p class="account-card-sub">This information is used for your orders and communication.</p>
            </div>
        </div>
        <div class="account-card-body">
            <form method="post" action="{{ route('account.profile.update') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Full Name</label>
                        <input id="name" class="form-control" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input id="phone" class="form-control" type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">Email Address</label>
                        <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        <div class="form-text">This is the email address you use to sign in.</div>
                    </div>
                </div>
                <button class="btn btn-primary mt-4" type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    {{-- Change password --}}
    <div class="account-card mb-4">
        <div class="account-card-header">
            <div>
                <h5>Change Password</h5>
                <p class="account-card-sub">Use a strong password you don't use anywhere else.</p>
            </div>
        </div>
        <div class="account-card-body">
            <form method="post" action="{{ route('account.password.update') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-12">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input id="current_password" class="form-control" type="password" name="current_password" autocomplete="current-password" required>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">New Password</label>
                        <input id="password" class="form-control" type="password" name="password" autocomplete="new-password" required>
                    </div>
                    <div class="col-md-6">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input id="confirm_password" class="form-control" type="password" name="confirm_password" autocomplete="new-password" required>
                    </div>
                    <div class="col-12">
                        <div class="form-text">At least 6 characters, including one uppercase letter, one lowercase letter and one number.</div>
                    </div>
                </div>
                <button class="btn btn-primary mt-3" type="submit">Update Password</button>
            </form>
        </div>
    </div>

    {{-- Danger zone --}}
    <div class="account-danger-zone">
        <div>
            <h6>Delete Account</h6>
            <p>Once your account is deleted, it's gone permanently. This action cannot be undone.</p>
        </div>
        <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            Delete My Account
        </button>
    </div>

    {{-- Delete confirmation modal --}}
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete your account?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    You are about to permanently delete your account. This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="post" action="{{ route('account.delete') }}" class="mb-0">
                        @csrf
                        <button class="btn btn-danger" type="submit">Yes, Delete My Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
