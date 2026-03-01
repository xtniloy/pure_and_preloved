@extends('admin.layout.main')
@section('page-title')
    Shipping Method Management
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Shipping Method Management
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a></li>
                <li class="breadcrumb-item active">Shipping Method Management</li>
            </ol>
        </nav>
        @include('partials.notification')
        <div class="row ">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Shipping method list</strong>
                        </div>
                        <span class="small ms-1">Total: {{$shippingMethods->total()??0}}</span>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mx-2 mb-3">
                            <div class="col-auto ml-0 ml-lg-auto text-left text-lg-right mt-3 mt-lg-0 ms-auto">
                                <a href="{{route('admin.shipping_methods.create')}}">
                                    <button class="btn btn-outline-secondary">
                                        <svg class="icon me-2">
                                            <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-plus')}}"></use>
                                        </svg>
                                        Add
                                    </button>
                                </a>
                            </div>
                        </div>

                        <div class="card border">
                            <div class="p-3" role="tabpanel">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Charge</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($shippingMethods as $k => $method)
                                        <tr class="align-middle">
                                            <th scope="row">{{$shippingMethods->firstItem() + $k}}</th>
                                            <td>{{$method->name}}</td>
                                            <td>${{number_format($method->charge, 2)}}</td>
                                            <td>
                                                @if($method->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Disabled</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{route('admin.shipping_methods.edit', $method->id)}}" class="btn btn-sm btn-outline-primary me-2">
                                                        <svg class="icon">
                                                            <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-pencil')}}"></use>
                                                        </svg>
                                                    </a>
                                                    <form action="{{route('admin.shipping_methods.destroy', $method->id)}}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <svg class="icon">
                                                                <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-trash')}}"></use>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $shippingMethods->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
