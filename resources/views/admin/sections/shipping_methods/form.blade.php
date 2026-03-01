@extends('admin.layout.main')
@section('page-title')
    @if(isset($shippingMethod))
        Update
    @else
        Create
    @endif Shipping Method
@endsection

@php
    if(isset($shippingMethod)) {
        $actionUrl = route('admin.shipping_methods.update', ['shipping_method' => $shippingMethod]);
        $method = 'PATCH';
    } else {
        $actionUrl = route('admin.shipping_methods.store') ;
        $method = 'POST';
    }
@endphp

@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Shipping Method
            @if(isset($shippingMethod))
                Update
            @else
                Create
            @endif
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{route('admin.shipping_methods.index')}}">Shipping Method Management</a></li>
                <li class="breadcrumb-item active">@if(isset($shippingMethod)) Update @else Create @endif</li>
            </ol>
        </nav>

        <div class="row ">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">
                            @if(isset($shippingMethod))
                                Update
                            @else
                                Create
                            @endif
                            Shipping Method
                        </h5>
                    </div>
                    <div class="card-body">
                        @include('partials.notification')

                        <form action="{{ $actionUrl }}" method="post">
                            @method($method)
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($shippingMethod) ? $shippingMethod->name : '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="charge" class="form-label">Charge <b class="text-danger">*</b></label>
                                    <input type="number" step="0.01" class="form-control @error('charge') is-invalid @enderror" id="charge" name="charge" value="{{ old('charge', isset($shippingMethod) ? $shippingMethod->charge : '') }}" required>
                                    @error('charge')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', isset($shippingMethod) ? $shippingMethod->status : 1) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="{{ route('admin.shipping_methods.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
