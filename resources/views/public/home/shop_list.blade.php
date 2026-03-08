@extends('public.layouts.main')
@section('title')
    Shop
@endsection
@section('content')

    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Shop</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->
    <!-- Shop Category Area End -->
    <div class="shop-category-area mt-30px">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 order-lg-last col-md-12 order-md-first">
                    <!-- Shop Top Area Start -->
                    <div class="shop-top-bar d-flex">
                        <!-- Left Side start -->
                        <div class="shop-tab nav d-flex">
                            <a class="active" href="#shop-1" data-bs-toggle="tab">
                                <i class="fa fa-th"></i>
                            </a>
                            <a href="#shop-2" data-bs-toggle="tab">
                                <i class="fa fa-list"></i>
                            </a>
                            <p>There Are {{ $products->total() }} Products.</p>
                        </div>
                        <!-- Left Side End -->
                        <!-- Right Side Start -->
                        <div class="select-shoing-wrap d-flex">
                            <div class="shot-product">
                                <p>Sort By:</p>
                            </div>
                            <div class="shop-select">
                                <select id="sort-select" onchange="filterProducts()">
                                    <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Sort by newness</option>
                                    <option value="name_asc" {{ $sort == 'name_asc' ? 'selected' : '' }}>A to Z</option>
                                    <option value="name_desc" {{ $sort == 'name_desc' ? 'selected' : '' }}>Z to A</option>
                                    <option value="price_low" {{ $sort == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ $sort == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                </select>
                            </div>
                        </div>
                        <!-- Right Side End -->
                    </div>
                    <!-- Shop Top Area End -->

                    <!-- Shop Bottom Area Start -->
                    <div class="shop-bottom-area mt-35">
                        <!-- Shop Tab Content Start -->
                        <div class="tab-content jump">
                            <!-- Tab One Start -->
                            <div id="shop-1" class="tab-pane active">
                                <div class="row m-0">
                                    @forelse($products as $product)
                                        @php
                                            $category = $product->categories->first();
                                            $gender = $category ? $category->gender : 'unisex';
                                            $categorySlug = $category ? $category->slug : 'all';
                                            $imageUrl = $product->thumbnail_image_id ? $product->thumbnailImage->public_url : asset('assets/images/product-image/8.jpg');
                                        @endphp
                                        <div class="mb-30px col-md-4 col-sm-6  p-0">
                                            <div class="slider-single-item">
                                                <!-- Single Item -->
                                                <article class="list-product p-0 text-center">
                                                    <div class="product-inner">
                                                        <div class="img-block">
                                                            <a href="{{ route('product.show', [$gender, $categorySlug, $product->slug]) }}" class="thumbnail">
                                                                <img class="first-img" src="{{ $imageUrl }}" alt="{{ $product->name }}" />
                                                                <img class="second-img" src="{{ $imageUrl }}" alt="{{ $product->name }}" />
                                                            </a>
                                                            <div class="add-to-link">
                                                                <ul>
                                                                    <li>
                                                                        <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal" data-product-id="{{ $product->id }}">
                                                                            <i class="lnr lnr-magnifier"></i>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#" onclick="event.preventDefault(); document.getElementById('wishlist-{{ $product->id }}').submit();" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                                        <form id="wishlist-{{ $product->id }}" action="{{ route('wishlist.add') }}" method="POST" class="d-none">@csrf<input type="hidden" name="product_id" value="{{ $product->id }}"></form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="product-decs">
                                                            <a class="inner-link" href="{{ route('shop.index', ['category' => $categorySlug]) }}"><span>{{ $category ? strtoupper($category->name) : 'JEWELRY' }}</span></a>
                                                            <h2><a href="{{ route('product.show', [$gender, $categorySlug, $product->slug]) }}" class="product-link">{{ $product->name }}</a></h2>
                                                            <div class="pricing-meta">
                                                                <ul>
                                                                    @if($product->sale_price)
                                                                        <li class="old-price">${{ number_format($product->price, 2) }}</li>
                                                                        <li class="current-price">${{ number_format($product->sale_price, 2) }}</li>
                                                                    @else
                                                                        <li class="current-price">${{ number_format($product->price, 2) }}</li>
                                                                    @endif
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <div class="cart-btn">
                                                            <a href="#" class="add-to-curt" onclick="event.preventDefault(); document.getElementById('add-to-cart-{{ $product->id }}').submit();" title="Add to cart">Add to cart</a>
                                                            <form id="add-to-cart-{{ $product->id }}" action="{{ route('cart.add') }}" method="POST" class="d-none">@csrf<input type="hidden" name="product_id" value="{{ $product->id }}"><input type="hidden" name="quantity" value="1"></form>
                                                        </div>
                                                    </div>
                                                </article>
                                                <!-- Single Item -->
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center p-5">
                                            <h4>No products found matching your criteria.</h4>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <!-- Tab One End -->
                            <!-- Tab Two Start -->
                            <div id="shop-2" class="tab-pane">
                                @foreach($products as $product)
                                    @php
                                        $category = $product->categories->first();
                                        $gender = $category ? $category->gender : 'unisex';
                                        $categorySlug = $category ? $category->slug : 'all';
                                        $imageUrl = $product->thumbnail_image_id ? $product->thumbnailImage->public_url : asset('assets/images/product-image/8.jpg');
                                    @endphp
                                    <div class="shop-list-wrap mb-30px scroll-zoom">
                                        <div class="slider-single-item">
                                            <div class="row list-product m-0px">
                                                <div class="col-md-12 padding-0px product-inner">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 p-0">
                                                            <div class="left-img">
                                                                <div class="img-block">
                                                                    <a href="{{ route('product.show', [$gender, $categorySlug, $product->slug]) }}" class="thumbnail">
                                                                        <img class="first-img" src="{{ $imageUrl }}" alt="{{ $product->name }}" />
                                                                        <img class="second-img" src="{{ $imageUrl }}" alt="{{ $product->name }}" />
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 p-0">
                                                            <div class="product-desc-wrap">
                                                                <div class="product-decs">
                                                                    <h2><a href="{{ route('product.show', [$gender, $categorySlug, $product->slug]) }}" class="product-link">{{ $product->name }}</a></h2>
                                                                    <a class="inner-link" href="{{ route('shop.index', ['category' => $categorySlug]) }}"><span>{{ $category ? strtoupper($category->name) : 'JEWELRY' }}</span></a>
                                                                    <div class="product-intro-info">
                                                                        <p>{{ Str::limit(strip_tags($product->description), 150) }}</p>
                                                                    </div>
                                                                </div>
                                                                <div class="box-inner d-flex">
                                                                    <div class="align-self-center">
                                                                        <div class="pricing-meta">
                                                                            <ul>
                                                                                @if($product->sale_price)
                                                                                    <li class="old-price">${{ number_format($product->price, 2) }}</li>
                                                                                    <li class="current-price">${{ number_format($product->sale_price, 2) }}</li>
                                                                                @else
                                                                                    <li class="current-price">${{ number_format($product->price, 2) }}</li>
                                                                                @endif
                                                                            </ul>
                                                                        </div>
                                                                        <div class="cart-btn">
                                                                            <a href="#" class="add-to-curt" onclick="event.preventDefault(); document.getElementById('add-to-cart-list-{{ $product->id }}').submit();" title="Add to cart">Add to cart</a>
                                                                            <form id="add-to-cart-list-{{ $product->id }}" action="{{ route('cart.add') }}" method="POST" class="d-none">@csrf<input type="hidden" name="product_id" value="{{ $product->id }}"><input type="hidden" name="quantity" value="1"></form>
                                                                        </div>
                                                                        <div class="add-to-link">
                                                                            <ul>
                                                                                <li>
                                                                                    <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#exampleModal" data-product-id="{{ $product->id }}">
                                                                                        <i class="lnr lnr-magnifier"></i>
                                                                                    </a>
                                                                                </li>
                                                                                <li>
                                                                                    <a href="#" onclick="event.preventDefault(); document.getElementById('wishlist-list-{{ $product->id }}').submit();" title="Add to Wishlist"><i class="lnr lnr-heart"></i></a>
                                                                                    <form id="wishlist-list-{{ $product->id }}" action="{{ route('wishlist.add') }}" method="POST" class="d-none">@csrf<input type="hidden" name="product_id" value="{{ $product->id }}"></form>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Tab Two End -->
                        </div>
                        <!-- Shop Tab Content End -->
                        <!--  Pagination Area Start -->
                        <div class="pro-pagination-style text-center mtb-50px">
                            {{ $products->links('vendor.pagination.custom') }}
                        </div>
                        <!--  Pagination Area End -->
                    </div>
                    <!-- Shop Bottom Area End -->
                </div>
                <!-- Sidebar Area Start -->
                <div class="col-lg-3 order-lg-first col-md-12 order-md-last mb-md-60px mb-lm-60px">
                    <div class="shop-sidebar-wrap">
                        <div class="sidebar-widget mb-30px">
                            <h3 class="sidebar-title"><span>Categories</span></h3>
                            <div class="accordion" id="accordionExample">
                                @foreach($categories as $category)
                                    <div class="card">
                                        <div class="card-header" id="heading-{{ $category->id }}">
                                            <a href="{{ route('shop.index', ['category' => $category->slug, 'gender' => $activeGender]) }}" 
                                               class="{{ request('category') == $category->slug ? '' : 'collapsed' }}"
                                               data-bs-toggle="{{ $category->children->count() ? 'collapse' : '' }}" 
                                               data-bs-target="#collapse-{{ $category->id }}">
                                                {{ $category->name }}
                                            </a>
                                        </div>

                                        @if($category->children->count())
                                            <div id="collapse-{{ $category->id }}" class="collapse {{ request('category') == $category->slug || in_array(request('category'), $category->children->pluck('slug')->toArray()) ? 'show' : '' }}" aria-labelledby="heading-{{ $category->id }}" data-bs-parent="#accordionExample">
                                                <div class="card-body">
                                                    <ul class="category-list">
                                                        @foreach($category->children as $child)
                                                            <li><a href="{{ route('shop.index', ['category' => $child->slug, 'gender' => $activeGender]) }}" class="{{ request('category') == $child->slug ? 'active text-primary' : '' }}">{{ $child->name }}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Sidebar single item -->
                        <div class="sidebar-widget-group mt-20">
                            <h3 class="sidebar-title m-0"><span>Filter By</span></h3>
                            
                            <!-- Filter by Condition -->
                            <div class="sidebar-widget mt-30">
                                <h4 class="pro-sidebar-title">Condition</h4>
                                <div class="sidebar-widget-list">
                                    <ul>
                                        @foreach($conditions as $condition)
                                            <li>
                                                <div class="sidebar-widget-list-left">
                                                    <input type="checkbox" class="condition-filter" value="{{ $condition }}" {{ request('condition') == $condition ? 'checked' : '' }} onchange="filterProducts()" /> 
                                                    <a href="#">{{ ucfirst($condition) }}</a>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="sidebar-widget mt-20">
                                <h4 class="pro-sidebar-title">Price</h4>
                                <div class="price-filter mt-10">
                                    <div class="price-slider-amount">
                                        <input type="text" id="amount" name="price" placeholder="Add Your Price" readonly />
                                    </div>
                                    <div id="slider-range"></div>
                                </div>
                            </div>

                            <!-- Filter by Tags -->
                            <div class="sidebar-widget mt-30">
                                <h4 class="pro-sidebar-title">Tags</h4>
                                <div class="sidebar-widget-tag mt-20">
                                    <ul>
                                        @foreach($tags as $tag)
                                            <li>
                                                <a href="{{ route('shop.index', array_merge(request()->query(), ['tag' => $tag])) }}" 
                                                   class="{{ request('tag') == $tag ? 'active text-primary border-primary' : '' }}">
                                                   {{ $tag }}
                                                </a>
                                            </li>
                                        @endforeach
                                        @if(request('tag'))
                                            <li><a href="{{ route('shop.index', request()->except('tag')) }}" class="text-danger border-danger">Clear Tag</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shop Category Area End -->

    <form id="filter-form" action="{{ route('shop.index') }}" method="GET" class="d-none">
        <input type="hidden" name="gender" value="{{ $activeGender }}">
        <input type="hidden" name="category" value="{{ request('category') }}">
        <input type="hidden" name="condition" id="filter-condition" value="{{ request('condition') }}">
        <input type="hidden" name="min_price" id="filter-min-price" value="{{ request('min_price', $minPrice) }}">
        <input type="hidden" name="max_price" id="filter-max-price" value="{{ request('max_price', $maxPrice) }}">
        <input type="hidden" name="sort" id="filter-sort" value="{{ $sort }}">
        <input type="hidden" name="tag" value="{{ request('tag') }}">
        <input type="hidden" name="q" value="{{ request('q') }}">
    </form>

@endsection

@push('scripts')
<script>
    function filterProducts() {
        var condition = $('.condition-filter:checked').val() || '';
        var sort = $('#sort-select').val();
        
        $('#filter-condition').val(condition);
        $('#filter-sort').val(sort);
        
        // Price values are updated by the slider's slide/change event
        
        $('#filter-form').submit();
    }

    $(document).ready(function() {
        var minVal = parseInt("{{ request('min_price', $minPrice) }}");
        var maxVal = parseInt("{{ request('max_price', $maxPrice) }}");
        var absMin = parseInt("{{ $minPrice }}");
        var absMax = parseInt("{{ $maxPrice }}");

        $("#slider-range").slider({
            range: true,
            min: absMin,
            max: absMax,
            values: [minVal, maxVal],
            slide: function(event, ui) {
                $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                $('#filter-min-price').val(ui.values[0]);
                $('#filter-max-price').val(ui.values[1]);
            },
            change: function(event, ui) {
                filterProducts();
            }
        });
        $("#amount").val("$" + $("#slider-range").slider("values", 0) + " - $" + $("#slider-range").slider("values", 1));
    });
</script>
@endpush

