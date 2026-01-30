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
                                <button type="submit" form="orderForm" class="btn btn-outline-primary ms-2">
                                    <svg class="icon me-2">
                                        <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-save')}}"></use>
                                    </svg>
                                    Save Order
                                </button>
                            </div>
                        </div>

                        <div class="card border">
                            <div class=" p-3 " role="tabpanel">
                                <form action="{{ route('admin.categories.update_order') }}" method="POST" id="orderForm">
                                    @csrf
                                <table class="table table-hover" id="sortable-table">
                                    <thead>
                                    <tr>
                                        <th scope="col" style="width: 50px;"></th>
                                        <th scope="col">#</th>
                                        <th scope="col" style="width: 100px;">Order</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Parent</th>
                                        <th scope="col">Gender</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="category-list">
                                    @foreach($categories as $k=> $category)
                                        <tr class="align-middle" data-id="{{ $category->id }}">
                                            <td class="cursor-grab text-center">
                                                <svg class="icon text-secondary">
                                                    <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-cursor-move')}}"></use>
                                                </svg>
                                            </td>
                                            <th scope="row">{{$categories->firstItem() + $k}}</th>
                                            <td>
                                                <input type="hidden" name="categories[{{ $k }}][id]" value="{{ $category->id }}">
                                                <input type="number" name="categories[{{ $k }}][sort_order]" value="{{ $category->sort_order }}" class="form-control form-control-sm sort-order-input" style="width: 80px;" readonly>
                                            </td>
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
                                                {{-- Separate delete button to avoid nested forms --}}
                                                <button type="button" class="btn btn-sm btn-danger" onclick="if(confirm('Are you sure?')) { document.getElementById('delete-form-{{$category->id}}').submit(); }">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                </form>
                                {{-- Hidden delete forms outside the main form --}}
                                @foreach($categories as $category)
                                    <form id="delete-form-{{$category->id}}" action="{{route('admin.categories.destroy', $category->id)}}" method="POST" style="display:none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endforeach
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

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.getElementById('category-list');
            var sortable = Sortable.create(el, {
                handle: '.cursor-grab', // Drag handle selector within list items
                animation: 150,
                onEnd: function (evt) {
                    updateSortOrders();
                }
            });

            function updateSortOrders() {
                var rows = document.querySelectorAll('#category-list tr');
                rows.forEach(function (row, index) {
                    var input = row.querySelector('.sort-order-input');
                    if (input) {
                        input.value = index + 1; // Start order from 1
                    }
                });
            }
        });
    </script>
    <style>
        .cursor-grab {
            cursor: grab;
        }
        .cursor-grab:active {
            cursor: grabbing;
        }
    </style>
@endpush
