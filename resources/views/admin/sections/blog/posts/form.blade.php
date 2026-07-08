@extends('admin.layout.main')
@section('page-title')
    @if(isset($post))
        Update
    @else
        Create
    @endif Blog Post
@endsection

@php
    if(isset($post)) {
        $actionUrl = route('admin.blog-posts.update', ['blog_post' => $post]);
        $method = 'PATCH';
    } else {
        $actionUrl = route('admin.blog-posts.store') ;
        $method = 'POST';
    }
@endphp

@section('content')
    <div class="container-lg px-4">

        {{-- Page heading + breadcrumb + top actions --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <div class="fs-2 fw-semibold">
                    Blog Post @if(isset($post)) Update @else Create @endif
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.blog-posts.index')}}">Blog Posts</a></li>
                        <li class="breadcrumb-item active" aria-current="page">@if(isset($post)) Update @else Create @endif</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" form="blog-post-form" class="btn btn-primary text-white px-4">
                    Save Post
                </button>
            </div>
        </div>

        @include('partials.notification')

        <form id="blog-post-form" action="{{ $actionUrl }}" method="post">
            @method($method)
            @csrf

            <div class="row g-4">

                {{-- ============ MAIN COLUMN ============ --}}
                <div class="col-lg-8">

                    {{-- Post Content --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Post Content</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <b class="text-danger">*</b></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', isset($post) ? $post->title : '') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', isset($post) ? $post->slug : '') }}" placeholder="auto-generated from title if left blank">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    The public URL. Leave blank to generate from the title.
                                    @isset($post)
                                        Current post: <code>{{ route('blog.show', $post->slug) }}</code>
                                    @endisset
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="excerpt" class="form-label">Excerpt</label>
                                <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3" maxlength="500">{{ old('excerpt', isset($post) ? $post->excerpt : '') }}</textarea>
                                @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Short summary shown on the blog list. Falls back to the beginning of the body when empty.</div>
                            </div>

                            <div class="mb-0">
                                <label for="body" class="form-label">Body</label>
                                <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="18">{{ old('body', isset($post) ? $post->body : '') }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Use the toolbar to format headings, lists, links, tables and more.</div>
                            </div>
                        </div>
                    </div>

                    {{-- Media --}}
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Media</h6>
                        </div>
                        <div class="card-body">
                            <label class="form-label">Featured Image</label>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <button class="btn btn-outline-secondary" type="button" id="btn-file-manager-featured">Select Featured Image</button>
                                <div id="featured-image-container" class="d-flex flex-wrap gap-2">
                                    @if(isset($post) && $post->featured_image_id)
                                        <div class="position-relative image-item-single">
                                            <img src="{{ route('admin.file.view', ['fileId' => $post->featured_image_id]) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                            <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" aria-label="Close" onclick="removeSingleImage(this)"></button>
                                            <input type="hidden" name="featured_image_id" value="{{ $post->featured_image_id }}">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-text">Shown on the blog list, the post page and the "Recent Posts" widget.</div>
                            @error('featured_image_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
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
                                        @if(isset($post) && $post->meta_image_id)
                                            <div class="position-relative image-item-single">
                                                <img src="{{ route('admin.file.view', ['fileId' => $post->meta_image_id]) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                                <button type="button" class="btn-close position-absolute top-0 end-0 bg-white" aria-label="Close" onclick="removeSingleImage(this)"></button>
                                                <input type="hidden" name="meta_image_id" value="{{ $post->meta_image_id }}">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-text">Used for social sharing previews. Falls back to the featured image.</div>
                            </div>
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', isset($post) ? $post->meta_title : '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', isset($post) ? $post->meta_description : '') }}</textarea>
                            </div>
                            <div class="mb-0">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', isset($post) ? $post->meta_keywords : '') }}">
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
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <b class="text-danger">*</b></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        @foreach(\App\Models\BlogPost::statuses() as $value => $label)
                                            <option value="{{ $value }}" {{ old('status', isset($post) ? $post->status : \App\Models\BlogPost::STATUS_DRAFT) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Only <b>Published</b> posts appear on the site. Drafts and private posts stay hidden.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="published_at" class="form-label">Publish Date</label>
                                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at"
                                           value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave blank to use the moment of publishing. A future date schedules the post.</div>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" value="1" {{ old('allow_comments', isset($post) ? $post->allow_comments : 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="allow_comments">Allow comments</label>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary text-white">Save Post</button>
                                    <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-secondary">Cancel</a>
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
                                    <label for="categories" class="form-label">Categories</label>
                                    <select class="form-select @error('categories') is-invalid @enderror" id="categories" name="categories[]" multiple style="height: 160px;">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ in_array($category->id, old('categories', isset($post) ? $post->categories->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">
                                        Hold Ctrl/Cmd to select multiple.
                                        <a href="{{ route('admin.blog-categories.create') }}" target="_blank">Add a category</a>
                                    </div>
                                    @error('categories')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="tags" class="form-label">Tags</label>
                                    <select class="form-select @error('tags') is-invalid @enderror" id="tags" name="tags[]" multiple style="height: 140px;">
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}"
                                                {{ in_array($tag->id, old('tags', isset($post) ? $post->tags->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-0">
                                    <label for="new_tags" class="form-label">New Tags</label>
                                    <input type="text" class="form-control @error('new_tags') is-invalid @enderror" id="new_tags" name="new_tags" value="{{ old('new_tags') }}" placeholder="e.g. gold, vintage, rings">
                                    @error('new_tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Comma separated. Created and attached on save.</div>
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

@push('css')
    <style>
        /* Give the CKEditor editing area a comfortable height */
        .ck-editor__editable[role="textbox"] { min-height: 350px; }
        .ck.ck-editor { width: 100%; }
    </style>
@endpush

@push('js')
    {{-- CKEditor 5 (Classic) — free GPL build, pinned to the last key-free release --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bodyField = document.querySelector('#body');

            if (bodyField && window.ClassicEditor) {
                ClassicEditor
                    .create(bodyField, {
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ]
                    })
                    .catch(function (error) {
                        console.error('CKEditor failed to load:', error);
                    });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const fileManagerModal = new coreui.Modal(document.getElementById('fileManagerModal'));
            const fileManagerIframe = document.getElementById('fileManagerIframe');

            // Containers
            const featuredImageContainer = document.getElementById('featured-image-container');
            const metaImageContainer = document.getElementById('meta-image-container');

            // Buttons
            const btnFileManagerFeatured = document.getElementById('btn-file-manager-featured');
            const btnFileManagerMeta = document.getElementById('btn-file-manager-meta');

            let currentSelectionMode = 'featured'; // featured, meta

            btnFileManagerFeatured.addEventListener('click', function() {
                currentSelectionMode = 'featured';
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

                // Single-select modes (featured / meta): pick one and close.
                if (data.type === 'fileSelected') {
                    const file = data.file;

                    if (currentSelectionMode === 'featured') {
                        featuredImageContainer.innerHTML = '';
                        featuredImageContainer.appendChild(createSingleImageElement(file, 'featured_image_id'));
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

        function removeSingleImage(btn) {
            btn.parentElement.remove();
        }
    </script>
@endpush
