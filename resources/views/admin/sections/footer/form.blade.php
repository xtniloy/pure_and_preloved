@extends('admin.layout.main')

@section('page-title')
    Footer
@endsection

@php
    $infoLinks = array_values(old('info_links', $footer['info_links'] ?? [['label' => '', 'url' => '']]));
    $customLinks = array_values(old('custom_links', $footer['custom_links'] ?? [['label' => '', 'url' => '']]));
    $tagLinks = array_values(old('tag_links', $footer['tag_links'] ?? [['label' => '', 'url' => '']]));
@endphp

@section('content')
    <div class="container-lg px-4">

        {{-- Page heading + breadcrumb + top actions --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <div class="fs-2 fw-semibold">Footer</div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Footer</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" form="footer-form" class="btn btn-primary text-white px-4">
                    Save Footer
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

        <form id="footer-form" action="{{ route('admin.footer.update') }}" method="post">
            @method('PUT')
            @csrf

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 fw-semibold">About &amp; Contact</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="about_text" class="form-label">About Text</label>
                                    <textarea class="form-control @error('about_text') is-invalid @enderror" id="about_text" name="about_text" rows="3">{{ old('about_text', $footer['about_text'] ?? '') }}</textarea>
                                    <div class="form-text">Shown under the footer logo.</div>
                                    @error('about_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $footer['address'] ?? '') }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $footer['email'] ?? '') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $footer['phone'] ?? '') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label for="copyright" class="form-label">Copyright Line</label>
                                    <input type="text" class="form-control @error('copyright') is-invalid @enderror" id="copyright" name="copyright" value="{{ old('copyright', $footer['copyright'] ?? '') }}">
                                    <div class="form-text">Simple links are allowed, e.g. &lt;a href="..."&gt;Pure and Preloved&lt;/a&gt;.</div>
                                    @error('copyright')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">Information Column</h6>
                            <button type="button" class="btn btn-sm btn-primary text-white js-add-row" data-repeater="info-links-repeater">+ Add Link</button>
                        </div>
                        <div class="card-body js-repeater" id="info-links-repeater" data-next-index="{{ count($infoLinks) }}">
                            <div class="js-rows">
                                @foreach($infoLinks as $i => $link)
                                    <div class="js-row border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-semibold">Link <span class="js-row-number"></span></span>
                                            <button type="button" class="btn btn-sm btn-outline-danger js-remove-row">Remove</button>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-5">
                                                <label class="form-label">Label</label>
                                                <input type="text" class="form-control" name="info_links[{{ $i }}][label]" value="{{ $link['label'] ?? '' }}">
                                            </div>
                                            <div class="col-md-7">
                                                <label class="form-label">Link</label>
                                                <input type="text" class="form-control" name="info_links[{{ $i }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="e.g. /about-us or a full URL">
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
                                        <div class="col-md-5">
                                            <label class="form-label">Label</label>
                                            <input type="text" class="form-control" name="info_links[__IDX__][label]" value="">
                                        </div>
                                        <div class="col-md-7">
                                            <label class="form-label">Link</label>
                                            <input type="text" class="form-control" name="info_links[__IDX__][url]" value="" placeholder="e.g. /about-us or a full URL">
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div class="form-text">Your pages live at /&lt;slug&gt;, e.g. /about-us, /terms, /care-guide.</div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">Custom Links Column</h6>
                            <button type="button" class="btn btn-sm btn-primary text-white js-add-row" data-repeater="links-repeater">+ Add Link</button>
                        </div>
                        <div class="card-body js-repeater" id="links-repeater" data-next-index="{{ count($customLinks) }}">
                            <div class="js-rows">
                                @foreach($customLinks as $i => $link)
                                    <div class="js-row border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-semibold">Link <span class="js-row-number"></span></span>
                                            <button type="button" class="btn btn-sm btn-outline-danger js-remove-row">Remove</button>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-5">
                                                <label class="form-label">Label</label>
                                                <input type="text" class="form-control" name="custom_links[{{ $i }}][label]" value="{{ $link['label'] ?? '' }}">
                                            </div>
                                            <div class="col-md-7">
                                                <label class="form-label">Link</label>
                                                <input type="text" class="form-control" name="custom_links[{{ $i }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="e.g. /login or a full URL">
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
                                        <div class="col-md-5">
                                            <label class="form-label">Label</label>
                                            <input type="text" class="form-control" name="custom_links[__IDX__][label]" value="">
                                        </div>
                                        <div class="col-md-7">
                                            <label class="form-label">Link</label>
                                            <input type="text" class="form-control" name="custom_links[__IDX__][url]" value="" placeholder="e.g. /login or a full URL">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">Bottom Tag Links (above the copyright line)</h6>
                            <button type="button" class="btn btn-sm btn-primary text-white js-add-row" data-repeater="tag-links-repeater">+ Add Link</button>
                        </div>
                        <div class="card-body js-repeater" id="tag-links-repeater" data-next-index="{{ count($tagLinks) }}">
                            <div class="js-rows">
                                @foreach($tagLinks as $i => $link)
                                    <div class="js-row border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-semibold">Link <span class="js-row-number"></span></span>
                                            <button type="button" class="btn btn-sm btn-outline-danger js-remove-row">Remove</button>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-5">
                                                <label class="form-label">Label</label>
                                                <input type="text" class="form-control" name="tag_links[{{ $i }}][label]" value="{{ $link['label'] ?? '' }}">
                                            </div>
                                            <div class="col-md-7">
                                                <label class="form-label">Link</label>
                                                <input type="text" class="form-control" name="tag_links[{{ $i }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="e.g. /shop or a full URL">
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
                                        <div class="col-md-5">
                                            <label class="form-label">Label</label>
                                            <input type="text" class="form-control" name="tag_links[__IDX__][label]" value="">
                                        </div>
                                        <div class="col-md-7">
                                            <label class="form-label">Link</label>
                                            <input type="text" class="form-control" name="tag_links[__IDX__][url]" value="" placeholder="e.g. /shop or a full URL">
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div class="form-text">The row of small links at the very bottom of the footer.</div>
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
                                    <button type="submit" class="btn btn-primary text-white">Save Footer</button>
                                </div>
                                <div class="form-text mt-3">
                                    The blog posts in the footer update automatically.
                                    Social icons are managed under <strong>Social Links</strong>.
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
