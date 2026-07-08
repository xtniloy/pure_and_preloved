{{--
    Shared shell for all "My Account" pages: breadcrumb + sidebar navigation.
    Child views extend this and fill the `account-content` section
    (plus optional `title` and `breadcrumb` sections).
--}}
@extends('public.layouts.main')

@section('title', 'My Account')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/account.css') }}">
@endpush

@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            @hasSection('breadcrumb')
                                <li><a href="{{ route('user.dashboard') }}">My Account</a></li>
                                <li>@yield('breadcrumb')</li>
                            @else
                                <li>My Account</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End -->

    <div class="account-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account._sidebar')
                </div>
                <div class="col-lg-9">
                    @include('partials.notification')
                    @yield('account-content')
                </div>
            </div>
        </div>
    </div>
@endsection
