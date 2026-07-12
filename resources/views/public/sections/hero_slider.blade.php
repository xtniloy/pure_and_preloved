{{-- Hero image slider (fixed at the top of the homepage) --}}
<div class="slider-area">
    <div class="hero-slider-wrapper">
        @foreach(($data['slides'] ?? []) as $slide)
            {{-- The background is server-rendered inline so the browser can start the
                 download immediately; data-bg-image stays for main.js compatibility. --}}
            <div class="single-slide slider-height-1 bg-img d-flex" data-bg-image="{{ $slide['image_src'] ?? '' }}"
                 @if(!empty($slide['image_src'])) style="background-image:url('{{ $slide['image_src'] }}')" @endif>
                <div class="container align-self-center">
                    <div class="slider-content-1 slider-animated-{{ ($loop->index % 3) + 1 }} text-left pl-60px">
                        <h1 class="animated color-black">{!! nl2br(e($slide['heading'] ?? '')) !!}</h1>
                        @if(!empty($slide['subheading']))
                            <p class="animated color-gray">{{ $slide['subheading'] }}</p>
                        @endif
                        @if(!empty($slide['button_text']))
                            <a href="{{ !empty($slide['button_url']) ? $slide['button_url'] : route('shop.index') }}" class="shop-btn animated">{{ $slide['button_text'] }}</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
