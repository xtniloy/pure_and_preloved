@extends('admin.layout.main')
@section('page-title')
    @if(isset($category))
        Update
    @else
        Create
    @endif Blog Category
@endsection

@php
    if(isset($category)) {
        $actionUrl = route('admin.blog-categories.update', ['blog_category' => $category]);
        $method = 'PATCH';
    } else {
        $actionUrl = route('admin.blog-categories.store') ;
        $method = 'POST';
    }
@endphp

@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Blog Category
            @if(isset($category))
                Update
            @else
                Create
            @endif
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{route('admin.blog-categories.index')}}">Blog Categories</a></li>
                <li class="breadcrumb-item active">@if(isset($category)) Update @else Create @endif</li>
            </ol>
        </nav>

        <form action="{{ $actionUrl }}" method="post">
            @method($method)
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">Category Details</h6>
                        </div>
                        <div class="card-body">
                            @include('partials.notification')

                            <div class="mb-3">
                                <label for="name" class="form-label">Name <b class="text-danger">*</b></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($category) ? $category->name : '') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', isset($category) ? $category->slug : '') }}" placeholder="auto-generated from name if left blank">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Used in the blog filter URL. Leave blank to generate from the name.</div>
                            </div>

                            <div class="mb-0">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" maxlength="1000">{{ old('description', isset($category) ? $category->description : '') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', isset($category) ? $category->status : 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
