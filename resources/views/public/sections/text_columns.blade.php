{{-- Heading + text columns (SEO copy) --}}
@php
    $columns = array_values(array_filter($data['columns'] ?? []));
    $colClass = [1 => 'col-lg-12', 2 => 'col-lg-6', 3 => 'col-lg-4', 4 => 'col-lg-3'][min(max(count($columns), 1), 4)];
@endphp
<div class="greed-Jewellery-area">
    <div class="container">
        @if(!empty($data['heading']))
            <div class="common-title text-uppercase text-center mb-4">
                <h2>{!! $data['heading'] !!}</h2>
            </div>
        @endif
        <div class="row">
            @foreach($columns as $column)
                <div class="{{ $colClass }} {{ !$loop->last ? 'mb-3 mb-lg-0' : '' }}">
                    <p>{{ $column }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
