<div class="card mb-4">
    <div class="card-header">
        <h6 class="mb-0 fw-semibold">Section Settings</h6>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label for="heading" class="form-label">Heading <b class="text-danger">*</b></label>
            <input type="text" class="form-control @error('heading') is-invalid @enderror" id="heading" name="heading" value="{{ old('heading', $data['heading'] ?? '') }}" required placeholder="e.g. &lt;strong&gt;WE ALSO RECOMMEND&lt;/strong&gt; FOR YOU">
            @error('heading')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Wrap words in &lt;strong&gt;...&lt;/strong&gt; to make them extra bold.</div>
        </div>
        <div class="alert alert-info mb-0">
            This section automatically shows the latest products marked as featured.
            Manage which products appear in
            <a href="{{ route('admin.featured-products.index') }}" class="alert-link">Featured Products</a>.
        </div>
    </div>
</div>
