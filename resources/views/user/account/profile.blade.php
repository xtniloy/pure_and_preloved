@extends('public.layouts.main')
@section('page-title')
    User Profile
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('partials.notification')
            <div class="card mb-4 mx-4">
                <div class="card-body p-4">
                    <h1>Profile</h1>
                    <p class="text-body-secondary">Update your details and addresses</p>
                    <form method="post" action="{{ route('account.profile.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" class="form-control" type="text" name="name" value="{{ old('name', $user->name) }}">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input id="phone" class="form-control" type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Billing Address</label>
                            <textarea id="billing_address" class="form-control" name="billing_address" rows="3">{{ old('billing_address', $user->billing_address) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Delivery Address</label>
                            <textarea id="delivery_address" class="form-control" name="delivery_address" rows="3">{{ old('delivery_address', $user->delivery_address) }}</textarea>
                        </div>

                        <button class="btn btn-success" type="submit">Save Changes</button>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary ms-2">Back to Dashboard</a>
                    </form>
                </div>
            </div>

            <div class="card mb-4 mx-4">
                <div class="card-body p-4">
                    <h2>Delete Account</h2>
                    <p class="text-danger mb-3">This action is permanent and cannot be undone.</p>
                    <form method="post" action="{{ route('account.delete') }}">
                        @csrf
                        <button class="btn btn-outline-danger" type="submit">Delete My Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

