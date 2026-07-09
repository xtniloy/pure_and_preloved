@php $items = array_values(old('items', $data['items'] ?? [[]])); @endphp
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Section Settings</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="heading" class="form-label">Heading</label>
                <input type="text" class="form-control @error('heading') is-invalid @enderror" id="heading" name="heading" value="{{ old('heading', $data['heading'] ?? '') }}" placeholder="Optional. &lt;strong&gt; allowed">
                @error('heading')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="subheading" class="form-label">Subheading</label>
                <input type="text" class="form-control" id="subheading" name="subheading" value="{{ old('subheading', $data['subheading'] ?? '') }}" placeholder="Optional">
            </div>
            <div class="col-md-4">
                <label for="background" class="form-label">Background</label>
                <select class="form-select" id="background" name="background">
                    <option value="white" {{ old('background', $data['background'] ?? 'white') === 'white' ? 'selected' : '' }}>White</option>
                    <option value="grey" {{ old('background', $data['background'] ?? 'white') === 'grey' ? 'selected' : '' }}>Light Grey</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="title_tag" class="form-label">Banner Title Size</label>
                <select class="form-select" id="title_tag" name="title_tag">
                    @foreach(['h3' => 'Large (h3)', 'h4' => 'Medium (h4)', 'h5' => 'Small (h5)', 'h6' => 'Extra Small (h6)'] as $tag => $label)
                        <option value="{{ $tag }}" {{ old('title_tag', $data['title_tag'] ?? 'h4') === $tag ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label d-block">Rounded Images</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="image_rounded" name="image_rounded" value="1"
                           {{ old('image_rounded', $data['image_rounded'] ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="image_rounded">Round the corners</label>
                </div>
            </div>
        </div>
        <div class="form-text mt-2">Column width adjusts automatically: 2 banners = half width each, 3 = thirds, 4 = quarters.</div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">Banners</h6>
        <button type="button" class="btn btn-sm btn-primary text-white js-add-row" data-repeater="items-repeater">+ Add Banner</button>
    </div>
    <div class="card-body js-repeater" id="items-repeater" data-next-index="{{ count($items) }}">
        <div class="js-rows">
            @foreach($items as $i => $item)
                @include('admin.sections.homepage.fields._module_item_row', ['i' => $i, 'item' => $item])
            @endforeach
        </div>
        <template class="js-row-template">
            @include('admin.sections.homepage.fields._module_item_row', ['i' => '__IDX__', 'item' => []])
        </template>
    </div>
</div>
