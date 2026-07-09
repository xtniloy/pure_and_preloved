{{-- Full-width background-image banner with centered content --}}
@php $dark = ($data['theme'] ?? 'light') === 'dark'; @endphp
<div class="full-product-banner" style="background:url('{{ $data['image_src'] ?? '' }}');">
    <div class="container">
        <div class="product-content text-center">
            @if(!empty($data['badge']))
                <strong class="d-inline-flex py-1 px-3 fs-2 mb-2 bg-black text-white">{{ $data['badge'] }}</strong>
            @endif
            @if(!empty($data['eyebrow']))
                <p class="pb-2 {{ $dark ? 'text-black' : '' }}">{{ $data['eyebrow'] }}</p>
            @endif
            @if(!empty($data['heading']))
                <h2 class="{{ $dark ? 'text-black' : '' }}">{!! $data['heading'] !!}</h2>
            @endif
            @if(!empty($data['subheading']))
                <p class="pb-2 {{ $dark ? 'text-black' : '' }}">{{ $data['subheading'] }}</p>
            @endif
            @if(!empty($data['button_text']))
                <a href="{{ $data['button_url'] ?? '#' }}" class="show-btn d-inline-block fw-bold {{ $dark ? 'text-white' : 'bg-white text-black' }}">{{ $data['button_text'] }}</a>
            @endif
        </div>
    </div>
</div>
