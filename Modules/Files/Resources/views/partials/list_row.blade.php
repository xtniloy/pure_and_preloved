<tr class="file-row" id="file-{{$file->id}}" data-file-id="{{$file->id}}" data-file-type="{{$file->mime_type}}" data-file-name="{{$file->original_name}}" data-thumbnail="{{$file->thumbnail_url ?? ''}}">
    <td>
        <div class="d-flex align-items-center">
            @php
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
