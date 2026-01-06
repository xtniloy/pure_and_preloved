@extends('admin.layout.main')
@section('page-title')
    @if(isset($category))
        Update
    @else
        Create
    @endif Category
@endsection

@php
    if(isset($category)) {
    $actionUrl = route('admin.categories.update', ['category' => $category]);
    $method = 'PATCH';
    } else {
    $actionUrl = route('admin.categories.store') ;
    $method = 'POST';
    }
@endphp

@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Category
            @if(isset($category))
                Update
            @else
                Create
            @endif
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a>
                </li>
                <li class="breadcrumb-item active"><span data-coreui-i18n="dashboard"><a href="{{route('admin.categories.index')}}">Category Management </a> </span>
                </li>
                <li class="breadcrumb-item active"><span data-coreui-i18n="dashboard">Category @if(isset($category)) Update @else Create @endif</span>
                </li>
            </ol>
        </nav>

        <div class="row ">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">
                            @if(isset($category))
                                Update
                            @else
                                Create
                            @endif
                            Category
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
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', isset($category) ? $category->name : '') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="parent_id" class="form-label">Parent Category</label>
                                    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                                        <option value="">None</option>
                                        @foreach($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ (old('parent_id', isset($category) ? $category->parent_id : '') == $parent->id) ? 'selected' : '' }}>{{ $parent->name }} - {{$parent->gender??""}}</option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Gender <b class="text-danger">*</b></label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                        <option value="unisex" {{ (old('gender', isset($category) ? $category->gender : '') == 'unisex') ? 'selected' : '' }}>Unisex</option>
                                        <option value="man" {{ (old('gender', isset($category) ? $category->gender : '') == 'man') ? 'selected' : '' }}>Man</option>
                                        <option value="women" {{ (old('gender', isset($category) ? $category->gender : '') == 'women') ? 'selected' : '' }}>Women</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                    @if(isset($category) && $category->image)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/'.$category->image) }}" alt="Current Image" style="max-height: 100px;">
                                        </div>
                                    @endif
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" {{ (old('status', isset($category) ? $category->status : 1)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
