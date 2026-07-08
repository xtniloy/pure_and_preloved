{{-- Single product card. Used once per product; the wrapper decides slider vs stacked layout. --}}
@php
    $category = $product->categories->first();
    $gender = $category ? $category->gender : null;
    $categorySlug = $category ? $category->slug : null;
    $productUrl = $gender && $categorySlug ? route('product.show', [$gender, $categorySlug, $product->slug]) : '#';

    if ($product->thumbnailImage) {
        $imageUrl = $product->thumbnailImage->public_url;
    } elseif ($product->main_image) {
        $imageUrl = $product->main_image->public_url;
    } else {
        $imageUrl = asset('assets/images/product-image/4.jpg');
    }
    $hoverImageUrl = $product->assets[0]->public_url ?? $imageUrl;
@endphp
<article class="list-product text-center mb-30px">
    <div class="product-inner">
        <div class="img-block">
            <a href="{{ $productUrl }}" class="thumbnail">
                <img class="first-img" src="{{ $imageUrl }}" alt="{{ $product->name }}" />
                <img class="second-img" src="{{ $hoverImageUrl }}" alt="{{ $product->name }}" />
            </a>
            <div class="add-to-link">
                <ul>
                    <li>
                        <a class="quick_view" href="#" data-link-action="quickview" title="Quick view" data-bs-toggle="modal" data-bs-target="#quickview-modal" data-product-id="{{ $product->id }}">
                            <i class="lnr lnr-magnifier"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                           title="Add to Wishlist"
                           onclick="event.preventDefault(); document.getElementById('wishlist-{{ $product->id }}').submit();">
                            <i class="lnr lnr-heart"></i>
                        </a>

                        <form id="wishlist-{{ $product->id }}"
                              action="{{ route('wishlist.add') }}"
                              method="POST"
                              class="d-none">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                        </form>
                    </li>
                    <li>
                        <a href="#" title="Add to compare"><i class="lnr lnr-sync"></i></a>
                    </li>
                </ul>
            </div>
        </div>
        <ul class="product-flag">
        </ul>
        <div class="product-decs">
            <a class="inner-link" href="#"><span>Pure and Preloved</span></a>
            <h2><a href="{{ $productUrl }}" class="product-link">{{ $product->name }}</a></h2>
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
        {{-- Hidden below lg to keep the original mobile layout (no add-to-cart on mobile) --}}
        <div class="cart-btn d-none d-lg-block">
            <a href="#" class="add-to-curt"
               onclick="event.preventDefault(); document.getElementById('add-to-curt-{{ $product->id }}').submit();"
               title="Add to cart">Add to cart</a>

            <form id="add-to-curt-{{ $product->id }}"
                  action="{{ route('cart.add') }}"
                  method="POST"
                  class="d-none">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
            </form>
        </div>
    </div>
</article>
