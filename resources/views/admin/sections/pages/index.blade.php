@extends('admin.layout.main')
@section('page-title')
    Page Management
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Page Management
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a></li>
                <li class="breadcrumb-item active">Page Management</li>
            </ol>
        </nav>
        @include('partials.notification')
        <div class="row ">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Pages</strong>
                        </div>
                        <span class="small ms-1">Total: {{$pages->total()??0}}</span>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mx-2 mb-3">
                            <div class="col-auto ml-0 ml-lg-auto text-left text-lg-right mt-3 mt-lg-0 ms-auto">
                                <a href="{{route('admin.pages.create')}}">
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
                                        <th scope="col">Title</th>
                                        <th scope="col">Slug</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($pages as $k => $page)
                                        <tr class="align-middle">
                                            <th scope="row">{{$pages->firstItem() + $k}}</th>
                                            <td>{{$page->title}}</td>
                                            <td><code>{{$page->slug}}</code></td>
                                            <td>
                                                @if($page->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Disabled</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{route('pages.show', $page->slug)}}" target="_blank" class="btn btn-sm btn-outline-secondary me-2" title="View">
                                                        <svg class="icon">
                                                            <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-external-link')}}"></use>
                                                        </svg>
                                                    </a>
                                                    <a href="{{route('admin.pages.edit', $page->id)}}" class="btn btn-sm btn-outline-primary me-2">
                                                        <svg class="icon">
                                                            <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-pencil')}}"></use>
                                                        </svg>
                                                    </a>
                                                    <form action="{{route('admin.pages.destroy', $page->id)}}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No pages found.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $pages->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
