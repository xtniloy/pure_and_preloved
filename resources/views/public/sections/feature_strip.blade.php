{{-- Horizontal strip of image cards with caption (light or dark theme) --}}
@php $dark = ($data['theme'] ?? 'light') === 'dark'; @endphp
<div class="feature-area {{ $dark ? 'jg-magazine-area bg-black' : 'mtb-50px' }}">
    @if(!empty($data['heading']))
        <div class="container">
            <div class="{{ $dark ? 'common-title mb-4' : 'section-title' }} text-center">
                <h2 class="px-3 {{ $dark ? 'text-white' : '' }}">{!! $data['heading'] !!}</h2>
            </div>
        </div>
    @endif
    <div class="feature-product-wrap container d-flex">
        @foreach(($data['items'] ?? []) as $item)
            <a href="{{ $item['url'] ?? '#' }}" class="feature-product-item d-block">
                <div class="feature-product-photo">
                    <img src="{{ $item['image_src'] ?? '' }}" alt="{{ strip_tags($item['title'] ?? '') }}">
                </div>
                @if(!empty($item['title']) || !empty($item['subtitle']))
                    <div class="feature-product-info">
                        @if(!empty($item['title']))
                            <h3>{{ $item['title'] }}</h3>
                        @endif
                        @if(!empty($item['subtitle']))
                            <span>{{ $item['subtitle'] }}</span>
                        @endif
                    </div>
                @endif
            </a>
        @endforeach
    </div>
</div>
