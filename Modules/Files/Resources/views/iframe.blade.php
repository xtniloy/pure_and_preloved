@extends('files::layouts.iframe_layout')

@section('page-title')
    File Uploader
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Check if running inside iframe
                if (window.self !== window.top) {
                    const selectButtons = document.querySelectorAll('.select-file-btn');
                    selectButtons.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const file = {
                                id: this.dataset.id,
                                original_name: this.dataset.originalName,
                                url: this.dataset.url
                            };
                            window.parent.postMessage({ type: 'fileSelected', file: file }, '*');
                        });
                    });
                } else {
                    // Hide select buttons if not in iframe
                    document.querySelectorAll('.select-file-btn').forEach(el => el.style.display = 'none');
                }
            });
        </script>
    @endpush
@endsection

@section('content')
    <div class="px-4">
        <div class="fs-2 fw-semibold mb-2">File Uploader</div>

        {{-- Upload Section --}}
        <div class="card mb-4">
            <div class="card-header">
                <strong>Upload Files (up to 500MB)</strong>
            </div>
            <div class="card-body">
                <div class="upload-area border border-2 border-dashed rounded-3 p-5 text-center" id="uploadArea">
                    <svg class="mb-3" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    <h5 class="mb-2">Drag & Drop files here</h5>
                    <p class="text-medium-emphasis mb-3">or</p>
                    <button type="button" class="btn btn-primary" id="browseBtn">
                        <svg class="me-2" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        Browse Files
                    </button>
                    <input type="file" id="fileInput" class="d-none" multiple>
                    <p class="text-muted small mt-3 mb-0">Supported formats: Images, Videos, Documents, PDFs, Text files, and more (Max: 500MB per file)</p>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div><strong>Uploaded Files </strong><span class="small ms-1">File List Section</span></div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-primary" id="fileCount">0 files</span>
                    {{-- View Toggle --}}
                    <div class="btn-group btn-group-sm" role="group" aria-label="View toggle">
                        <button type="button" class="btn btn-outline-secondary active" id="listViewBtn" title="List View">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                <line x1="3" y1="18" x2="3.01" y2="18"></line>
                            </svg>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="gridViewBtn" title="Grid View">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="text-body-secondary small">Files will be dynamically added here</p>

                {{-- List View --}}
                <div class="tab-content rounded-bottom" id="listView">
                    <div class="tab-pane p-3 active preview" role="tabpanel" id="preview-1000">
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 35%">File Name</th>
                                <th style="width: 10%">Type</th>
                                <th style="width: 10%">Size</th>
                                <th style="width: 15%">Upload Time</th>
                                <th style="width: 15%">Progress</th>
                                <th style="width: 8%">Status</th>
                                <th style="width: 7%" class="text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="fileListBody">
                            @if($files->count() > 0)
                                @foreach($files as $k=>$file)
                                    <tr class="file-row" id="file-{{$file->id}}" data-file-id="{{$file->id}}" data-file-type="{{$file->mime_type}}" data-file-name="{{$file->original_name}}" data-thumbnail="{{$file->thumbnail_url ?? ''}}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $iconClass = 'text-secondary';
                                                    $mimeType = $file->mime_type ?? '';
                                                @endphp

                                                <div class="form-check me-2">
                                                    <button type="button" class="btn btn-sm btn-outline-primary select-file-btn" data-id="{{ $file->id }}" data-original-name="{{ $file->original_name }}" data-url="{{ $file->url }}">Select</button>
                                                </div>

                                                @if(str_starts_with($mimeType, 'image/'))
                                                    <div class="file-thumbnail me-2" style="width: 32px; height: 32px;">
                                                        <img src="{{ $file->thumbnail_url ?? $file->url }}" alt="" class="rounded" style="width: 32px; height: 32px; object-fit: cover;">
                                                    </div>
                                                @elseif(str_starts_with($mimeType, 'video/'))
                                                    <div class="file-thumbnail me-2 position-relative" style="width: 32px; height: 32px;">
                                                        @if($file->thumbnail_url)
                                                            <img src="{{ $file->thumbnail_url }}" alt="" class="rounded" style="width: 32px; height: 32px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-dark rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                <svg class="text-white" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @elseif($mimeType === 'application/pdf')
                                                    <svg class="me-2 text-danger" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                        <polyline points="14 2 14 8 20 8"></polyline>
                                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                                        <polyline points="10 9 9 9 8 9"></polyline>
                                                    </svg>
                                                @elseif(str_starts_with($mimeType, 'text/') || in_array($mimeType, ['application/json', 'application/xml']))
                                                    <svg class="me-2 text-info" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                        <polyline points="14 2 14 8 20 8"></polyline>
                                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                                        <line x1="10" y1="9" x2="8" y2="9"></line>
                                                    </svg>
                                                @elseif(str_starts_with($mimeType, 'audio/'))
                                                    <svg class="me-2 text-success" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M9 18V5l12-2v13"></path>
                                                        <circle cx="6" cy="18" r="3"></circle>
                                                        <circle cx="18" cy="16" r="3"></circle>
                                                    </svg>
                                                @elseif(in_array($mimeType, ['application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed']))
                                                    <svg class="me-2 text-warning" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M21 8v13H3V8"></path>
                                                        <path d="M1 3h22v5H1z"></path>
                                                        <path d="M10 12h4"></path>
                                                    </svg>
                                                @else
                                                    <svg class="me-2 text-primary" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                                        <polyline points="13 2 13 9 20 9"></polyline>
                                                    </svg>
                                                @endif
                                                <span class="fw-medium text-truncate" style="max-width: 250px;" title="{{$file->original_name}}">{{$file->original_name}}</span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark">{{ $file->file_extension ?? pathinfo($file->original_name, PATHINFO_EXTENSION) }}</span></td>
                                        <td>{{$file->file_size}}</td>
                                        <td><small class="text-medium-emphasis">{{$file->upload_time}}</small></td>
                                        <td>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar progress-bar bg-success"
                                                     role="progressbar"
                                                     style="width: 100%"
                                                     id="progress-{{$file->id}}"></div>
                                            </div>
                                            <small class="text-muted" id="progress-text-{{$file->id}}">100%</small>
                                            <small class="upload-speed" id="speed-{{$file->id}}"></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-success" id="status-{{$file->id}}">{{$file->status}}</span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary action-btn" onclick="viewFile('{{$file->id}}')" title="View">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </button>
                                                <button type="button" class="btn btn-outline-success action-btn" onclick="downloadFile('{{$file->id}}')" title="Download" id="download-{{$file->id}}">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                        <polyline points="7 10 12 15 17 10"></polyline>
                                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                                    </svg>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger action-btn" onclick="deleteFile('{{$file->id}}')" title="Delete">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr id="emptyState">
                                    <td colspan="7" class="text-center py-5 text-medium-emphasis">
                                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                            <polyline points="13 2 13 9 20 9"></polyline>
                                        </svg>
                                        <p class="mb-0">No files uploaded yet</p>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Grid View --}}
                <div class="d-none" id="gridView">
                    <div class="row g-4" id="fileGridBody">
                        @if($files->count() > 0)
                            @foreach($files as $file)
                                <div class="col-6 col-md-4 col-lg-3 col-xl-2 file-grid-item" id="grid-file-{{$file->id}}" data-file-id="{{$file->id}}">
                                    <div class="card h-100 file-card">
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" style="height: 140px; overflow: hidden;">
                                            @php
                                                $mimeType = $file->mime_type ?? '';
                                            @endphp

                                            @if(str_starts_with($mimeType, 'image/'))
                                                <img src="{{ $file->thumbnail_url ?? $file->url }}" alt="{{ $file->original_name }}" class="w-100 h-100" style="object-fit: cover;">
                                            @elseif(str_starts_with($mimeType, 'video/'))
                                                @if($file->thumbnail_url)
                                                    <img src="{{ $file->thumbnail_url }}" alt="{{ $file->original_name }}" class="w-100 h-100" style="object-fit: cover;">
                                                    <div class="position-absolute top-50 start-50 translate-middle">
                                                        <div class="bg-dark bg-opacity-75 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <svg class="text-white" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                                                <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="bg-dark w-100 h-100 d-flex align-items-center justify-content-center">
                                                        <svg class="text-white" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <polygon points="23 7 16 12 23 17 23 7"></polygon>
                                                            <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                                                        </svg>
                                                    </div>
                                                @endif
                                            @elseif($mimeType === 'application/pdf')
                                                <svg class="text-danger" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                </svg>
                                            @elseif(str_starts_with($mimeType, 'audio/'))
                                                <svg class="text-success" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M9 18V5l12-2v13"></path>
                                                    <circle cx="6" cy="18" r="3"></circle>
                                                    <circle cx="18" cy="16" r="3"></circle>
                                                </svg>
                                            @elseif(str_starts_with($mimeType, 'text/') || in_array($mimeType, ['application/json', 'application/xml']))
                                                <svg class="text-info" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                    <polyline points="14 2 14 8 20 8"></polyline>
                                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                                    <line x1="10" y1="9" x2="8" y2="9"></line>
                                                </svg>
                                            @elseif(in_array($mimeType, ['application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed']))
                                                <svg class="text-warning" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M21 8v13H3V8"></path>
                                                    <path d="M1 3h22v5H1z"></path>
                                                    <path d="M10 12h4"></path>
                                                </svg>
                                            @else
                                                <svg class="text-primary" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                                    <polyline points="13 2 13 9 20 9"></polyline>
                                                </svg>
                                            @endif

                                            {{-- File type badge --}}
                                            <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75">
                                                {{ strtoupper($file->file_extension ?? pathinfo($file->original_name, PATHINFO_EXTENSION)) }}
                                            </span>
                                        </div>
                                        <div class="card-body p-2">
                                            <p class="card-text small mb-1 text-truncate fw-medium" title="{{ $file->original_name }}">{{ $file->original_name }}</p>
                                            <p class="card-text small text-muted mb-2">{{ $file->file_size }}</p>
                                            <div class="d-flex gap-1">
                                                <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="viewFile('{{$file->id}}')" title="View">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success flex-fill" onclick="downloadFile('{{$file->id}}')" title="Download">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                        <polyline points="7 10 12 15 17 10"></polyline>
                                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                                    </svg>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger flex-fill" onclick="deleteFile('{{$file->id}}')" title="Delete">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        {{-- Upload Progress Overlay --}}
                                        <div class="upload-overlay position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-90 d-none" id="grid-overlay-{{$file->id}}">
                                            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                                <div class="spinner-border text-primary mb-2" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <small class="text-muted" id="grid-progress-{{$file->id}}">100%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        <div class="col-12 text-center py-5 text-medium-emphasis {{ $files->count() > 0 ? 'd-none' : '' }}" id="gridEmptyState">
                            <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>
                            <p class="mb-0">No files uploaded yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .upload-area {
            background-color: var(--cui-body-bg);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .upload-area:hover {
            background-color: var(--cui-secondary-bg);
            border-color: var(--cui-primary) !important;
        }

        .upload-area.drag-over {
            background-color: var(--cui-primary-bg-subtle);
            border-color: var(--cui-primary) !important;
        }

        .file-row {
            transition: background-color 0.2s ease;
        }

        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .upload-speed {
            font-size: 0.75rem;
            color: #6c757d;
        }

        /* Grid View Styles */
        .file-card {
            transition: all 0.2s ease;
            cursor: pointer;
            overflow: hidden;
        }

        .file-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .file-grid-item .upload-overlay {
            border-radius: var(--cui-card-border-radius);
        }

        .file-thumbnail img {
            transition: transform 0.2s ease;
        }

        .file-card:hover .file-thumbnail img {
            transform: scale(1.05);
        }

        /* View Toggle Active State */
        .btn-group .btn.active {
            background-color: var(--cui-primary);
            border-color: var(--cui-primary);
            color: white;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('fileInput');
            const browseBtn = document.getElementById('browseBtn');
            const fileListBody = document.getElementById('fileListBody');
            const fileGridBody = document.getElementById('fileGridBody');
            const emptyState = document.getElementById('emptyState');
            const gridEmptyState = document.getElementById('gridEmptyState');
            const fileCount = document.getElementById('fileCount');
            const listView = document.getElementById('listView');
            const gridView = document.getElementById('gridView');
            const listViewBtn = document.getElementById('listViewBtn');
            const gridViewBtn = document.getElementById('gridViewBtn');

            const CHUNK_SIZE = 2 * 1024 * 1024; // 2MB chunks
            const MAX_FILE_SIZE = 500 * 1024 * 1024; // 500MB

            const uploadQueue = [];
            let activeUploads = 0;
            const MAX_CONCURRENT_UPLOADS = 2;
            let currentView = 'list';

            // CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            // File type configurations
            const fileTypeConfig = {
                image: {
                    extensions: ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico'],
                    mimes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp', 'image/x-icon'],
                    icon: 'image',
                    color: 'text-success'
                },
                video: {
                    extensions: ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'],
                    mimes: ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime', 'video/x-msvideo'],
                    icon: 'video',
                    color: 'text-primary'
                },
                audio: {
                    extensions: ['mp3', 'wav', 'ogg', 'flac', 'aac'],
                    mimes: ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/flac', 'audio/aac'],
                    icon: 'audio',
                    color: 'text-success'
                },
                pdf: {
                    extensions: ['pdf'],
                    mimes: ['application/pdf'],
                    icon: 'pdf',
                    color: 'text-danger'
                },
                text: {
                    extensions: ['txt', 'json', 'xml', 'csv', 'md', 'html', 'css', 'js'],
                    mimes: ['text/plain', 'application/json', 'application/xml', 'text/csv', 'text/markdown', 'text/html', 'text/css', 'application/javascript'],
                    icon: 'text',
                    color: 'text-info'
                },
                archive: {
                    extensions: ['zip', 'rar', '7z', 'tar', 'gz'],
                    mimes: ['application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed', 'application/x-tar', 'application/gzip'],
                    icon: 'archive',
                    color: 'text-warning'
                },
                document: {
                    extensions: ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
                    mimes: ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                    icon: 'document',
                    color: 'text-primary'
                }
            };

            // View Toggle
            listViewBtn.addEventListener('click', () => {
                currentView = 'list';
                listView.classList.remove('d-none');
                gridView.classList.add('d-none');
                listViewBtn.classList.add('active');
                gridViewBtn.classList.remove('active');
                localStorage.setItem('fileViewPreference', 'list');
            });

            gridViewBtn.addEventListener('click', () => {
                currentView = 'grid';
                gridView.classList.remove('d-none');
                listView.classList.add('d-none');
                gridViewBtn.classList.add('active');
                listViewBtn.classList.remove('active');
                localStorage.setItem('fileViewPreference', 'grid');
            });

            // Restore view preference
            const savedView = localStorage.getItem('fileViewPreference');
            if (savedView === 'grid') {
                gridViewBtn.click();
            }

            // Click to upload
            uploadArea.addEventListener('click', (e) => {
                if (e.target !== browseBtn && !browseBtn.contains(e.target)) {
                    fileInput.click();
                }
            });

            browseBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                fileInput.click();
            });

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Highlight drop area
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => {
                    uploadArea.classList.add('drag-over');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => {
                    uploadArea.classList.remove('drag-over');
                });
            });

            // Handle dropped files
            uploadArea.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                handleFiles(files);
            });

            // Handle selected files
            fileInput.addEventListener('change', (e) => {
                handleFiles(e.target.files);
                e.target.value = '';
            });

            function getFileType(file) {
                const extension = file.name.split('.').pop().toLowerCase();
                const mimeType = file.type;

                for (const [type, config] of Object.entries(fileTypeConfig)) {
                    if (config.extensions.includes(extension) || config.mimes.includes(mimeType)) {
                        return type;
                    }
                }
                return 'other';
            }

            function handleFiles(files) {
                [...files].forEach(file => {
                    if (file.size > MAX_FILE_SIZE) {
                        alert(`File "${file.name}" is too large. Maximum size is 500MB.`);
                        return;
                    }
                    queueUpload(file);
                });
                processUploadQueue();
            }

            function queueUpload(file) {
                const fileId = Date.now() + Math.random();
                const fileType = getFileType(file);
                const extension = file.name.split('.').pop().toLowerCase();

                const fileData = {
                    id: fileId,
                    file: file,
                    name: file.name,
                    size: formatFileSize(file.size),
                    time: new Date().toLocaleString(),
                    progress: 0,
                    status: 'queued',
                    uploadedChunks: 0,
                    totalChunks: Math.ceil(file.size / CHUNK_SIZE),
                    startTime: null,
                    uploadIdentifier: null,
                    fileType: fileType,
                    mimeType: file.type,
                    extension: extension,
                    thumbnailUrl: null
                };

                // Generate thumbnail for images
                if (fileType === 'image') {
                    generateImageThumbnail(file, fileData);
                } else if (fileType === 'video') {
                    generateVideoThumbnail(file, fileData);
                }

                uploadQueue.push(fileData);
                addFileToList(fileData);
                addFileToGrid(fileData);
                updateFileCount();
            }

            function generateImageThumbnail(file, fileData) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = new Image();
                    img.onload = () => {
                        const maxWidth = 320;
                        const maxHeight = 320;
                        let width = img.naturalWidth || img.width;
                        let height = img.naturalHeight || img.height;
                        const ratio = Math.min(maxWidth / width, maxHeight / height, 1);
                        const canvas = document.createElement('canvas');
                        canvas.width = Math.round(width * ratio);
                        canvas.height = Math.round(height * ratio);
                        const ctx = canvas.getContext('2d');
                        ctx.imageSmoothingEnabled = true;
                        ctx.imageSmoothingQuality = 'high';
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        fileData.thumbnailUrl = canvas.toDataURL('image/jpeg', 0.6);
                        updateThumbnails(fileData);
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }

            function generateVideoThumbnail(file, fileData) {
                const video = document.createElement('video');
                video.preload = 'metadata';
                video.src = URL.createObjectURL(file);
                video.currentTime = 1;
                video.onloadeddata = () => {
                    const canvas = document.createElement('canvas');
                    canvas.width = 320;
                    canvas.height = 180;
                    const ctx = canvas.getContext('2d');
                    ctx.imageSmoothingEnabled = true;
                    ctx.imageSmoothingQuality = 'high';
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    fileData.thumbnailUrl = canvas.toDataURL('image/jpeg', 0.6);
                    updateThumbnails(fileData);
                    URL.revokeObjectURL(video.src);
                };
            }

            function updateThumbnails(fileData) {
                // Update list view thumbnail
                const listRow = document.getElementById(`file-${fileData.id}`);
                if (listRow) {
                    const thumbnailContainer = listRow.querySelector('.file-thumbnail');
                    if (thumbnailContainer && fileData.thumbnailUrl) {
                        thumbnailContainer.innerHTML = `<img src="${fileData.thumbnailUrl}" alt="" class="rounded" style="width: 32px; height: 32px; object-fit: cover;">`;
                    }
                }

                // Update grid view thumbnail
                const gridCard = document.getElementById(`grid-file-${fileData.id}`);
                if (gridCard && fileData.thumbnailUrl) {
                    const imgContainer = gridCard.querySelector('.card-img-top');
                    if (imgContainer) {
                        if (fileData.fileType === 'video') {
                            imgContainer.innerHTML = `
                                <img src="${fileData.thumbnailUrl}" alt="${fileData.name}" class="w-100 h-100" style="object-fit: cover;">
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <div class="bg-dark bg-opacity-75 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <svg class="text-white" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                        </svg>
                                    </div>
                                </div>
                                <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75">${fileData.extension.toUpperCase()}</span>
                            `;
                        } else {
                            imgContainer.innerHTML = `
                                <img src="${fileData.thumbnailUrl}" alt="${fileData.name}" class="w-100 h-100" style="object-fit: cover;">
                                <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75">${fileData.extension.toUpperCase()}</span>
                            `;
                        }
                    }
                }
            }

            function getFileIcon(fileType, size = 20) {
                const icons = {
                    image: `<svg class="me-2 text-success" width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>`,
                    video: `<svg class="me-2 text-primary" width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="23 7 16 12 23 17 23 7"></polygon>
                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                    </svg>`,
                    audio: `<svg class="me-2 text-success" width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 18V5l12-2v13"></path>
                        <circle cx="6" cy="18" r="3"></circle>
                        <circle cx="18" cy="16" r="3"></circle>
                    </svg>`,
                    pdf: `<svg class="me-2 text-danger" width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>`,
                    text: `<svg class="me-2 text-info" width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <line x1="10" y1="9" x2="8" y2="9"></line>
                    </svg>`,
                    archive: `<svg class="me-2 text-warning" width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 8v13H3V8"></path>
                        <path d="M1 3h22v5H1z"></path>
                        <path d="M10 12h4"></path>
                    </svg>`,
                    document: `<svg class="me-2 text-primary" width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>`,
                    other: `<svg class="me-2 text-secondary" width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                        <polyline points="13 2 13 9 20 9"></polyline>
                    </svg>`
                };
                return icons[fileType] || icons.other;
            }

            function getGridFileIcon(fileType) {
                const icons = {
                    image: `<svg class="text-success" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>`,
                    video: `<svg class="text-primary" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <polygon points="23 7 16 12 23 17 23 7"></polygon>
                        <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                    </svg>`,
                    audio: `<svg class="text-success" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M9 18V5l12-2v13"></path>
                        <circle cx="6" cy="18" r="3"></circle>
                        <circle cx="18" cy="16" r="3"></circle>
                    </svg>`,
                    pdf: `<svg class="text-danger" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>`,
                    text: `<svg class="text-info" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <line x1="10" y1="9" x2="8" y2="9"></line>
                    </svg>`,
                    archive: `<svg class="text-warning" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M21 8v13H3V8"></path>
                        <path d="M1 3h22v5H1z"></path>
                        <path d="M10 12h4"></path>
                    </svg>`,
                    document: `<svg class="text-primary" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>`,
                    other: `<svg class="text-secondary" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                        <polyline points="13 2 13 9 20 9"></polyline>
                    </svg>`
                };
                return icons[fileType] || icons.other;
            }

            function processUploadQueue() {
                while (activeUploads < MAX_CONCURRENT_UPLOADS && uploadQueue.length > 0) {
                    const fileData = uploadQueue.shift();
                    if (fileData.status === 'queued') {
                        uploadFile(fileData);
                    }
                }
            }

            async function uploadFile(fileData) {
                activeUploads++;
                fileData.status = 'uploading';
                fileData.startTime = Date.now();
                fileData.uploadIdentifier = `${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

                updateFileStatus(fileData.id, 'Uploading', 'warning');
                showGridOverlay(fileData.id, true);

                try {
                    const totalChunks = fileData.totalChunks;

                    for (let chunkIndex = 0; chunkIndex < totalChunks; chunkIndex++) {
                        const start = chunkIndex * CHUNK_SIZE;
                        const end = Math.min(start + CHUNK_SIZE, fileData.file.size);
                        const chunk = fileData.file.slice(start, end);

                        const formData = new FormData();
                        formData.append('file', chunk);
                        formData.append('chunkIndex', chunkIndex);
                        formData.append('totalChunks', totalChunks);
                        formData.append('fileName', fileData.name);
                        formData.append('uploadIdentifier', fileData.uploadIdentifier);
                        formData.append('mimeType', fileData.mimeType);

                        const response = await fetch('{{ route("admin.file.upload.chunk") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });

                        if (!response.ok) {
                            throw new Error(`Upload failed: ${response.statusText}`);
                        }

                        const result = await response.json();
                        fileData.uploadedChunks = chunkIndex + 1;
                        const progress = Math.round((fileData.uploadedChunks / totalChunks) * 100);
                        updateProgress(fileData.id, progress);
                        updateGridProgress(fileData.id, progress);

                        // Calculate and display upload speed
                        const elapsedTime = (Date.now() - fileData.startTime) / 1000;
                        const uploadedBytes = (chunkIndex + 1) * CHUNK_SIZE;
                        const speed = formatSpeed(uploadedBytes / elapsedTime);
                        updateSpeed(fileData.id, speed);

                        if (result.completed) {
                            fileData.status = 'completed';
                            fileData.serverPath = result.path;
                            fileData.db_id = result.fileId;
                            fileData.serverThumbnailUrl = result.thumbnailUrl;

                            updateElementIds(fileData);

                            fileData.id = fileData.db_id;
                            updateFileStatus(fileData.id, 'Completed', 'success');
                            updateProgressBarCompleted(fileData.id);
                            showGridOverlay(fileData.db_id, false);

                            if (fileData.thumbnailUrl) {
                                try {
                                    const thumbRes = await uploadThumbnail(fileData);
                                    if (thumbRes && thumbRes.thumbnailUrl) {
                                        fileData.serverThumbnailUrl = thumbRes.thumbnailUrl;
                                        fileData.thumbnailUrl = thumbRes.thumbnailUrl;
                                        updateThumbnails(fileData);
                                    }
                                } catch (e) {
                                    console.error('Thumbnail upload failed', e);
                                }
                            }
                        }
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    fileData.status = 'failed';
                    updateFileStatus(fileData.id, 'Failed', 'danger');
                    showGridOverlay(fileData.id, false);
                    alert(`Failed to upload ${fileData.name}: ${error.message}`);
                } finally {
                    activeUploads--;
                    processUploadQueue();
                }
            }

            async function uploadThumbnail(fileData) {
                const formData = new FormData();
                formData.append('fileId', fileData.db_id || fileData.id);
                formData.append('thumbnail', fileData.thumbnailUrl);

                const response = await fetch('{{ route("admin.file.upload.thumbnail") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                if (!response.ok) {
                    throw new Error(`Thumbnail upload failed: ${response.statusText}`);
                }
                return await response.json();
            }

            function updateElementIds(fileData) {
                const oldRow = document.getElementById(`file-${fileData.id}`);
                if (oldRow) oldRow.id = `file-${fileData.db_id}`;

                const oldGrid = document.getElementById(`grid-file-${fileData.id}`);
                if (oldGrid) oldGrid.id = `grid-file-${fileData.db_id}`;

                const oldProgressBarId = document.getElementById(`progress-${fileData.id}`);
                if (oldProgressBarId) oldProgressBarId.id = `progress-${fileData.db_id}`;

                const oldOverlayId = document.getElementById(`grid-overlay-${fileData.id}`);
                if (oldOverlayId) oldOverlayId.id = `grid-overlay-${fileData.db_id}`;

                const progressText = document.getElementById(`progress-text-${fileData.id}`);
                const speed = document.getElementById(`speed-${fileData.id}`);
                const status = document.getElementById(`status-${fileData.id}`);
                const downloadBtn = document.getElementById(`download-${fileData.id}`);
                const viewBtn = document.getElementById(`view-${fileData.id}`);

                if (progressText) progressText.id = `progress-text-${fileData.db_id}`;
                if (speed) speed.id = `speed-${fileData.db_id}`;
                if (status) status.id = `status-${fileData.db_id}`;

                if (downloadBtn) {
                    downloadBtn.id = `download-${fileData.db_id}`;
                    downloadBtn.disabled = false;
                    downloadBtn.setAttribute('onclick', `downloadFile('${fileData.db_id}')`);
                }

                if (viewBtn) {
                    viewBtn.id = `view-${fileData.db_id}`;
                    viewBtn.disabled = false;
                    viewBtn.setAttribute('onclick', `viewFile('${fileData.db_id}')`);
                }

                // Update delete buttons
                const listDeleteBtn = document.querySelector(`#file-${fileData.db_id} .btn-outline-danger`);
                if (listDeleteBtn) {
                    listDeleteBtn.setAttribute('onclick', `deleteFile('${fileData.db_id}')`);
                }

                const gridCard = document.getElementById(`grid-file-${fileData.db_id}`);
                if (gridCard) {
                    const gridBtns = gridCard.querySelectorAll('button');
                    gridBtns.forEach(btn => {
                        const onclick = btn.getAttribute('onclick');
                        if (onclick) {
                            if (onclick.includes('viewFile')) btn.setAttribute('onclick', `viewFile('${fileData.db_id}')`);
                            if (onclick.includes('downloadFile')) btn.setAttribute('onclick', `downloadFile('${fileData.db_id}')`);
                            if (onclick.includes('deleteFile')) btn.setAttribute('onclick', `deleteFile('${fileData.db_id}')`);
                        }
                    });
                }
            }

            function addFileToList(fileData) {
                if (emptyState) {
                    emptyState.style.display = 'none';
                }

                const row = document.createElement('tr');
                row.className = 'file-row';
                row.id = `file-${fileData.id}`;

                const thumbnailHtml = ['image', 'video'].includes(fileData.fileType)
                    ? `<div class="file-thumbnail me-2" style="width: 32px; height: 32px;">
                         <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                           <div class="spinner-border spinner-border-sm text-muted" role="status"><span class="visually-hidden">Loading...</span></div>
                         </div>
                       </div>`
                    : getFileIcon(fileData.fileType);

                row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            ${thumbnailHtml}
                            <span class="fw-medium text-truncate" style="max-width: 250px;" title="${fileData.name}">${fileData.name}</span>
                        </div>
                    </td>
                    <td><span class="badge bg-light text-dark">${fileData.extension.toUpperCase()}</span></td>
                    <td>${fileData.size}</td>
                    <td><small class="text-medium-emphasis">${fileData.time}</small></td>
                    <td>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 style="width: 0%"
                                 id="progress-${fileData.id}"></div>
                        </div>
                        <small class="text-muted" id="progress-text-${fileData.id}">0%</small>
                        <small class="upload-speed" id="speed-${fileData.id}"></small>
                    </td>
                    <td>
                        <span class="badge bg-secondary" id="status-${fileData.id}">Queued</span>
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary action-btn" onclick="viewFile('${fileData.id}')" title="View" disabled id="view-${fileData.id}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                            <button type="button" class="btn btn-outline-success action-btn" onclick="downloadFile('${fileData.id}')" title="Download" disabled id="download-${fileData.id}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                            </button>
                            <button type="button" class="btn btn-outline-danger action-btn" onclick="deleteFile('${fileData.id}')" title="Delete">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                `;

                fileListBody.prepend(row);
            }

            function addFileToGrid(fileData) {
                if (gridEmptyState) {
                    gridEmptyState.classList.add('d-none');
                }

                const col = document.createElement('div');
                col.className = 'col-6 col-md-4 col-lg-3 col-xl-2 file-grid-item';
                col.id = `grid-file-${fileData.id}`;

                col.innerHTML = `
                    <div class="card h-100 file-card">
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" style="height: 140px; overflow: hidden;">
                            ${getGridFileIcon(fileData.fileType)}
                            <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75">${fileData.extension.toUpperCase()}</span>
                        </div>
                        <div class="card-body p-2">
                            <p class="card-text small mb-1 text-truncate fw-medium" title="${fileData.name}">${fileData.name}</p>
                            <p class="card-text small text-muted mb-2">${fileData.size}</p>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="viewFile('${fileData.id}')" title="View" disabled>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success flex-fill" onclick="downloadFile('${fileData.id}')" title="Download" disabled>
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger flex-fill" onclick="deleteFile('${fileData.id}')" title="Delete">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="upload-overlay position-absolute top-0 start-0 w-100 h-100 bg-white bg-opacity-90" id="grid-overlay-${fileData.id}">
                            <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                <div class="spinner-border text-primary mb-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <small class="text-muted" id="grid-progress-${fileData.id}">0%</small>
                            </div>
                        </div>
                    </div>
                `;

                fileGridBody.prepend(col);
            }

            function showGridOverlay(fileId, show) {
                const overlay = document.getElementById(`grid-overlay-${fileId}`);
                if (overlay) {
                    overlay.classList.toggle('d-none', !show);
                }
            }

            function updateProgressBarCompleted(fileId){
                const progressBar = document.getElementById(`progress-${fileId}`);
                if (progressBar) {
                    progressBar.classList.remove('progress-bar-animated', 'progress-bar-striped');
                    progressBar.classList.add('bg-success');
                }
            }

            function updateGridProgress(fileId, progress) {
                const progressEl = document.getElementById(`grid-progress-${fileId}`);
                if (progressEl) {
                    progressEl.textContent = progress + '%';
                }
            }

            function updateProgress(fileId, progress) {
                const progressBar = document.getElementById(`progress-${fileId}`);
                const progressText = document.getElementById(`progress-text-${fileId}`);

                if (progressBar) progressBar.style.width = progress + '%';
                if (progressText) progressText.textContent = progress + '%';
            }

            function updateSpeed(fileId, speed) {
                const speedElement = document.getElementById(`speed-${fileId}`);
                if (speedElement) speedElement.textContent = ` (${speed})`;
            }

            function updateFileStatus(fileId, statusText, statusType) {
                const statusBadge = document.getElementById(`status-${fileId}`);
                if (statusBadge) {
                    statusBadge.textContent = statusText;
                    statusBadge.className = `badge bg-${statusType}`;
                }

                if (statusText === 'Completed') {
                    const downloadBtn = document.getElementById(`download-${fileId}`);
                    const viewBtn = document.getElementById(`view-${fileId}`);
                    if (downloadBtn) downloadBtn.disabled = false;
                    if (viewBtn) viewBtn.disabled = false;

                    // Enable grid buttons
                    const gridCard = document.getElementById(`grid-file-${fileId}`);
                    if (gridCard) {
                        gridCard.querySelectorAll('button[disabled]').forEach(btn => btn.disabled = false);
                    }
                }
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }

            function formatSpeed(bytesPerSecond) {
                if (bytesPerSecond === 0) return '0 B/s';
                const k = 1024;
                const sizes = ['B/s', 'KB/s', 'MB/s', 'GB/s'];
                const i = Math.floor(Math.log(bytesPerSecond) / Math.log(k));
                return Math.round(bytesPerSecond / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }

            function updateFileCount() {
                const totalFiles = document.querySelectorAll('.file-row').length;
                fileCount.textContent = `${totalFiles} file${totalFiles !== 1 ? 's' : ''}`;
            }

            // Global functions for action buttons
            window.downloadFile = function(fileId) {
                window.open(`{{ route('admin.file.download', ['fileId' => '']) }}/${fileId}`, '_blank');
            };

            window.viewFile = function(fileId) {
                window.open(`{{ route('admin.file.view', ['fileId' => '']) }}/${fileId}`, '_blank');
            };

            window.deleteFile = function(fileId) {
                if (confirm('Are you sure you want to delete this file?')) {
                    fetch(`{{ route('admin.file.delete', ['fileId' => '']) }}/${fileId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove from list view
                                const row = document.getElementById(`file-${fileId}`);
                                if (row) row.remove();

                                // Remove from grid view
                                const gridItem = document.getElementById(`grid-file-${fileId}`);
                                if (gridItem) gridItem.remove();

                                updateFileCount();

                                const remainingFiles = document.querySelectorAll('.file-row').length;
                                if (remainingFiles === 0) {
                                    if (emptyState) emptyState.style.display = '';
                                    if (gridEmptyState) gridEmptyState.classList.remove('d-none');
                                }
                            }
                        })
                        .catch(error => {
                            alert('Failed to delete file: ' + error.message);
                        });
                }
            };
        });
    </script>
@endsection
