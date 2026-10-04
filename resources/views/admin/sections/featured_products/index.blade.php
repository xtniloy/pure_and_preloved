@extends('admin.layout.main')
@section('page-title')
    Featured Products
@endsection
@section('content')
    @php
        // Prefer the small generated thumbnail; fall back to the full image.
        $thumb = function ($product) {
            $asset = $product->thumbnailImage ?? $product->main_image;

            return $asset ? ($asset->thumbnail_url ?? $asset->public_url) : null;
        };
        $featuredCount = $featured->count();
        $hasFilters = collect($filters)->except(['per_page'])->filter(fn ($v) => $v !== null && $v !== '')->isNotEmpty();
    @endphp
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold">Featured Products</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('admin.featured-products.index') }}">Featured Products</a></li>
            </ol>
        </nav>
        @include('partials.notification')

        @if($homepageLimit === null)
            <div class="alert alert-warning">
                No <strong>Featured Products Slider</strong> section is active on the homepage, so these products are not shown anywhere yet.
                @if(admin_can('manage homepage'))
                    <a href="{{ route('admin.homepage.index') }}" class="alert-link">Open the homepage builder</a>.
                @endif
            </div>
        @endif

        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'featured' ? 'active' : '' }}" data-coreui-toggle="tab" data-coreui-target="#tab-featured" type="button" role="tab">
                    Featured list <span class="badge bg-secondary ms-1">{{ $featuredCount }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'add' ? 'active' : '' }}" data-coreui-toggle="tab" data-coreui-target="#tab-add" type="button" role="tab">
                    Add products
                </button>
            </li>
        </ul>

        <div class="tab-content">
            {{-- ───────────── Featured list ───────────── --}}
            <div class="tab-pane fade {{ $activeTab === 'featured' ? 'show active' : '' }}" id="tab-featured" role="tabpanel">
                <div class="card border-top-0 rounded-top-0 mb-4">
                    <div class="card-body">
                        @if($featuredCount === 0)
                            <div class="text-center py-5">
                                <svg class="icon icon-3xl text-body-secondary mb-3"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-star') }}"></use></svg>
                                <p class="mb-3">No featured products yet.</p>
                                <button type="button" class="btn btn-primary js-open-add-tab">Add products</button>
                            </div>
                        @else
                            <form method="POST" action="{{ route('admin.featured-products.destroy') }}" id="remove-form">
                                @csrf
                                @method('DELETE')
                                <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                                    <span class="text-body-secondary small">
                                        Drag <svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use></svg>
                                        to reorder. Changes save automatically.
                                        @if($homepageLimit)
                                            The homepage shows the first <strong>{{ $homepageLimit }}</strong> active products.
                                        @endif
                                    </span>
                                    <span id="order-status" class="small"></span>
                                    <button type="submit" class="btn btn-sm btn-outline-danger ms-auto" id="remove-selected" disabled>
                                        Remove selected (<span class="js-count">0</span>)
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead>
                                        <tr>
                                            <th style="width:2rem"><input type="checkbox" class="form-check-input js-check-all" data-group="remove" aria-label="Select all"></th>
                                            <th style="width:2rem"></th>
                                            <th style="width:3rem">#</th>
                                            <th style="width:60px">Image</th>
                                            <th>Product</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody id="featured-list">
                                        @foreach($featured as $product)
                                            @php
                                                $visible = $product->status;
                                            @endphp
                                            <tr data-id="{{ $product->id }}" class="{{ $visible ? '' : 'table-light text-body-secondary' }}">
                                                <td><input type="checkbox" class="form-check-input js-check" data-group="remove" name="product_ids[]" value="{{ $product->id }}" aria-label="Select {{ $product->name }}"></td>
                                                <td class="cursor-grab text-body-secondary" title="Drag to reorder">
                                                    <svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use></svg>
                                                </td>
                                                <td class="js-position fw-semibold">{{ $loop->iteration }}</td>
                                                <td>
                                                    @if($src = $thumb($product))
                                                        <img src="{{ $src }}" alt="" width="48" height="48" class="rounded" style="object-fit:cover">
                                                    @else
                                                        <div class="bg-body-secondary rounded" style="width:48px;height:48px"></div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="fw-semibold">{{ $product->name }}</div>
                                                    <div class="small text-body-secondary">SKU: {{ $product->sku }}</div>
                                                </td>
                                                <td class="small">{{ $product->categories->pluck('name')->join(', ') ?: '—' }}</td>
                                                <td>{{ currency($product->sale_price ?? $product->price) }}</td>
                                                <td>
                                                    @unless($product->status)
                                                        <span class="badge bg-danger" title="Inactive products are hidden from the homepage">Inactive · hidden</span>
                                                    @endunless
                                                    @unless($product->in_stock)
                                                        <span class="badge bg-warning text-dark">Out of stock</span>
                                                    @endunless
                                                    @if($product->status && $product->in_stock)
                                                        <span class="badge bg-success">Live</span>
                                                    @endif
                                                </td>
                                                <td class="text-end text-nowrap">
                                                    <button type="button" class="btn btn-sm btn-ghost-secondary js-move" data-direction="top" title="Move to top">Top</button>
                                                    <button type="button" class="btn btn-sm btn-ghost-secondary js-move" data-direction="bottom" title="Move to bottom">Bottom</button>
                                                    @if(admin_can('manage products'))
                                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-ghost-secondary" title="Edit product">Edit</a>
                                                    @endif
                                                    <button type="button" class="btn btn-sm btn-ghost-danger js-remove-one" data-id="{{ $product->id }}" title="Remove from featured">Remove</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ───────────── Add products ───────────── --}}
            <div class="tab-pane fade {{ $activeTab === 'add' ? 'show active' : '' }}" id="tab-add" role="tabpanel">
                <div class="card border-top-0 rounded-top-0 mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.featured-products.index') }}" class="row g-2 align-items-end mb-3">
                            <input type="hidden" name="tab" value="add">
                            <div class="col-md-4">
                                <label class="form-label small mb-1" for="f-q">Search</label>
                                <input type="search" id="f-q" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Name or SKU">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small mb-1" for="f-category">Category</label>
                                <select id="f-category" name="category" class="form-select">
                                    <option value="">All categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected(($filters['category'] ?? null) == $category->id)>
                                            {{ ucfirst($category->gender) }} › {{ $category->parent ? $category->parent->name . ' › ' : '' }}{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small mb-1" for="f-status">Status</label>
                                <select id="f-status" name="status" class="form-select">
                                    <option value="">Any</option>
                                    <option value="active" @selected(($filters['status'] ?? null) === 'active')>Active</option>
                                    <option value="inactive" @selected(($filters['status'] ?? null) === 'inactive')>Inactive</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small mb-1" for="f-stock">Stock</label>
                                <select id="f-stock" name="stock" class="form-select">
                                    <option value="">Any</option>
                                    <option value="in" @selected(($filters['stock'] ?? null) === 'in')>In stock</option>
                                    <option value="out" @selected(($filters['stock'] ?? null) === 'out')>Out of stock</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label small mb-1" for="f-show">Show</label>
                                <select id="f-show" name="show" class="form-select">
                                    <option value="not_featured" @selected(($filters['show'] ?? 'not_featured') === 'not_featured')>Not yet featured</option>
                                    <option value="all" @selected(($filters['show'] ?? null) === 'all')>All products</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <label class="form-label small mb-1" for="f-per-page">Per page</label>
                                <select id="f-per-page" name="per_page" class="form-select">
                                    @foreach($perPageOptions as $option)
                                        <option value="{{ $option }}" @selected(($filters['per_page'] ?? $perPageOptions[0]) == $option)>{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-auto d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Apply</button>
                                @if($hasFilters)
                                    <a href="{{ route('admin.featured-products.index', ['tab' => 'add']) }}" class="btn btn-outline-secondary">Reset</a>
                                @endif
                            </div>
                        </form>

                        <form method="POST" action="{{ route('admin.featured-products.store') }}" id="add-form">
                            @csrf
                            <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                                <span class="small text-body-secondary">{{ number_format($products->total()) }} {{ str('product')->plural($products->total()) }} found</span>
                                <button type="submit" class="btn btn-sm btn-primary ms-auto" id="add-selected" disabled>
                                    Add selected to featured (<span class="js-count">0</span>)
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-2">
                                    <thead>
                                    <tr>
                                        <th style="width:2rem"><input type="checkbox" class="form-check-input js-check-all" data-group="add" aria-label="Select all on this page"></th>
                                        <th style="width:60px">Image</th>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($products as $product)
                                        <tr>
                                            <td>
                                                @if($product->is_featured)
                                                    <input type="checkbox" class="form-check-input" disabled checked aria-label="Already featured">
                                                @else
                                                    <input type="checkbox" class="form-check-input js-check" data-group="add" name="product_ids[]" value="{{ $product->id }}" aria-label="Select {{ $product->name }}">
                                                @endif
                                            </td>
                                            <td>
                                                @if($src = $thumb($product))
                                                    <img src="{{ $src }}" alt="" width="48" height="48" class="rounded" style="object-fit:cover">
                                                @else
                                                    <div class="bg-body-secondary rounded" style="width:48px;height:48px"></div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $product->name }}</div>
                                                <div class="small text-body-secondary">SKU: {{ $product->sku }}</div>
                                            </td>
                                            <td class="small">{{ $product->categories->pluck('name')->join(', ') ?: '—' }}</td>
                                            <td>{{ currency($product->sale_price ?? $product->price) }}</td>
                                            <td>{{ (int) $product->stock }}</td>
                                            <td>
                                                @if($product->is_featured)
                                                    <span class="badge bg-info">Featured #{{ $product->featured_order }}</span>
                                                @endif
                                                <span class="badge {{ $product->status ? 'bg-success' : 'bg-danger' }}">{{ $product->status ? 'Active' : 'Inactive' }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-body-secondary">
                                                No products match these filters.
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ── Selection: per-group counters, select-all, enable bulk buttons ──
            var buttons = { add: document.getElementById('add-selected'), remove: document.getElementById('remove-selected') };

            function refresh(group) {
                var boxes = document.querySelectorAll('.js-check[data-group="' + group + '"]');
                var checked = Array.prototype.filter.call(boxes, function (b) { return b.checked; }).length;
                var btn = buttons[group];
                if (btn) {
                    btn.disabled = checked === 0;
                    btn.querySelector('.js-count').textContent = checked;
                }
                var all = document.querySelector('.js-check-all[data-group="' + group + '"]');
                if (all) {
                    all.checked = boxes.length > 0 && checked === boxes.length;
                    all.indeterminate = checked > 0 && checked < boxes.length;
                }
            }

            document.addEventListener('change', function (e) {
                var el = e.target;
                if (el.classList.contains('js-check-all')) {
                    document.querySelectorAll('.js-check[data-group="' + el.dataset.group + '"]').forEach(function (b) { b.checked = el.checked; });
                    refresh(el.dataset.group);
                } else if (el.classList.contains('js-check')) {
                    refresh(el.dataset.group);
                }
            });

            // ── Tabs: empty-state shortcut + remember tab in the URL ──
            document.querySelectorAll('.js-open-add-tab').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var tab = document.querySelector('[data-coreui-target="#tab-add"]');
                    if (tab && window.coreui) coreui.Tab.getOrCreateInstance(tab).show();
                });
            });
            document.querySelectorAll('[data-coreui-toggle="tab"]').forEach(function (tab) {
                tab.addEventListener('shown.coreui.tab', function () {
                    var url = new URL(window.location);
                    if (tab.dataset.coreuiTarget === '#tab-add') url.searchParams.set('tab', 'add');
                    else url.searchParams.delete('tab');
                    history.replaceState(null, '', url);
                });
            });

            // ── Confirm removals ──
            var removeForm = document.getElementById('remove-form');
            if (removeForm) {
                removeForm.addEventListener('submit', function (e) {
                    var n = removeForm.querySelectorAll('.js-check:checked').length;
                    if (!window.confirm('Remove ' + n + ' product(s) from featured?')) e.preventDefault();
                });
            }
            document.querySelectorAll('.js-remove-one').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    removeForm.querySelectorAll('.js-check').forEach(function (b) { b.checked = b.value === btn.dataset.id; });
                    refresh('remove');
                    removeForm.requestSubmit();
                });
            });

            // ── Ordering: drag-and-drop + move to top/bottom, autosaved ──
            var list = document.getElementById('featured-list');
            if (!list) return;

            var statusEl = document.getElementById('order-status');
            var saveUrl = @json(route('admin.featured-products.reorder'));
            var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var saveTimer = null;

            Sortable.create(list, {
                handle: '.cursor-grab',
                animation: 150,
                ghostClass: 'bg-brand-soft',
                onEnd: changed
            });

            list.addEventListener('click', function (e) {
                var btn = e.target.closest('.js-move');
                if (!btn) return;
                var row = btn.closest('tr');
                if (btn.dataset.direction === 'top') list.prepend(row); else list.append(row);
                row.classList.add('bg-brand-soft');
                setTimeout(function () { row.classList.remove('bg-brand-soft'); }, 800);
                changed();
            });

            function changed() {
                list.querySelectorAll('tr').forEach(function (row, i) {
                    row.querySelector('.js-position').textContent = i + 1;
                });
                // Debounce so several quick moves become one request.
                clearTimeout(saveTimer);
                saveTimer = setTimeout(save, 400);
            }

            function setStatus(message, type) {
                statusEl.textContent = message;
                statusEl.className = 'small text-' + type;
            }

            function save() {
                var ids = Array.prototype.map.call(list.querySelectorAll('tr[data-id]'), function (row) { return row.dataset.id; });
                setStatus('Saving…', 'body-secondary');

                fetch(saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ids: ids })
                })
                .then(function (res) {
                    return res.json().then(function (body) {
                        if (!res.ok) throw new Error(body.message || 'Request failed');
                        return body;
                    });
                })
                .then(function () { setStatus('✓ Order saved', 'success'); })
                .catch(function (err) { setStatus('✗ ' + (err.message || 'Could not save order'), 'danger'); });
            }
        });
    </script>
    <style>
        .cursor-grab { cursor: grab; }
        .cursor-grab:active { cursor: grabbing; }
        .bg-brand-soft { background: rgba(15, 118, 111, .12) !important; }
    </style>
@endpush
