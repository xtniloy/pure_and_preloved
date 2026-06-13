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
                <button
                    type="button"
                    class="btn btn-sm btn-outline-primary flex-fill select-file-btn"
                    data-id="{{ $file->id }}"
                    data-original-name="{{ $file->original_name }}"
                    data-url="{{ $file->url }}"
                >
                    Select
                </button>
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
