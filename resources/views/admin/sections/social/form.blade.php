@extends('admin.layout.main')

@section('page-title')
    Social Links
@endsection

@php $rows = array_values(old('links', $links ?: [['platform' => 'facebook', 'url' => '']])); @endphp

@section('content')
    <div class="container-lg px-4">

        {{-- Page heading + breadcrumb + top actions --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <div class="fs-2 fw-semibold">Social Links</div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Social Links</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" form="social-form" class="btn btn-primary text-white px-4">
                    Save Social Links
                </button>
            </div>
        </div>

        @include('partials.notification')

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following:</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="social-form" action="{{ route('admin.social-links.update') }}" method="post">
            @method('PUT')
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">Links</h6>
                            <button type="button" class="btn btn-sm btn-primary text-white js-add-row" data-repeater="social-repeater">+ Add Link</button>
                        </div>
                        <div class="card-body js-repeater" id="social-repeater" data-next-index="{{ count($rows) }}">
                            <div class="js-rows">
                                @foreach($rows as $i => $row)
                                    <div class="js-row border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-semibold">Link <span class="js-row-number"></span></span>
                                            <button type="button" class="btn btn-sm btn-outline-danger js-remove-row">Remove</button>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Platform</label>
                                                <select class="form-select" name="links[{{ $i }}][platform]">
                                                    @foreach($platforms as $key => $platform)
                                                        <option value="{{ $key }}" {{ ($row['platform'] ?? '') === $key ? 'selected' : '' }}>{{ $platform['label'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label">Profile URL</label>
                                                <input type="text" class="form-control" name="links[{{ $i }}][url]" value="{{ $row['url'] ?? '' }}" placeholder="https://...">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <template class="js-row-template">
                                <div class="js-row border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-semibold">Link <span class="js-row-number"></span></span>
                                        <button type="button" class="btn btn-sm btn-outline-danger js-remove-row">Remove</button>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Platform</label>
                                            <select class="form-select" name="links[__IDX__][platform]">
                                                @foreach($platforms as $key => $platform)
                                                    <option value="{{ $key }}">{{ $platform['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label">Profile URL</label>
                                            <input type="text" class="form-control" name="links[__IDX__][url]" value="" placeholder="https://...">
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div class="form-text">Rows are shown in this order. Rows with an empty URL are dropped on save.</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 1rem;">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold">Save</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary text-white">Save Social Links</button>
                                </div>
                                <div class="form-text mt-3">
                                    These links are used everywhere social icons appear:
                                    the footer, the mobile menu and the homepage
                                    "Follow Us On Social" section.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('js')
    @include('admin.partials.repeater_js')
@endpush
