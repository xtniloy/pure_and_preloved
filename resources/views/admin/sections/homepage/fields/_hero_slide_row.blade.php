{{-- One hero slide row. Expects: $i (index or '__IDX__'), $slide (array) --}}
<div class="js-row border rounded p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-semibold">Slide <span class="js-row-number"></span></span>
        <button type="button" class="btn btn-sm btn-outline-danger js-remove-row">Remove</button>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Background Image</label>
            @include('admin.sections.homepage.fields._image_slot', [
                'prefix' => "slides[$i]",
                'imageId' => $slide['image_id'] ?? '',
                'imageUrl' => $slide['image_url'] ?? '',
            ])
        </div>
        <div class="col-md-8">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Heading</label>
                    <textarea class="form-control" name="slides[{{ $i }}][heading]" rows="2" placeholder="Line breaks are kept on the slide">{{ $slide['heading'] ?? '' }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">Subheading</label>
                    <input type="text" class="form-control" name="slides[{{ $i }}][subheading]" value="{{ $slide['subheading'] ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Button Text</label>
                    <input type="text" class="form-control" name="slides[{{ $i }}][button_text]" value="{{ $slide['button_text'] ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Button Link</label>
                    <input type="text" class="form-control" name="slides[{{ $i }}][button_url]" value="{{ $slide['button_url'] ?? '' }}" placeholder="Leave empty to link to the shop">
                </div>
            </div>
        </div>
    </div>
</div>
