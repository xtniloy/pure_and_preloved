@extends('user.account.layout')

@section('title', 'Addresses')
@section('breadcrumb', 'Addresses')

@section('account-content')
    <div class="account-page-head">
        <h4>Addresses</h4>
        <p>Keep your delivery and billing details up to date.</p>
    </div>

    <form method="post" action="{{ route('account.addresses.update') }}">
        @csrf
        <div class="row g-4">
            <div class="col-md-6 d-flex">
                <div class="account-card w-100">
                    <div class="account-card-header">
                        <h5><i class="lnr lnr-map-marker account-header-icon"></i>Delivery Address</h5>
                    </div>
                    <div class="account-card-body">
                        <label for="delivery_address" class="form-label">Where should we deliver your orders?</label>
                        <textarea id="delivery_address" class="form-control" name="delivery_address" rows="5"
                                  placeholder="Street, city, postcode, country">{{ old('delivery_address', $user->delivery_address) }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-md-6 d-flex">
                <div class="account-card w-100">
                    <div class="account-card-header">
                        <h5><i class="lnr lnr-map account-header-icon"></i>Billing Address</h5>
                    </div>
                    <div class="account-card-body">
                        <label for="billing_address" class="form-label">The address on your invoices</label>
                        <textarea id="billing_address" class="form-control" name="billing_address" rows="5"
                                  placeholder="Street, city, postcode, country">{{ old('billing_address', $user->billing_address) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-primary mt-4" type="submit">Save Addresses</button>
    </form>
@endsection
