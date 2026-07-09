@extends('admin.layout.main')

@php
    $isEdit = isset($section);
    $standalone = $standalone ?? false;
    $typeConfig = \App\Models\HomeSection::TYPES[$type] ?? ['label' => $type];
    $data = $isEdit ? ($section->data ?? []) : [];

    if ($isEdit) {
        $actionUrl = route('admin.homepage.sections.update', ['section' => $section]);
        $method = 'PUT';
    } else {
        $actionUrl = route('admin.homepage.sections.store');
        $method = 'POST';
    }
@endphp

@section('page-title')
    {{ $standalone ? $typeConfig['label'] : ($isEdit ? 'Edit ' : 'Add ') . $typeConfig['label'] }}
@endsection

@section('content')
    <div class="container-lg px-4">

        {{-- Page heading + breadcrumb + top actions --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <div class="fs-2 fw-semibold">
                    {{ $standalone ? $typeConfig['label'] : ($isEdit ? 'Edit Section' : 'Add Section') }}
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        @unless($standalone)
                            <li class="breadcrumb-item"><a href="{{ route('admin.homepage.index') }}">Homepage</a></li>
                        @endunless
                        <li class="breadcrumb-item active" aria-current="page">{{ $typeConfig['label'] }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                @unless($standalone)
                    <a href="{{ route('admin.homepage.index') }}" class="btn btn-secondary">Cancel</a>
                @endunless
                <button type="submit" form="section-form" class="btn btn-primary text-white px-4">
                    Save Section
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

        <form id="section-form" action="{{ $actionUrl }}" method="post">
            @method($method)
            @csrf
            @unless($isEdit)
                <input type="hidden" name="type" value="{{ $type }}">
            @endunless

            <div class="row g-4">

                {{-- ============ MAIN COLUMN ============ --}}
                <div class="col-lg-8">
                    @include('admin.sections.homepage.fields.' . $type, ['data' => $data])
                </div>

                {{-- ============ SIDEBAR ============ --}}
                <div class="col-lg-4">
                    <div class="position-sticky" style="top: 1rem;">

                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold">Publish</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Section Name <b class="text-danger">*</b></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                                           value="{{ old('title', $isEdit ? $section->title : $typeConfig['label']) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Only shown in the admin section list.</div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <label class="form-label mb-0" for="is_active">Visible</label>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                               {{ old('is_active', $isEdit ? $section->is_active : true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Show on page</label>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary text-white">Save Section</button>
                                    @unless($standalone)
                                        <a href="{{ route('admin.homepage.index') }}" class="btn btn-secondary">Cancel</a>
                                    @endunless
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0 fw-semibold">Section Type</h6>
                            </div>
                            <div class="card-body">
                                <span class="badge bg-dark">{{ $typeConfig['label'] }}</span>
                                @if(!empty($typeConfig['fixed']))
                                    <div class="form-text mt-2">This section is pinned to the top of the homepage.</div>
                                @else
                                    <div class="form-text mt-2">Reorder or hide this section from the Homepage list.</div>
                                @endif
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
    @include('admin.partials.repeater_js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ------- Image picking (delegated so it works in new rows too) -------
            const fileManagerModal = new coreui.Modal(document.getElementById('fileManagerModal'));
            const fileManagerIframe = document.getElementById('fileManagerIframe');
            let currentSlot = null;

            document.addEventListener('click', function (event) {
                const pickBtn = event.target.closest('.js-pick-image');
                if (pickBtn) {
                    currentSlot = pickBtn.closest('.image-slot');
                    fileManagerIframe.src = "{{ route('admin.file.iframe') }}";
                    fileManagerModal.show();
                    return;
                }

                const clearBtn = event.target.closest('.js-clear-image');
                if (clearBtn) {
                    const slot = clearBtn.closest('.image-slot');
                    slot.querySelector('.js-image-id').value = '';
                    const urlInput = slot.querySelector('.js-image-url');
                    if (urlInput) urlInput.value = '';
                    slot.querySelector('.image-slot-preview').innerHTML = '';
                }
            });

            window.addEventListener('message', function (event) {
                const data = event.data || {};
                if (data.type !== 'fileSelected' || !currentSlot) return;

                currentSlot.querySelector('.js-image-id').value = data.file.id;
                const urlInput = currentSlot.querySelector('.js-image-url');
                if (urlInput) urlInput.value = '';
                currentSlot.querySelector('.image-slot-preview').innerHTML =
                    '<div class="position-relative"><img src="' + data.file.url + '" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;"></div>';

                fileManagerModal.hide();
            });
        });
    </script>
@endpush
