@extends('admin.layout.main')
@section('page-title')
    User Management
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">User Management</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a>
                </li>
                <li class="breadcrumb-item active"><span data-coreui-i18n="dashboard">User Management</span>
                </li>
            </ol>
        </nav>
        @include('partials.notification')
        <!-- /.row-->
        <div class="row ">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between"><strong>User list</strong><span
                            class="small ms-1">Total: {{$users->total()??0}}</span></div>
                    <div class="card-body">
                        <div class="row align-items-center mx-2 mb-3">
                            <div class="col-xxl-6 col-lg-8 ">
                                <form class="admin-search-form">
                                    <div class="input-group">

                                        <div class="input-group">
                                            <input type="text" @if(request()->q) autofocus @endif name="q"
                                                   id="query" class="form-control"
                                                   value="{{ request()->q }}"
                                                   placeholder="Search keywords..." aria-label="Search input form">

                                            <select @if(request()->status) autofocus @endif
                                            aria-label="Select status"
                                                    class="form-control form-select search-fld-dropdown"
                                                    name="status">
                                                <option value="">Select status</option>
                                                @if(!empty(\App\Enum\General::$user_status))
                                                    @foreach(\App\Enum\General::$user_status as $k2 => $type)
                                                        <option
                                                            value="{{ $k2 }}"{{ request()->status !== null && request()->status == $k2 ? 'selected' : '' }}>
                                                            {{ $type }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            <button class="btn btn-outline-secondary"
                                                    onclick="location.href='{{route('admin.users.index')}}';"
                                                    type="reset">
                                                <svg class="icon me-2 ">
                                                    <use
                                                        xlink:href="{{asset('assets/vendors/@coreui/icons/svg/free.svg#cil-x')}}"></use>
                                                </svg>
                                                Reset
                                            </button>


                                            <button class="btn btn-outline-secondary" type="submit">
                                                <svg class="icon me-2 ">
                                                    <use
                                                        xlink:href="{{asset('assets/vendors/@coreui/icons/svg/free.svg#cil-search')}}"></use>
                                                </svg>
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-auto ml-0 ml-lg-auto text-left text-lg-right mt-3 mt-lg-0">
                                <a href="{{route('admin.users.create')}}">
                                    <button class="btn btn-outline-secondary">
                                        <svg class="icon me-2">
                                            <use
                                                xlink:href="{{asset('assets/vendors/@coreui/icons/svg/free.svg#cil-user-follow')}}"></use>
                                        </svg>
                                        Add
                                    </button>
                                </a>
                            </div>
                        </div>
                        {{--                <p class="text-body-secondary small">Using the most basic table markup, here’s how<code>.table</code>-based tables look in Bootstrap.</p>--}}

                        <div class="card border">
                            <div class=" p-3 " role="tabpanel">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Authentication</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $k=> $user)
                                        <tr>
                                            <td> {{$k + $users->firstItem() }} </td>
                                            <td>
                                                <a href="{{route('admin.users.edit',['user'=>$user])}}">{{ $user->name }}</a>
                                            </td>
                                            <td class="d-none d-md-table-cell">{{$user->email}}</td>
                                            <td>{{$user->phone}}</td>
                                            <td>
                                                <span>
                                                    Unauthenticated
                                                </span>
                                            </td>
                                            <td>
                                                <span>
                                                    {{ \App\Enum\General::$user_status[$user->status] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <div class="row align-items-center m-2">
                                        <div class="col-lg-12 text-end">
                                            <div class="d-flex justify-content-lg-end justify-content-start">
                                                {{ $users->onEachSide(5)->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



