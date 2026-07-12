{{-- Image banner grid: optional heading + 2-4 image cards with overlay text --}}
@php
    $items = $data['items'] ?? [];
    $count = max(count($items), 1);
    $colClass = [1 => 'col-lg-12', 2 => 'col-lg-6', 3 => 'col-lg-4', 4 => 'col-lg-3'][min($count, 4)];
    $titleTag = in_array($data['title_tag'] ?? 'h4', ['h3', 'h4', 'h5', 'h6'], true) ? $data['title_tag'] : 'h4';
    $rounded = !empty($data['image_rounded']);
@endphp
<div class="module-featured-area {{ ($data['background'] ?? 'white') === 'grey' ? 'shop-gift-area' : 'mtb-50px' }}">
    <div class="container">
        @if(!empty($data['heading']) || !empty($data['subheading']))
            <div class="common-title text-center mb-4">
                @if(!empty($data['heading']))
                    <h2>{!! $data['heading'] !!}</h2>
                @endif
                @if(!empty($data['subheading']))
                    <p>{!! $data['subheading'] !!}</p>
                @endif
            </div>
        @endif
        <div class="row">
            @foreach($items as $item)
                @php $light = ($item['text_style'] ?? 'dark') === 'light'; @endphp
                <div class="{{ $colClass }} {{ !$loop->last ? 'mb-3 mb-lg-0' : '' }}">
                    <a href="{{ $item['url'] ?? '#' }}" class="module-featured-item">
                        <img @if($rounded) class="rounded-1" @endif src="{{ $item['image_src'] ?? '' }}" alt="{{ strip_tags($item['title'] ?? '') }}" loading="lazy" decoding="async">
                        @if(!empty($item['title']) || !empty($item['subtitle']) || !empty($item['button_text']))
                            <div class="module-feature-info">
                                @if(!empty($item['title']))
                                    <{{ $titleTag }} class="mb-2 text-center {{ $light ? 'text-white' : 'text-black' }}">{!! $item['title'] !!}</{{ $titleTag }}>
                                @endif
                                @if(!empty($item['subtitle']))
                                    <span>{{ $item['subtitle'] }}</span>
                                @endif
                                @if(!empty($item['button_text']))
                                    <button class="show-btn fw-bold {{ $light ? 'bg-white text-black' : '' }}">{{ $item['button_text'] }}</button>
                                @endif
                            </div>
                        @endif
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
