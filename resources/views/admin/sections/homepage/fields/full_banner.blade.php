<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Banner Content</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Background Image</label>
                @include('admin.sections.homepage.fields._image_slot', [
                    'prefix' => '',
                    'imageId' => old('image_id', $data['image_id'] ?? ''),
                    'imageUrl' => old('image_url', $data['image_url'] ?? ''),
                ])
                <div class="form-text">Shown full width behind the text.</div>
            </div>
            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="badge" class="form-label">Badge</label>
                        <input type="text" class="form-control" id="badge" name="badge" value="{{ old('badge', $data['badge'] ?? '') }}" placeholder="e.g. 20% OFF (optional)">
                    </div>
                    <div class="col-md-6">
                        <label for="eyebrow" class="form-label">Small Text Above Heading</label>
                        <input type="text" class="form-control" id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $data['eyebrow'] ?? '') }}" placeholder="e.g. NEW IN! (optional)">
                    </div>
                    <div class="col-12">
                        <label for="heading" class="form-label">Heading <b class="text-danger">*</b></label>
                        <input type="text" class="form-control @error('heading') is-invalid @enderror" id="heading" name="heading" value="{{ old('heading', $data['heading'] ?? '') }}" required>
                        @error('heading')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12">
                        <label for="subheading" class="form-label">Subheading</label>
                        <input type="text" class="form-control" id="subheading" name="subheading" value="{{ old('subheading', $data['subheading'] ?? '') }}" placeholder="Optional">
                    </div>
                    <div class="col-md-4">
                        <label for="button_text" class="form-label">Button Text</label>
                        <input type="text" class="form-control" id="button_text" name="button_text" value="{{ old('button_text', $data['button_text'] ?? '') }}" placeholder="Empty = no button">
                    </div>
                    <div class="col-md-4">
                        <label for="button_url" class="form-label">Button Link</label>
                        <input type="text" class="form-control" id="button_url" name="button_url" value="{{ old('button_url', $data['button_url'] ?? '') }}" placeholder="e.g. /shop">
                    </div>
                    <div class="col-md-4">
                        <label for="theme" class="form-label">Text Color</label>
                        <select class="form-select" id="theme" name="theme">
                            <option value="light" {{ old('theme', $data['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light text (dark image)</option>
                            <option value="dark" {{ old('theme', $data['theme'] ?? 'light') === 'dark' ? 'selected' : '' }}>Dark text (light image)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
