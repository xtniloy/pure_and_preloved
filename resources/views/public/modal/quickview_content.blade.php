<div class="row">
    <div class="col-md-6 col-sm-12 col-xs-12 mb-lm-100px mb-sm-30px">
        <div class="quickview-wrapper">
            <!-- slider -->
            <div class="gallery-top">
                @php
                    $images = $product->assets;
                @endphp
                @forelse($images as $asset)
                    <div class="single-slide">
                        <img class="img-responsive m-auto" src="{{ $asset->public_url }}" alt="{{ $product->name }}">
                    </div>
                @empty
                    <div class="single-slide">
                        <img class="img-responsive m-auto" src="{{ asset('assets/images/product-image/8.jpg') }}" alt="">
                    </div>
                @endforelse
            </div>
            <div class=" gallery-thumbs">
                @forelse($images as $asset)
                    <div class="single-slide">
                        <img class="img-responsive m-auto" src="{{ $asset->public_url }}" alt="{{ $product->name }}">
                    </div>
                @empty
                    <div class="single-slide">
                        <img class="img-responsive m-auto" src="{{ asset('assets/images/product-image/8.jpg') }}" alt="">
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <div class="product-details-content quickview-content">
            <h2>{{ $product->name }}</h2>
            <p class="reference">SKU:<span> {{ $product->sku }}</span></p>
            <div class="pro-details-rating-wrap">
                <div class="rating-product">
                    <i class="ion-android-star"></i>
                    <i class="ion-android-star"></i>
                    <i class="ion-android-star"></i>
                    <i class="ion-android-star"></i>
                    <i class="ion-android-star"></i>
                </div>
                <span class="read-review"><a class="reviews" href="#">Read reviews (1)</a></span>
            </div>
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
            <p class="quickview-para">{!! Str::limit(strip_tags($product->description), 200) !!}</p>
            <div class="pro-details-size-color">
                <div class="pro-details-color-wrap">
                    <span>Condition: {{ $product->condition }}</span>
                </div>
            </div>
            <div class="pro-details-quality">
                <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="cart-plus-minus">
                        <input class="cart-plus-minus-box" type="text" name="quantity" value="1" />
                    </div>
                    <div class="pro-details-cart btn-hover">
                        <button type="submit" class="add-to-cart-btn"> + Add To Cart</button>
                    </div>
                </form>
            </div>
            <div class="pro-details-wish-com">
                <div class="pro-details-wishlist">
                    <form action="{{ route('wishlist.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-link p-0">
                            <i class="ion-android-favorite-outline"></i>Add to wishlist
                        </button>
                    </form>
                </div>
                {{-- <div class="pro-details-compare">
                    <a href="#"><i class="ion-ios-shuffle-strong"></i>Add to compare</a>
                </div> --}}
            </div>
            <div class="pro-details-social-info">
                <span>Share</span>
                <div class="social-info">
                    <ul>
                        <li>
                            <a href="#"><i class="ion-social-facebook"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="ion-social-twitter"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="ion-social-google"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="ion-social-instagram"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .add-to-cart-btn {
        background-color: #242424;
        color: #fff;
        display: block;
        font-size: 14px;
        font-weight: 700;
        height: 50px;
        line-height: 50px;
        padding: 0 30px;
        text-transform: uppercase;
        border-radius: 0;
        border: none;
        transition: all .3s ease 0s;
    }
    .add-to-cart-btn:hover {
        background-color: #f6b500;
        color: #fff;
    }
</style>
