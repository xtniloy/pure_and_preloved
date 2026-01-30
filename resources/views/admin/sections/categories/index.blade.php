@extends('admin.layout.main')
@section('page-title')
    Category Management
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Category Management
            @if(isset($parent))
                <small class="text-muted fs-5"> > {{ $parent->name }}</small>
            @endif
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a>
                </li>
                <li class="breadcrumb-item active"><a href="{{route('admin.categories.index')}}">Category Management</a>
                </li>
                @if(isset($parent))
                    <li class="breadcrumb-item active">{{ $parent->name }}</li>
                @endif
            </ol>
        </nav>
        @include('partials.notification')
        <div class="row ">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Category list</strong>
                            @if(isset($parent))
                                <span class="badge bg-info ms-2">Parent: {{ $parent->name }}</span>
                                <a href="{{ $parent->parent_id ? route('admin.categories.index', ['parent_id' => $parent->parent_id]) : route('admin.categories.index') }}" class="btn btn-sm btn-outline-secondary ms-2">Back</a>
                            @endif
                        </div>
                        <span class="small ms-1">Total: {{$categories->total()??0}}</span>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mx-2 mb-3">
                            <div class="col-auto ml-0 ml-lg-auto text-left text-lg-right mt-3 mt-lg-0 ms-auto">
                                <a href="{{route('admin.categories.create', ['parent_id' => isset($parent) ? $parent->id : null])}}">
                                    <button class="btn btn-outline-secondary">
                                        <svg class="icon me-2">
                                            <use
                                                xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-plus')}}"></use>
                                        </svg>
                                        Add
                                    </button>
                                </a>
                            </div>
                        </div>

                        <div class="card border">
                            <div class=" p-3 " role="tabpanel">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Parent</th>
                                        <th scope="col">Gender</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($categories as $k=> $category)
                                        <tr>
                                            <th scope="row">{{$categories->firstItem() + $k}}</th>
                                            <td>
                                                @if($category->image)
                                                    <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}" style="width: 30px; height: 30px; object-fit: cover; margin-right: 5px;">
                                                @endif
                                                <a href="{{ route('admin.categories.index', ['parent_id' => $category->id]) }}" class="text-decoration-none">
                                                    {{$category->name}}
                                                </a>
                                            </td>
                                            <td>{{$category->parent ? $category->parent->name : '-'}}</td>
                                            <td>{{ucfirst($category->gender)}}</td>
                                            <td>
                                                @if($category->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('admin.categories.edit', $category->id)}}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{route('admin.categories.destroy', $category->id)}}" method="POST" style="display:inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $categories->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
