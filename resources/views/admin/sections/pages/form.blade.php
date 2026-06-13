@extends('admin.layout.main')
@section('page-title')
    @if(isset($page))
        Update
    @else
        Create
    @endif Page
@endsection

@php
    if(isset($page)) {
        $actionUrl = route('admin.pages.update', ['page' => $page]);
        $method = 'PATCH';
    } else {
        $actionUrl = route('admin.pages.store') ;
        $method = 'POST';
    }
@endphp

@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Page
            @if(isset($page))
                Update
            @else
                Create
            @endif
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{route('admin.pages.index')}}">Page Management</a></li>
                <li class="breadcrumb-item active">@if(isset($page)) Update @else Create @endif</li>
            </ol>
        </nav>

        <form action="{{ $actionUrl }}" method="post">
            @method($method)
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Page Content</h6>
                        </div>
                        <div class="card-body">
                            @include('partials.notification')

                            <div class="mb-3">
                                <label for="title" class="form-label">Title <b class="text-danger">*</b></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', isset($page) ? $page->title : '') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', isset($page) ? $page->slug : '') }}" placeholder="auto-generated from title if left blank">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    The public URL. Leave blank to generate from the title.
                                    @isset($page)
                                        Current page: <code>{{ route('pages.show', $page->slug) }}</code>
                                    @endisset
                                </div>
                            </div>

                            <div class="mb-0">
                                <label for="body" class="form-label">Body</label>
                                <textarea class="form-control @error('body') is-invalid @enderror" id="body" name="body" rows="18">{{ old('body', isset($page) ? $page->body : '') }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Use the toolbar to format headings, lists, links, tables and more.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 1rem;">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold">Publish</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', isset($page) ? $page->status : 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold">SEO</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', isset($page) ? $page->meta_title : '') }}">
                                </div>
                                <div class="mb-0">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', isset($page) ? $page->meta_description : '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
    </script>
@endpush
