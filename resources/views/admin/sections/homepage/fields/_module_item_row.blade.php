{{-- One image-banner-grid item row. Expects: $i (index or '__IDX__'), $item (array) --}}
<div class="js-row border rounded p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-semibold">Banner <span class="js-row-number"></span></span>
        <button type="button" class="btn btn-sm btn-outline-danger js-remove-row">Remove</button>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Image</label>
            @include('admin.sections.homepage.fields._image_slot', [
                'prefix' => "items[$i]",
                'imageId' => $item['image_id'] ?? '',
                'imageUrl' => $item['image_url'] ?? '',
            ])
        </div>
        <div class="col-md-8">
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Title</label>
                    <input type="text" class="form-control" name="items[{{ $i }}][title]" value="{{ $item['title'] ?? '' }}" placeholder="Optional. &lt;strong&gt; and &lt;br&gt; allowed">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Text Color</label>
                    <select class="form-select" name="items[{{ $i }}][text_style]">
                        <option value="dark" {{ ($item['text_style'] ?? 'dark') === 'dark' ? 'selected' : '' }}>Dark (for light images)</option>
                        <option value="light" {{ ($item['text_style'] ?? 'dark') === 'light' ? 'selected' : '' }}>Light (for dark images)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subtitle</label>
                    <input type="text" class="form-control" name="items[{{ $i }}][subtitle]" value="{{ $item['subtitle'] ?? '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Button Text</label>
                    <input type="text" class="form-control" name="items[{{ $i }}][button_text]" value="{{ $item['button_text'] ?? '' }}" placeholder="Empty = no button">
                </div>
                <div class="col-12">
                    <label class="form-label">Link</label>
                    <input type="text" class="form-control" name="items[{{ $i }}][url]" value="{{ $item['url'] ?? '' }}" placeholder="e.g. /shop or a full URL">
                </div>
            </div>
        </div>
    </div>
</div>
