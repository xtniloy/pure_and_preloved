@extends('admin.layout.main')
@section('page-title')
    @if(isset($category))
        Update
    @else
        Create
    @endif Category
@endsection

@php
    $isEdit = isset($category);
    // "Subcategory" when it sits under a parent (level 3), otherwise a top "Category" (level 2).
    $entityLabel = $parent ? 'Subcategory' : 'Category';

    if ($isEdit) {
        $actionUrl = route('admin.categories.update', ['category' => $category]);
        $method = 'PATCH';
    } else {
        $actionUrl = route('admin.categories.store');
        $method = 'POST';
    }

    // Where "Cancel" returns to: the list this category belongs (or will belong) to.
    $cancelUrl = $parent_id
        ? route('admin.categories.index', ['parent_id' => $parent_id])
        : route('admin.categories.index', ['gender' => $gender]);

    $currentImageUrl = $isEdit && $category->asset
        ? route('admin.file.uploaded_asset', ['stored_name' => $category->asset->stored_name])
        : null;
    $currentImageName = $isEdit && $category->asset ? $category->asset->original_name : '';

    // Hierarchy trail: gender (fixed level 1) > ancestor categories > this one.
    $genderShort = ['man' => 'Men', 'women' => 'Women', 'unisex' => 'Unisex'][$gender] ?? ucfirst($gender);
    $trailCats = $isEdit
        ? $category->ancestors()
        : ($parent ? $parent->ancestors()->push($parent) : collect());
    $currentLabel = $isEdit ? $category->name : 'New ' . strtolower($entityLabel);
@endphp

@push('css')
    <style>
        .image-dropzone {
            border: 2px dashed var(--cui-border-color);
            border-radius: .5rem;
            cursor: pointer;
            transition: border-color .15s ease, background-color .15s ease;
            min-height: 112px;
        }
        .image-dropzone:hover { border-color: #0f766f; background: rgba(15, 118, 111, .04); }
        .image-dropzone img { width: 88px; height: 88px; object-fit: cover; border-radius: .5rem; }
        .image-dropzone .dz-placeholder-icon {
            width: 40px; height: 40px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            background: rgba(15, 118, 111, .12); color: #0f766f;
        }
        .image-dropzone .dz-placeholder-icon .icon { width: 22px; height: 22px; }

        .cat-trail .crumb {
            padding: .35rem .8rem; border-radius: 999px;
            font-size: .82rem; font-weight: 600; white-space: nowrap; line-height: 1;
        }
        .cat-trail .crumb-context { background: rgba(15, 118, 111, .12); color: #0f766f; }
        .cat-trail .crumb-current { background: #0f766f; color: #fff; }
        .cat-trail .crumb-sep { width: 14px; height: 14px; color: var(--cui-secondary-color); opacity: .65; }
    </style>
@endpush

@section('content')
    <div class="container-lg px-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mt-2 mb-3">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.categories.index')}}">Category Management</a></li>
                <li class="breadcrumb-item"><a href="{{ $cancelUrl }}">{{ $genderShort }}</a></li>
                <li class="breadcrumb-item active">{{ $isEdit ? 'Update' : 'Create' }} {{ $entityLabel }}</li>
            </ol>
        </nav>

        @include('partials.notification')

        <form action="{{ $actionUrl }}" enctype="multipart/form-data" method="post">
            @method($method)
            @csrf

            {{-- Gender and parent are fixed by context, submitted but not editable. --}}
            <input type="hidden" name="gender" value="{{ $gender }}">
            <input type="hidden" name="parent_id" value="{{ $parent_id ?? '' }}">

            <div class="card mb-4">
                {{-- Header doubles as the action bar — always visible, no scrolling --}}
                <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-3">
                    <h5 class="card-title mb-0 fw-semibold">{{ $isEdit ? 'Update' : 'Create' }} {{ $entityLabel }}</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ $cancelUrl }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-sm btn-primary">
                            <svg class="icon me-1"><use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-save')}}"></use></svg>
                            {{ $isEdit ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    {{-- Hierarchy indicator: shows exactly where this category lives. --}}
                    <div class="mb-4">
                        <div class="small text-body-secondary mb-2">
                            {{ $isEdit ? 'You are editing a category in:' : 'This category will be placed under:' }}
                        </div>
                        <div class="cat-trail d-flex align-items-center flex-wrap gap-2">
                            <span class="crumb crumb-context">{{ $genderShort }}</span>
                            @foreach($trailCats as $anc)
                                <svg class="crumb-sep"><use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-chevron-right')}}"></use></svg>
                                <span class="crumb crumb-context">{{ $anc->name }}</span>
                            @endforeach
                            <svg class="crumb-sep"><use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-chevron-right')}}"></use></svg>
                            <span class="crumb crumb-current">{{ $currentLabel }}</span>
                        </div>
                    </div>

                    <div class="row g-4">
                        {{-- Main details --}}
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <b class="text-danger">*</b></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $isEdit ? $category->name : '') }}" placeholder="e.g. Necklaces" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" {{ (old('status', $isEdit ? $category->status : 1)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active <span class="text-body-secondary small">— visible on the storefront</span></label>
                                </div>
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="col-lg-4">
                            <label class="form-label">Image</label>
                            <div class="image-dropzone d-flex flex-column align-items-center justify-content-center text-center p-2" id="image-dropzone">
                                <div id="image-preview" class="{{ $currentImageUrl ? '' : 'd-none' }}">
                                    @if($currentImageUrl)
                                        <img src="{{ $currentImageUrl }}" alt="{{ $currentImageName }}">
                                    @endif
                                </div>
                                <div id="image-empty" class="{{ $currentImageUrl ? 'd-none' : '' }}">
                                    <span class="dz-placeholder-icon mb-1">
                                        <svg class="icon"><use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-image-plus')}}"></use></svg>
                                    </span>
                                    <div class="small fw-medium">Click to choose an image</div>
                                </div>
                            </div>

                            <div class="small text-body-secondary text-truncate mt-1" id="image-caption">{{ $currentImageName }}</div>

                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-sm btn-outline-secondary flex-fill" type="button" id="btn-file-manager">
                                    {{ $currentImageUrl ? 'Change' : 'Choose' }}
                                </button>
                                <button class="btn btn-sm btn-outline-danger {{ $currentImageUrl ? '' : 'd-none' }}" type="button" id="btn-remove-image">Remove</button>
                            </div>

                            <input type="hidden" id="image_name" value="{{ $currentImageName }}">
                            <input type="hidden" id="asset_id" name="asset_id" value="{{ old('asset_id', $isEdit ? $category->asset_id : '') }}">
                            @error('asset_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- File Manager Modal -->
    <div class="modal fade" id="fileManagerModal" tabindex="-1" aria-labelledby="fileManagerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileManagerModalLabel">File Manager</h5>
                    <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe id="fileManagerIframe" src="{{ route('admin.file.iframe') }}" style="width: 100%; height: 600px; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const btnFileManager = document.getElementById('btn-file-manager');
                const btnRemoveImage = document.getElementById('btn-remove-image');
                const fileManagerModal = new coreui.Modal(document.getElementById('fileManagerModal'));
                const assetIdInput = document.getElementById('asset_id');
                const imageNameInput = document.getElementById('image_name');
                const imagePreview = document.getElementById('image-preview');
                const imageEmpty = document.getElementById('image-empty');
                const imageCaption = document.getElementById('image-caption');
                const dropzone = document.getElementById('image-dropzone');

                function openManager() {
                    fileManagerModal.show();
                }

                btnFileManager.addEventListener('click', openManager);
                dropzone.addEventListener('click', openManager);

                btnRemoveImage.addEventListener('click', function(e) {
                    e.stopPropagation();
                    assetIdInput.value = '';
                    imageNameInput.value = '';
                    imageCaption.textContent = '';
                    imagePreview.innerHTML = '';
                    imagePreview.classList.add('d-none');
                    imageEmpty.classList.remove('d-none');
                    btnRemoveImage.classList.add('d-none');
                    btnFileManager.textContent = 'Choose';
                });

                // Listen for message from the file manager iframe
                window.addEventListener('message', function(event) {
                    if (event.data.type === 'fileSelected') {
                        const file = event.data.file;
                        assetIdInput.value = file.id;
                        imageNameInput.value = file.original_name;
                        imageCaption.textContent = file.original_name;

                        imagePreview.innerHTML = `<img src="${file.url}" alt="${file.original_name}">`;
                        imagePreview.classList.remove('d-none');
                        imageEmpty.classList.add('d-none');
                        btnRemoveImage.classList.remove('d-none');
                        btnFileManager.textContent = 'Change';

                        fileManagerModal.hide();
                    }
                });
            });
        </script>
    @endpush
@endsection
