@php $items = array_values(old('items', $data['items'] ?? [[]])); @endphp
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Section Settings</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label for="heading" class="form-label">Heading</label>
                <input type="text" class="form-control @error('heading') is-invalid @enderror" id="heading" name="heading" value="{{ old('heading', $data['heading'] ?? '') }}" placeholder="Optional. &lt;strong&gt; allowed">
                @error('heading')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="theme" class="form-label">Theme</label>
                <select class="form-select" id="theme" name="theme">
                    <option value="light" {{ old('theme', $data['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                    <option value="dark" {{ old('theme', $data['theme'] ?? 'light') === 'dark' ? 'selected' : '' }}>Dark (black background)</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">Items</h6>
        <button type="button" class="btn btn-sm btn-primary text-white js-add-row" data-repeater="items-repeater">+ Add Item</button>
    </div>
    <div class="card-body js-repeater" id="items-repeater" data-next-index="{{ count($items) }}">
        <div class="js-rows">
            @foreach($items as $i => $item)
                @include('admin.sections.homepage.fields._strip_item_row', ['i' => $i, 'item' => $item])
            @endforeach
        </div>
        <template class="js-row-template">
            @include('admin.sections.homepage.fields._strip_item_row', ['i' => '__IDX__', 'item' => []])
        </template>
    </div>
</div>
