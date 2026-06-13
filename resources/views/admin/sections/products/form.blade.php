@extends('admin.layout.main')
@section('page-title')
    @if(isset($product))
        Update
    @else
        Create
    @endif Product
@endsection

@php
    if(isset($product)) {
        $actionUrl = route('admin.products.update', ['product' => $product]);
        $method = 'PATCH';
    } else {
        $actionUrl = route('admin.products.store') ;
        $method = 'POST';
    }
@endphp

@section('content')
    <div class="container-lg px-4">

        {{-- Page heading + breadcrumb + top actions --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <div class="fs-2 fw-semibold">
                    Product @if(isset($product)) Update @else Create @endif
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.products.index')}}">Product Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@if(isset($product)) Update @else Create @endif</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" form="product-form" class="btn btn-primary text-white px-4">
                    Save Product
                </button>
            </div>
        </div>

        @include('partials.notification')

        <form id="product-form" action="{{ $actionUrl }}" enctype="multipart/form-data" method="post">
            @method($method)
            @csrf

            <div class="row g-4">

                {{-- ============ MAIN COLUMN ============ --}}
                <div class="col-lg-8">

                    {{-- Basic Information --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Basic Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($product) ? $product->name : '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="sku" class="form-label">SKU <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', isset($product) ? $product->sku : '') }}" required>
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', isset($product) ? $product->description : '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing & Inventory --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Pricing &amp; Inventory</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="price" class="form-label">Price <b class="text-danger">*</b></label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', isset($product) ? $product->price : '') }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="sale_price" class="form-label">Sale Price</label>
                                    <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price', isset($product) ? $product->sale_price : '') }}">
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="stock" class="form-label">Stock Qty <b class="text-danger">*</b></label>
                                    <input type="number" step="1" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', isset($product) ? $product->stock : 0) }}" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Units in stock. 0 = out of stock.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Product Attributes --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Product Attributes</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <label for="condition" class="form-label">Condition</label>
                                    <input type="text" class="form-control" id="condition" name="condition" value="{{ old('condition', isset($product) ? $product->condition : 'Pre-owned') }}" placeholder="e.g. Pre-owned">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label for="material" class="form-label">Material</label>
                                    <input type="text" class="form-control" id="material" name="material" value="{{ old('material', isset($product) ? $product->material : 'Gold') }}" placeholder="e.g. Gold">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label for="weight" class="form-label">Weight</label>
                                    <input type="text" class="form-control" id="weight" name="weight" value="{{ old('weight', isset($product) ? $product->weight : '') }}" placeholder="e.g. 5g">
                                </div>
                                <div class="col-6 col-md-3">
                                    <label for="carat" class="form-label">Carat</label>
                                    <input type="text" class="form-control" id="carat" name="carat" value="{{ old('carat', isset($product) ? $product->carat : '') }}" placeholder="e.g. 24K">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Media / Images --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Media</h6>
                        </div>
                        <div class="card-body">
                            {{-- Thumbnail Image --}}
                            <div class="mb-4">
                                <label class="form-label">Thumbnail Image</label>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-file-manager-thumbnail">Select Thumbnail</button>
                                    <div id="thumbnail-container" class="d-flex flex-wrap gap-2">
                                        @if(isset($product) && $product->thumbnailImage)
                                            <div class="position-relative image-item-single">
                                                <img src="{{ route('admin.file.view', ['fileId' => $product->thumbnail_image_id]) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" aria-label="Close" onclick="removeSingleImage(this)"></button>
                                                <input type="hidden" name="thumbnail_image_id" value="{{ $product->thumbnail_image_id }}">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-text">Used as the main product image in listings.</div>
                            </div>

                            <hr class="text-body-secondary">

                            {{-- Gallery Images --}}
                            <div>
                                <label class="form-label">Gallery Images</label>
                                <div>
                                    <button class="btn btn-outline-secondary mb-2" type="button" id="btn-file-manager-multi">Select Images</button>
                                    <div id="selected-images-container" class="d-flex flex-wrap gap-2">
                                        @if(isset($product) && $product->images)
                                            @foreach($product->assets as $asset)
                                                <div class="position-relative image-item" data-id="{{ $asset->id }}">
                                                    <img src="{{ route('admin.file.view', ['fileId' => $asset->id]) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                    <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" aria-label="Close" onclick="removeImage(this)"></button>
                                                    <input type="hidden" name="images[]" value="{{ $asset->id }}">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="form-text">Add multiple images to showcase the product.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SEO Information --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">SEO Information</h6>
                        </div>
                        <div class="card-body">
                            {{-- Meta Image --}}
                            <div class="mb-3">
                                <label class="form-label">Meta Image</label>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-file-manager-meta">Select Meta Image</button>
                                    <div id="meta-image-container" class="d-flex flex-wrap gap-2">
                                        @if(isset($product) && $product->metaImage)
                                            <div class="position-relative image-item-single">
                                                <img src="{{ route('admin.file.view', ['fileId' => $product->meta_image_id]) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" aria-label="Close" onclick="removeSingleImage(this)"></button>
                                                <input type="hidden" name="meta_image_id" value="{{ $product->meta_image_id }}">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', isset($product) ? $product->meta_title : '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', isset($product) ? $product->meta_description : '') }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', isset($product) ? $product->meta_keywords : '') }}">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============ SIDEBAR ============ --}}
                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 1rem;">

                        {{-- Publish --}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold">Publish</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0" for="status">Status</label>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', isset($product) && $product->status ? 'checked' : '') }}>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary text-white">Save Product</button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>

                        {{-- Organization --}}
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold">Organization</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="product_gender" class="form-label">Gender Selection</label>
                                    <select class="form-select" id="product_gender" name="product_gender">
                                        <option value="">Select Gender</option>
                                        <option value="man">Man</option>
                                        <option value="women">Woman</option>
                                        <option value="unisex">Unisex</option>
                                    </select>
                                    <div class="form-text">Changing gender clears current category selections.</div>
                                </div>
                                <div class="mb-0">
                                    <label for="categories" class="form-label">Categories <b class="text-danger">*</b></label>
                                    <select class="form-select @error('categories') is-invalid @enderror" id="categories" name="categories[]" multiple required style="height: 260px;">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" data-gender="{{ $category->gender }}"
                                                {{ (in_array($category->id, old('categories', isset($product) ? $product->categories->pluck('id')->toArray() : []))) ? 'selected' : '' }}>
                                                {{ $category->parent ? $category->parent->name . ' > ' : '' }}{{ $category->name }} ({{ $category->gender }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Hold Ctrl/Cmd to select multiple. Only categories matching the selected gender are shown.</div>
                                    @error('categories')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- File Manager Modal -->
    <div class="modal fade" id="fileManagerModal" tabindex="-1" aria-labelledby="fileManagerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileManagerModalLabel">File Manager</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="fileManagerIframe" src="" style="width: 100%; height: 600px; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileManagerModal = new coreui.Modal(document.getElementById('fileManagerModal'));
            const fileManagerIframe = document.getElementById('fileManagerIframe');

            // Containers
            const selectedImagesContainer = document.getElementById('selected-images-container');
            const thumbnailContainer = document.getElementById('thumbnail-container');
            const metaImageContainer = document.getElementById('meta-image-container');

            // Buttons
            const btnFileManagerMulti = document.getElementById('btn-file-manager-multi');
            const btnFileManagerThumbnail = document.getElementById('btn-file-manager-thumbnail');
            const btnFileManagerMeta = document.getElementById('btn-file-manager-meta');

            let currentSelectionMode = 'multi'; // multi, thumbnail, meta

            btnFileManagerMulti.addEventListener('click', function() {
                currentSelectionMode = 'multi';
                fileManagerIframe.src = "{{ route('admin.file.iframe') }}?mode=multi";
                fileManagerModal.show();
            });

            function getSelectedGalleryIds() {
                return Array.from(selectedImagesContainer.querySelectorAll('.image-item'))
                    .map(function(el) { return el.getAttribute('data-id'); });
            }

            function addGalleryImage(file) {
                // Avoid duplicates
                if (selectedImagesContainer.querySelector(`.image-item[data-id="${file.id}"]`)) {
                    return;
                }

                const div = document.createElement('div');
                div.className = 'position-relative image-item';
                div.setAttribute('data-id', file.id);

                const img = document.createElement('img');
                img.src = file.url;
                img.className = 'img-thumbnail';
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';

                const closeBtn = document.createElement('button');
                closeBtn.type = 'button';
                closeBtn.className = 'btn-close position-absolute top-0 end-0 bg-white';
                closeBtn.ariaLabel = 'Close';
                closeBtn.onclick = function() { removeImage(closeBtn); };

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'images[]';
                input.value = file.id;

                div.appendChild(img);
                div.appendChild(closeBtn);
                div.appendChild(input);

                selectedImagesContainer.appendChild(div);
            }

            function removeGalleryImageById(id) {
                const el = selectedImagesContainer.querySelector(`.image-item[data-id="${id}"]`);
                if (el) {
                    el.remove();
                }
            }

            btnFileManagerThumbnail.addEventListener('click', function() {
                currentSelectionMode = 'thumbnail';
                fileManagerIframe.src = "{{ route('admin.file.iframe') }}";
                fileManagerModal.show();
            });

            btnFileManagerMeta.addEventListener('click', function() {
                currentSelectionMode = 'meta';
                fileManagerIframe.src = "{{ route('admin.file.iframe') }}";
                fileManagerModal.show();
            });

            // Listen for messages from the iframe
            window.addEventListener('message', function(event) {
                const data = event.data || {};

                // The multi-select iframe asks for the currently selected gallery
                // images on load, so it can pre-highlight them.
                if (data.type === 'iframeReady') {
                    if (currentSelectionMode === 'multi' && fileManagerIframe.contentWindow) {
                        fileManagerIframe.contentWindow.postMessage({
                            type: 'initSelection',
                            selectedIds: getSelectedGalleryIds()
                        }, '*');
                    }
                    return;
                }

                // Multi-select: a file was toggled on/off in the (still open) modal.
                if (data.type === 'fileToggled') {
                    if (data.selected) {
                        addGalleryImage(data.file);
                    } else {
                        removeGalleryImageById(data.file.id);
                    }
                    return;
                }

                // Single-select modes (thumbnail / meta): pick one and close.
                if (data.type === 'fileSelected') {
                    const file = data.file;

                    if (currentSelectionMode === 'thumbnail') {
                        thumbnailContainer.innerHTML = '';
                        thumbnailContainer.appendChild(createSingleImageElement(file, 'thumbnail_image_id'));
                        fileManagerModal.hide();
                    } else if (currentSelectionMode === 'meta') {
                        metaImageContainer.innerHTML = '';
                        metaImageContainer.appendChild(createSingleImageElement(file, 'meta_image_id'));
                        fileManagerModal.hide();
                    }
                }
            });
        });

        function createSingleImageElement(file, inputName) {
            const div = document.createElement('div');
            div.className = 'position-relative image-item-single';

            const img = document.createElement('img');
            img.src = file.url;
            img.className = 'img-thumbnail';
            img.style.width = '100px';
            img.style.height = '100px';
            img.style.objectFit = 'cover';

            const closeBtn = document.createElement('button');
            closeBtn.type = 'button';
            closeBtn.className = 'btn-close position-absolute top-0 end-0 bg-white';
            closeBtn.ariaLabel = 'Close';
            closeBtn.onclick = function() { removeSingleImage(closeBtn); };

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = inputName;
            input.value = file.id;

            div.appendChild(img);
            div.appendChild(closeBtn);
            div.appendChild(input);

            return div;
        }

        // Gender -> Category Flow
        const genderSelect = document.getElementById('product_gender');
        const categoriesSelect = document.getElementById('categories');

        if (genderSelect && categoriesSelect) {
            // Initial setup
            initializeGenderFlow();

            // Listen for gender changes
            genderSelect.addEventListener('change', function() {
                // Clear existing selections when gender changes
                Array.from(categoriesSelect.options).forEach(opt => opt.selected = false);
                filterCategories();
            });
        }

        function initializeGenderFlow() {
            // Check if we have old input or existing product gender
            // If gender is already selected (e.g. by old input), filter based on it.
            // If not, but we have selected categories, infer gender.

            const selectedCategories = Array.from(categoriesSelect.selectedOptions);

            if (genderSelect.value) {
                // Gender explicitly selected (e.g. old input)
                filterCategories();
            } else if (selectedCategories.length > 0) {
                // Infer from first category
                const inferredGender = selectedCategories[0].getAttribute('data-gender');
                if (inferredGender) {
                    genderSelect.value = inferredGender;
                    filterCategories();
                }
            } else {
                // No gender, no categories.
                // Maybe hide all categories until gender is selected?
                // Or show all but disable? User said "based on gender it will show".
                // Let's hide all initially if no gender selected.
                filterCategories();
            }
        }

        function filterCategories() {
            const selectedGender = genderSelect.value;

            Array.from(categoriesSelect.options).forEach(opt => {
                const optGender = opt.getAttribute('data-gender');

                if (!selectedGender) {
                    // No gender selected -> Hide all or Show all?
                    // User flow: "first i slect the gender...".
                    // So we should probably hide everything or disable everything.
                    opt.hidden = true;
                    opt.disabled = true;
                } else {
                    if (optGender === selectedGender) {
                        opt.hidden = false;
                        opt.disabled = false;
                    } else {
                        opt.hidden = true;
                        opt.disabled = true;
                        opt.selected = false; // Ensure hidden ones aren't selected
                    }
                }
            });
        }

        function removeImage(btn) {
            btn.parentElement.remove();
        }

        function removeSingleImage(btn) {
            btn.parentElement.remove();
        }
    </script>
@endpush
