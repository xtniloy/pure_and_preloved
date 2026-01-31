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
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Product
            @if(isset($product))
                Update
            @else
                Create
            @endif
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a>
                </li>
                <li class="breadcrumb-item active"><span data-coreui-i18n="dashboard"><a href="{{route('admin.products.index')}}">Product Management </a> </span>
                </li>
                <li class="breadcrumb-item active"><span data-coreui-i18n="dashboard">Product @if(isset($product)) Update @else Create @endif</span>
                </li>
            </ol>
        </nav>

        <div class="row ">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">
                            @if(isset($product))
                                Update
                            @else
                                Create
                            @endif
                            Product
                        </h5>
                    </div>
                    <div class="card-body">
                        @include('partials.notification')

                        <form action="{{ $actionUrl }}" enctype="multipart/form-data" method="post">
                            @method($method)
                            @csrf

                            <div class="row mb-3">
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
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label">Category <b class="text-danger">*</b></label>
                                    <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (old('category_id', isset($product) ? $product->category_id : '') == $category->id) ? 'selected' : '' }}>{{ $category->name }} ({{ $category->gender }})</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', isset($product) && $product->status ? 'checked' : '') }}>
                                        <label class="form-check-label" for="status">Active</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="price" class="form-label">Price <b class="text-danger">*</b></label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', isset($product) ? $product->price : '') }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="sale_price" class="form-label">Sale Price</label>
                                    <input type="number" step="0.01" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price', isset($product) ? $product->sale_price : '') }}">
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', isset($product) ? $product->description : '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <h5 class="mt-4 mb-3">Product Attributes</h5>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="condition" class="form-label">Condition</label>
                                    <input type="text" class="form-control" id="condition" name="condition" value="{{ old('condition', isset($product) ? $product->condition : 'Pre-owned') }}" placeholder="e.g. Pre-owned">
                                </div>
                                <div class="col-md-3">
                                    <label for="material" class="form-label">Material</label>
                                    <input type="text" class="form-control" id="material" name="material" value="{{ old('material', isset($product) ? $product->material : 'Gold') }}" placeholder="e.g. Gold">
                                </div>
                                <div class="col-md-3">
                                    <label for="weight" class="form-label">Weight</label>
                                    <input type="text" class="form-control" id="weight" name="weight" value="{{ old('weight', isset($product) ? $product->weight : '') }}" placeholder="e.g. 5g">
                                </div>
                                <div class="col-md-3">
                                    <label for="carat" class="form-label">Carat</label>
                                    <input type="text" class="form-control" id="carat" name="carat" value="{{ old('carat', isset($product) ? $product->carat : '') }}" placeholder="e.g. 24K">
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">Images</h5>
                            <div class="mb-3">
                                <button class="btn btn-outline-secondary mb-2" type="button" id="btn-file-manager-multi">Select Images</button>
                                <div id="selected-images-container" class="d-flex flex-wrap gap-2">
                                    @if(isset($product) && $product->images)
                                        @foreach($product->assets as $asset)
                                            <div class="position-relative image-item" data-id="{{ $asset->id }}">
                                                <img src="{{ route('file.view', ['fileId' => $asset->id]) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" aria-label="Close" onclick="removeImage(this)"></button>
                                                <input type="hidden" name="images[]" value="{{ $asset->id }}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">SEO Information</h5>
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', isset($product) ? $product->meta_title : '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', isset($product) ? $product->meta_description : '') }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', isset($product) ? $product->meta_keywords : '') }}">
                            </div>

                            <button type="submit" class="btn btn-primary text-white">Save Product</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Manager Modal -->
    <div class="modal fade" id="fileManagerModal" tabindex="-1" aria-labelledby="fileManagerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileManagerModalLabel">File Manager</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
            const selectedImagesContainer = document.getElementById('selected-images-container');
            const btnFileManagerMulti = document.getElementById('btn-file-manager-multi');

            btnFileManagerMulti.addEventListener('click', function() {
                fileManagerIframe.src = "{{ route('file.iframe') }}"; // Assuming this route exists and supports multiple selection if needed, or we adapt logic
                fileManagerModal.show();
            });

            // Listen for messages from the iframe
            window.addEventListener('message', function(event) {
                if (event.data.type === 'fileSelected') {
                    const file = event.data.file;
                    
                    // Check if image is already added
                    if (document.querySelector(`.image-item[data-id="${file.id}"]`)) {
                        return;
                    }

                    // Create image element
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
                    
                    fileManagerModal.hide();
                }
            });
        });

        function removeImage(btn) {
            btn.parentElement.remove();
        }
    </script>
@endpush
