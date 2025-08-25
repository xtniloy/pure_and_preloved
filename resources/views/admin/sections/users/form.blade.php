@extends('admin.layout.main')
@section('page-title')
    @if(isset($user))
        Update
    @else
        Create
    @endif User
@endsection

@php
    if(isset($user)) {
    $actionUrl = route('admin.users.update', ['user' => $user]);
    $method = 'PATCH';
    } else {
    $actionUrl = route('admin.users.store') ;
    $method = 'POST';
    }
@endphp

@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            User
            @if(isset($user))
                Update
            @else
                Create
            @endif
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a>
                </li>
                <li class="breadcrumb-item active"><span data-coreui-i18n="dashboard"><a href="{{route('admin.users.index')}}">User Management </a> </span>
                </li>
                <li class="breadcrumb-item active"><span data-coreui-i18n="dashboard">User @if(isset($user)) Update @else Create @endif</span>
                </li>
            </ol>
        </nav>

        <!-- /.row-->

{{--        <div class="container-fluid p-0">--}}
            <div class="row ">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="card-title mb-0">
                                @if(isset($user))
                                    Update
                                @else
                                    Create
                                @endif
                                User
                            </h5>

                            @if(isset($user))
                                <div class="mx-4 text-right">
                                    @if($user->isVerified)
                                        <span class="mx-2">Verified by: <b>{{ $user->verifiedBy->name }}</b></span>
                                    @endif

                                        <a class="btn btn-{{$user->isVerified?'danger':'success'}}"
                                           href="{{ route('admin.users.verification_toggle',['user' => $user]) }}" role="button">
                                            @if($user->isVerified)
                                                Revoke Verification
                                            @else
                                                Set Verified
                                            @endif
                                        </a>

                                    @if($user->email_verified_at == null)
                                            <a class="btn btn-primary" href="{{ route('admin.users.email.resend',['user' => $user]) }}" role="button">Resend Email</a>
                                    @endif

                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            @include('partials.notification')

                            <form action="{{ $actionUrl }}" enctype="multipart/form-data" method="post">
                                @method($method)
                                @csrf

                                <div class="row mb">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="name">Full Name<b class="text-danger">*</b></label>
                                                    <input type="text" required
                                                           class="form-control @error('name')border-danger @enderror"
                                                           id="name" name="name" placeholder="Full name"
                                                           value="{{ old('name', isset($user) ? $user->name : '') }}">
                                                    @error('name')
                                                    <span class="text-danger mx-1"> {{ $message }} </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="email">Email<b class="text-danger">*</b></label>
                                                    <input type="email" required
                                                           class="form-control @error('email')border-danger @enderror"
                                                           id="email" name="email" placeholder="Email"
                                                           value="{{ old('email', isset($user) ? $user->email : '') }}">
                                                    @error('email')
                                                    <span class="text-danger mx-1"> {{ $message }} </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="phone">Phone<b class="text-danger">*</b></label>
                                                    <input type="number" required
                                                           class="form-control @error('phone')border-danger @enderror"
                                                           id="phone" name="phone" placeholder="Phone number"
                                                           value="{{ old('phone', isset($user) ? $user->phone : '') }}">
                                                    @error('phone')
                                                    <span class="text-danger mx-1"> {{ $message }} </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select id="status" name="status"
                                                            {{!isset($user)? 'disabled':''}}
                                                            class="form-control">
                                                        @if(!empty(\App\Enum\General::$user_status))
                                                            @foreach(\App\Enum\General::$user_status as $key=> $status)
                                                                <option value="{{ $key }}"
                                                                    {{ old('status', isset($user) ? $user->status : '') == $key ? 'selected' : '' }}>
                                                                    {{ $status }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    @error('status')
                                                    <span class="text-danger mx-1"> {{ $message }} </span>
                                                    @enderror
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Save changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
{{--        </div>--}}
    </div>
@endsection



