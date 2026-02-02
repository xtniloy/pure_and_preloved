@extends('admin.layout.main')
@section('page-title')
    Product Management
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Product Management
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a>
                </li>
                <li class="breadcrumb-item active"><a href="{{route('admin.products.index')}}">Product Management</a>
                </li>
            </ol>
        </nav>
        @include('partials.notification')
        <div class="row ">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Product list</strong>
                        </div>
                        <span class="small ms-1">Total: {{$products->total()??0}}</span>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mx-2 mb-3">
                            <div class="col-auto ml-0 ml-lg-auto text-left text-lg-right mt-3 mt-lg-0 ms-auto">
                                <a href="{{route('admin.products.create')}}">
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
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">SKU</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $k=> $product)
                                        <tr class="align-middle">
                                            <th scope="row">{{$products->firstItem() + $k}}</th>
                                            <td>
                                                @if($product->main_image)
                                                    <img src="{{ route('admin.file.view', ['fileId' => $product->main_image->id]) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{$product->name}}</td>
                                            <td>{{$product->sku}}</td>
                                            <td>
                                                @if($product->categories->isNotEmpty())
                                                    {{ $product->categories->pluck('name')->join(', ') }}
                                                    <br>
                                                    <small class="text-muted">({{ $product->categories->first()->gender }})</small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($product->sale_price)
                                                    <del class="text-muted">${{ $product->price }}</del> <br>
                                                    <span class="text-danger">${{ $product->sale_price }}</span>
                                                @else
                                                    ${{ $product->price }}
                                                @endif
                                            </td>
                                            <td>
                                                @if($product->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @if($product->categories->isNotEmpty())
                                                        <a href="{{ route('product.show', ['gender' => $product->categories->first()->gender, 'category' => $product->categories->first()->slug, 'product' => $product->slug]) }}"
                                                           class="btn btn-sm btn-outline-info me-2"
                                                           target="_blank"
                                                           title="View Public Page">
                                                            <svg class="icon">
                                                                <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-eye')}}"></use>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    <a href="{{route('admin.products.edit', $product->id)}}"
                                                       class="btn btn-sm btn-outline-primary me-2">
                                                        <svg class="icon">
                                                            <use
                                                                xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-pencil')}}"></use>
                                                        </svg>
                                                    </a>
                                                    <form action="{{route('admin.products.destroy', $product->id)}}"
                                                          method="post"
                                                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <svg class="icon">
                                                                <use
                                                                    xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-trash')}}"></use>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-3">
                            {{$products->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
