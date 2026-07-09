@php $columns = array_values(old('columns', $data['columns'] ?? [''])); @endphp
<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Section Settings</h6>
    </div>
    <div class="card-body">
        <div class="mb-0">
            <label for="heading" class="form-label">Heading</label>
            <input type="text" class="form-control @error('heading') is-invalid @enderror" id="heading" name="heading" value="{{ old('heading', $data['heading'] ?? '') }}" placeholder="Optional">
            @error('heading')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-semibold">Text Columns</h6>
        <button type="button" class="btn btn-sm btn-primary text-white js-add-row" data-repeater="columns-repeater">+ Add Column</button>
    </div>
    <div class="card-body js-repeater" id="columns-repeater" data-next-index="{{ count($columns) }}">
        <div class="js-rows">
            @foreach($columns as $column)
                <div class="js-row border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Column <span class="js-row-number"></span></span>
                        <button type="button" class="btn btn-sm btn-outline-danger js-remove-row">Remove</button>
                    </div>
                    <textarea class="form-control" name="columns[]" rows="4">{{ $column }}</textarea>
                </div>
            @endforeach
        </div>
        <template class="js-row-template">
            <div class="js-row border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-semibold">Column <span class="js-row-number"></span></span>
                    <button type="button" class="btn btn-sm btn-outline-danger js-remove-row">Remove</button>
                </div>
                <textarea class="form-control" name="columns[]" rows="4"></textarea>
            </div>
        </template>
        <div class="form-text">Columns share the row equally (up to 4 per row).</div>
    </div>
</div>
