@extends('admin.layout.main')
@section('page-title')
    Feature Products
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Feature Products
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a>
                </li>
                <li class="breadcrumb-item active"><a href="{{route('admin.featured-products.index')}}">Feature Products</a>
                </li>
            </ol>
        </nav>
        @include('partials.notification')
            <div class="row mb-3">
                <div class="col">
                    <h4>Manage Feature Products</h4>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <form method="GET" action="{{ route('admin.featured-products.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Search name or SKU</label>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Featured</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->sku }}</td>
                                        <td>${{ number_format($product->sale_price ?? $product->price, 2) }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.featured-products.toggle', $product) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="is_featured" value="{{ $product->is_featured ? 0 : 1 }}">
                                                <button type="submit" class="btn btn-sm {{ $product->is_featured ? 'btn-success' : 'btn-outline-secondary' }}">
                                                    {{ $product->is_featured ? 'Featured' : 'Mark as featured' }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No products found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div>
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

