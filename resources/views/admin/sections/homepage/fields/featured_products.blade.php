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
        <div class="mb-3">
            <label for="limit" class="form-label">Number of products to show <b class="text-danger">*</b></label>
            <input type="number" class="form-control @error('limit') is-invalid @enderror" id="limit" name="limit" min="1" max="{{ \App\Models\Product::FEATURED_MAX_SHOWN }}" value="{{ old('limit', \App\Models\Product::featuredLimit($data ?? [])) }}" required style="max-width: 10rem">
            @error('limit')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Between 1 and {{ \App\Models\Product::FEATURED_MAX_SHOWN }}. Inactive products are skipped.</div>
        </div>
        <div class="alert alert-info mb-0">
            This section shows active featured products in the order you set.
            Choose and reorder them in
            <a href="{{ route('admin.featured-products.index') }}" class="alert-link">Featured Products</a>.
        </div>
    </div>
</div>
