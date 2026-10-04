{{-- Featured products slider (products flagged in Admin > Featured Products) --}}
@php($sectionProducts = $featuredProducts->take(\App\Models\Product::featuredLimit($data ?? [])))
@if($sectionProducts->isNotEmpty())
    <div class="feature-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title text-center">
                                <h2 class="px-3">{!! $data['heading'] ?? '' !!}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="feature-slider-wrapper slider-nav-style-1">
                        @foreach($sectionProducts as $product)
                            <div class="slider-single-item">
                                @include('public.partials.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
