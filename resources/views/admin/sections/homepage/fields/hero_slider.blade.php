@php $slides = array_values(old('slides', $data['slides'] ?? [[]])); @endphp
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">Slides</h6>
        <button type="button" class="btn btn-sm btn-primary text-white js-add-row" data-repeater="slides-repeater">+ Add Slide</button>
    </div>
    <div class="card-body js-repeater" id="slides-repeater" data-next-index="{{ count($slides) }}">
        <div class="js-rows">
            @foreach($slides as $i => $slide)
                @include('admin.sections.homepage.fields._hero_slide_row', ['i' => $i, 'slide' => $slide])
            @endforeach
        </div>
        <template class="js-row-template">
            @include('admin.sections.homepage.fields._hero_slide_row', ['i' => '__IDX__', 'slide' => []])
        </template>
    </div>
</div>
