@extends('admin.layout.main')
@section('page-title')
    Homepage Builder
@endsection

@section('content')
    <div class="container-lg px-4">

        {{-- Page heading + breadcrumb + top actions --}}
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div>
                <div class="fs-2 fw-semibold">Homepage Builder</div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Homepage</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary">View Homepage</a>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary text-white dropdown-toggle" data-coreui-toggle="dropdown" aria-expanded="false">
                        + Add Section
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach($addableTypes as $type => $config)
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.homepage.sections.create', ['type' => $type]) }}">
                                    {{ $config['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        @include('partials.notification')

        <div class="row g-4">
            {{-- ============ SECTIONS ============ --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold">Page Sections</h6>
                        <span class="text-body-secondary small">
                            Drag rows to reorder &middot; <span id="reorder-status">order is saved automatically</span>
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 40px;"></th>
                                    <th>Section</th>
                                    <th>Type</th>
                                    <th style="width: 90px;">Visible</th>
                                    <th style="width: 160px;" class="text-end pe-3">Actions</th>
                                </tr>
                            </thead>
                            {{-- Fixed sections (hero) stay pinned on top --}}
                            <tbody>
                                @foreach($sections->filter->isFixed() as $section)
                                    <tr class="table-light">
                                        <td class="text-center text-body-secondary" title="Fixed position">
                                            <svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use></svg>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $section->title }}</span>
                                            <div class="small text-body-secondary">Position fixed at the top of the page</div>
                                        </td>
                                        <td><span class="badge bg-dark">{{ $section->typeLabel() }}</span></td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.homepage.sections.toggle', $section) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" onchange="this.form.submit()"
                                                           {{ $section->is_active ? 'checked' : '' }}>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-end pe-3">
                                            @if($section->isEditable())
                                                <a href="{{ route('admin.homepage.sections.edit', $section) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- Reorderable body sections --}}
                            <tbody id="sections-sortable">
                                @foreach($sections->reject->isFixed() as $section)
                                    <tr draggable="true" data-id="{{ $section->id }}">
                                        <td class="text-center text-body-secondary drag-handle" style="cursor: grab;" title="Drag to reorder">
                                            <svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use></svg>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $section->title }}</span>
                                            @unless($section->is_active)
                                                <span class="badge bg-secondary ms-1">Hidden</span>
                                            @endunless
                                        </td>
                                        <td><span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">{{ $section->typeLabel() }}</span></td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.homepage.sections.toggle', $section) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox" onchange="this.form.submit()"
                                                           {{ $section->is_active ? 'checked' : '' }}>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="d-inline-flex gap-1">
                                                @if($section->isEditable())
                                                    <a href="{{ route('admin.homepage.sections.edit', $section) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                                @else
                                                    <span class="btn btn-sm btn-outline-secondary disabled">Static</span>
                                                @endif
                                                @if($section->isDeletable())
                                                    <form method="POST" action="{{ route('admin.homepage.sections.destroy', $section) }}"
                                                          onsubmit="return confirm('Remove the section \'{{ $section->title }}\' from the homepage? This cannot be undone.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ============ SEO ============ --}}
            <div class="col-lg-4">
                <div class="card mb-4 position-sticky" style="top: 1rem;">
                    <div class="card-header">
                        <h6 class="mb-0 fw-semibold">Homepage SEO</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.homepage.seo.update') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title"
                                       value="{{ old('meta_title', $seo['meta_title']) }}">
                                @error('meta_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description"
                                          rows="3">{{ old('meta_description', $seo['meta_description']) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords"
                                       value="{{ old('meta_keywords', $seo['meta_keywords']) }}">
                                <div class="form-text">Comma separated.</div>
                                @error('meta_keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Image (social share)</label>
                                <div class="image-slot">
                                    <div class="image-slot-preview d-flex flex-wrap gap-2 mb-2">
                                        @if($seo['meta_image_id'])
                                            <div class="position-relative">
                                                <img src="{{ route('admin.file.view', ['fileId' => $seo['meta_image_id']]) }}" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-secondary js-pick-image" type="button">Select Image</button>
                                        <button class="btn btn-sm btn-outline-danger js-clear-image" type="button">Remove</button>
                                    </div>
                                    <input type="hidden" class="js-image-id" name="meta_image_id" value="{{ old('meta_image_id', $seo['meta_image_id']) }}">
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary text-white">Save SEO Settings</button>
                            </div>
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
        document.addEventListener('DOMContentLoaded', function () {
            // ------- Drag & drop reordering -------
            const tbody = document.getElementById('sections-sortable');
            const statusEl = document.getElementById('reorder-status');
            let draggedRow = null;

            if (tbody) {
                tbody.addEventListener('dragstart', function (event) {
                    const row = event.target.closest('tr[draggable]');
                    if (!row) return;
                    draggedRow = row;
                    row.classList.add('opacity-50');
                    event.dataTransfer.effectAllowed = 'move';
                    // Firefox needs data set for the drag to start
                    event.dataTransfer.setData('text/plain', row.dataset.id);
                });

                tbody.addEventListener('dragover', function (event) {
                    if (!draggedRow) return;
                    event.preventDefault();
                    const overRow = event.target.closest('tr[draggable]');
                    if (!overRow || overRow === draggedRow) return;

                    const rect = overRow.getBoundingClientRect();
                    const after = (event.clientY - rect.top) > rect.height / 2;
                    tbody.insertBefore(draggedRow, after ? overRow.nextSibling : overRow);
                });

                tbody.addEventListener('drop', function (event) {
                    event.preventDefault();
                });

                tbody.addEventListener('dragend', function () {
                    if (!draggedRow) return;
                    draggedRow.classList.remove('opacity-50');
                    draggedRow = null;
                    saveOrder();
                });
            }

            function saveOrder() {
                const ids = Array.from(tbody.querySelectorAll('tr[data-id]')).map(function (row) {
                    return parseInt(row.dataset.id, 10);
                });

                statusEl.textContent = 'saving order...';

                fetch("{{ route('admin.homepage.sections.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ ids: ids })
                }).then(function (response) {
                    statusEl.textContent = response.ok ? 'order saved' : 'saving failed — reload and retry';
                }).catch(function () {
                    statusEl.textContent = 'saving failed — reload and retry';
                });
            }

            // ------- Meta image picker -------
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
                    slot.querySelector('.image-slot-preview').innerHTML = '';
                }
            });

            window.addEventListener('message', function (event) {
                const data = event.data || {};
                if (data.type !== 'fileSelected' || !currentSlot) return;

                currentSlot.querySelector('.js-image-id').value = data.file.id;
                currentSlot.querySelector('.image-slot-preview').innerHTML =
                    '<div class="position-relative"><img src="' + data.file.url + '" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;"></div>';

                fileManagerModal.hide();
            });
        });
    </script>
@endpush
