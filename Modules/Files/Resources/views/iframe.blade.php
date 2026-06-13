@extends('files::layouts.iframe_layout')

@section('page-title')
    File Uploader
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const params = new URLSearchParams(window.location.search);
                const isMulti = (params.get('mode') || 'single') === 'multi';
                const selectedIds = new Set();

                function updateSelectButton(btn, selected) {
                    if (!btn) {
                        return;
                    }
                    if (selected) {
                        btn.classList.remove('btn-outline-primary');
                        btn.classList.add('btn-primary');
                        btn.textContent = '✓ Selected';
                    } else {
                        btn.classList.remove('btn-primary');
                        btn.classList.add('btn-outline-primary');
                        btn.textContent = 'Select';
                    }
                }

                function applySelectedState(id, selected) {
                    const grid = document.getElementById('grid-file-' + id);
                    if (grid) {
                        grid.classList.toggle('selected-file', selected);
                        updateSelectButton(grid.querySelector('.select-file-btn'), selected);
                    }
                }

                function updateCount() {
                    const counter = document.getElementById('multiSelectCount');
                    if (counter) {
                        counter.textContent = selectedIds.size;
                    }
                }

                // Re-apply the highlight to any currently-selected files. Called after
                // infinite scroll appends new file cards so they reflect selection.
                window.__applySelectionHighlights = function() {
                    selectedIds.forEach(function(id) {
                        applySelectedState(id, true);
                    });
                };

                document.addEventListener('click', function(event) {
                    const btn = event.target.closest('.select-file-btn');
                    if (!btn) {
                        return;
                    }

                    const file = {
                        id: btn.dataset.id,
                        original_name: btn.dataset.originalName,
                        url: btn.dataset.url
                    };

                    // Single-select modes: pick one, parent closes the modal.
                    if (!isMulti) {
                        if (window.parent && window.parent !== window) {
                            window.parent.postMessage({ type: 'fileSelected', file: file }, '*');
                        }
                        return;
                    }

                    // Multi-select: toggle this file on/off and highlight it.
                    const id = String(file.id);
                    const nowSelected = !selectedIds.has(id);
                    if (nowSelected) {
                        selectedIds.add(id);
                    } else {
                        selectedIds.delete(id);
                    }
                    applySelectedState(id, nowSelected);
                    updateCount();

                    if (window.parent && window.parent !== window) {
                        window.parent.postMessage({ type: 'fileToggled', file: file, selected: nowSelected }, '*');
                    }
                });

                if (isMulti) {
                    const banner = document.getElementById('multiSelectBanner');
                    if (banner) {
                        banner.classList.remove('d-none');
                    }

                    // Receive the gallery's already-selected images and pre-highlight them.
                    window.addEventListener('message', function(event) {
                        const data = event.data || {};
                        if (data.type === 'initSelection' && Array.isArray(data.selectedIds)) {
                            data.selectedIds.forEach(function(rawId) {
                                const id = String(rawId);
                                selectedIds.add(id);
                                applySelectedState(id, true);
                            });
                            updateCount();
                        }
                    });

                    // Tell the parent we're ready to receive the current selection.
                    if (window.parent && window.parent !== window) {
                        window.parent.postMessage({ type: 'iframeReady' }, '*');
                    }
                }
            });
        </script>
    @endpush
@endsection

@section('content')
    <div class="px-4">
        <div class="fs-2 fw-semibold mb-2">File Uploader</div>

        {{-- Multi-select instructions (shown only when opened in multi mode) --}}
        <div class="alert alert-info d-flex align-items-center py-2 d-none" id="multiSelectBanner">
            <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <div>
                Click an image to <strong>select</strong> it, click again to <strong>deselect</strong>.
                Selected images are added to the gallery instantly —
                <strong><span id="multiSelectCount">0</span></strong> selected.
                Close this window when you're done.
            </div>
        </div>

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
                <div><strong>Uploaded Files</strong></div>
                <span class="badge bg-primary" id="fileCount">0 files</span>
            </div>
            <div class="card-body">
                {{-- Grid View --}}
                <div class="row g-4" id="fileGridBody">
                    @if($files->total() > 0)
                        @foreach($files as $file)
                            @include('files::partials.grid_card', ['file' => $file])
                        @endforeach
                    @endif
                    <div class="col-12 text-center py-5 text-medium-emphasis {{ $files->total() > 0 ? 'd-none' : '' }}" id="gridEmptyState">
                        <svg class="mb-3" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                            <polyline points="13 2 13 9 20 9"></polyline>
                        </svg>
                        <p class="mb-0">No files uploaded yet</p>
                    </div>
                </div>

                {{-- Infinite-scroll loader / end-of-list indicator --}}
                <div id="filesLoader" class="text-center py-3 d-none">
                    <div class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <span class="ms-2 text-muted small">Loading more files…</span>
                </div>
                <div id="filesEnd" class="text-center py-3 text-muted small d-none">No more files</div>
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

        /* Multi-select highlight */
        .file-grid-item.selected-file .file-card {
            border: 2px solid var(--cui-primary);
            box-shadow: 0 0 0 0.2rem var(--cui-primary-bg-subtle);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const fileInput = document.getElementById('fileInput');
            const browseBtn = document.getElementById('browseBtn');
            const fileGridBody = document.getElementById('fileGridBody');
            const gridEmptyState = document.getElementById('gridEmptyState');
            const fileCount = document.getElementById('fileCount');

            const CHUNK_SIZE = 2 * 1024 * 1024; // 2MB chunks
            const MAX_FILE_SIZE = 500 * 1024 * 1024; // 500MB

            const uploadQueue = [];
            let activeUploads = 0;
            const MAX_CONCURRENT_UPLOADS = 2;

            // CSRF Token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            // File type configurations
            const fileTypeConfig = {
                image: {
                    extensions: ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico'],
                    mimes: ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp', 'image/x-icon']
                },
                video: {
                    extensions: ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'],
                    mimes: ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime', 'video/x-msvideo']
                },
                audio: {
                    extensions: ['mp3', 'wav', 'ogg', 'flac', 'aac'],
                    mimes: ['audio/mpeg', 'audio/wav', 'audio/ogg', 'audio/flac', 'audio/aac']
                },
                pdf: {
                    extensions: ['pdf'],
                    mimes: ['application/pdf']
                },
                text: {
                    extensions: ['txt', 'json', 'xml', 'csv', 'md', 'html', 'css', 'js'],
                    mimes: ['text/plain', 'application/json', 'application/xml', 'text/csv', 'text/markdown', 'text/html', 'text/css', 'application/javascript']
                },
                archive: {
                    extensions: ['zip', 'rar', '7z', 'tar', 'gz'],
                    mimes: ['application/zip', 'application/x-rar-compressed', 'application/x-7z-compressed', 'application/x-tar', 'application/gzip']
                },
                document: {
                    extensions: ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
                    mimes: ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
                }
            };

            // ---- Infinite scroll pagination ----
            const loadFilesUrl = "{{ route('admin.file.iframe.load') }}";
            const filesLoader = document.getElementById('filesLoader');
            const filesEnd = document.getElementById('filesEnd');
            let nextPage = {{ $files->currentPage() + 1 }};
            let hasMorePages = @json($files->hasMorePages());
            let isLoadingPage = false;

            function insertGridFiles(html) {
                const wrapper = document.createElement('div');
                wrapper.innerHTML = html;
                Array.from(wrapper.children).forEach(function(node) {
                    // Skip duplicates already rendered (e.g. just-uploaded files)
                    if (node.id && document.getElementById(node.id)) {
                        return;
                    }
                    if (gridEmptyState) {
                        fileGridBody.insertBefore(node, gridEmptyState);
                    } else {
                        fileGridBody.appendChild(node);
                    }
                });
            }

            async function loadNextPage() {
                if (isLoadingPage || !hasMorePages) {
                    return;
                }
                isLoadingPage = true;
                if (filesLoader) filesLoader.classList.remove('d-none');

                try {
                    const response = await fetch(`${loadFilesUrl}?page=${nextPage}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!response.ok) {
                        throw new Error(`Failed to load files: ${response.statusText}`);
                    }
                    const data = await response.json();

                    if (data.gridHtml) {
                        insertGridFiles(data.gridHtml);
                    }

                    hasMorePages = data.hasMore;
                    nextPage = data.nextPage;
                    updateFileCount();

                    // Re-highlight any newly loaded files that are already selected (multi mode)
                    if (typeof window.__applySelectionHighlights === 'function') {
                        window.__applySelectionHighlights();
                    }

                    if (!hasMorePages && filesEnd) {
                        filesEnd.classList.remove('d-none');
                    }
                } catch (error) {
                    console.error('Pagination error:', error);
                } finally {
                    isLoadingPage = false;
                    if (filesLoader) filesLoader.classList.add('d-none');

                    // The freshly loaded page may still not fill the viewport.
                    maybeLoadMore();
                }
            }

            function maybeLoadMore() {
                if (!hasMorePages || isLoadingPage) {
                    return;
                }
                const scrollPosition = window.innerHeight + window.scrollY;
                const threshold = document.documentElement.scrollHeight - 300;
                if (scrollPosition >= threshold) {
                    loadNextPage();
                }
            }

            window.addEventListener('scroll', maybeLoadMore, { passive: true });
            window.addEventListener('resize', maybeLoadMore, { passive: true });
            // Kick off in case the first 30 don't fill the iframe height.
            maybeLoadMore();

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
                        updateThumbnail(fileData);
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
                    updateThumbnail(fileData);
                    URL.revokeObjectURL(video.src);
                };
            }

            function updateThumbnail(fileData) {
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
                        updateGridProgress(fileData.id, progress);

                        if (result.completed) {
                            fileData.status = 'completed';
                            fileData.serverPath = result.path;
                            fileData.db_id = result.fileId;
                            fileData.serverThumbnailUrl = result.thumbnailUrl;
                            fileData.url = result.url || result.thumbnailUrl || result.path || '';

                            updateElementIds(fileData);

                            fileData.id = fileData.db_id;
                            showGridOverlay(fileData.db_id, false);

                            if (fileData.thumbnailUrl) {
                                try {
                                    const thumbRes = await uploadThumbnail(fileData);
                                    if (thumbRes && thumbRes.thumbnailUrl) {
                                        fileData.serverThumbnailUrl = thumbRes.thumbnailUrl;
                                        fileData.thumbnailUrl = thumbRes.thumbnailUrl;
                                        updateThumbnail(fileData);
                                    }
                                } catch (e) {
                                    console.error('Thumbnail upload failed', e);
                                }
                            }

                            ensureSelectButton(fileData);
                        }
                    }
                } catch (error) {
                    console.error('Upload error:', error);
                    fileData.status = 'failed';
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
                const oldGrid = document.getElementById(`grid-file-${fileData.id}`);
                if (oldGrid) oldGrid.id = `grid-file-${fileData.db_id}`;

                const oldOverlayId = document.getElementById(`grid-overlay-${fileData.id}`);
                if (oldOverlayId) oldOverlayId.id = `grid-overlay-${fileData.db_id}`;

                const oldProgressId = document.getElementById(`grid-progress-${fileData.id}`);
                if (oldProgressId) oldProgressId.id = `grid-progress-${fileData.db_id}`;

                const gridCard = document.getElementById(`grid-file-${fileData.db_id}`);
                if (gridCard) {
                    gridCard.querySelectorAll('button').forEach(btn => {
                        const onclick = btn.getAttribute('onclick');
                        if (onclick) {
                            if (onclick.includes('viewFile')) btn.setAttribute('onclick', `viewFile('${fileData.db_id}')`);
                            if (onclick.includes('downloadFile')) btn.setAttribute('onclick', `downloadFile('${fileData.db_id}')`);
                            if (onclick.includes('deleteFile')) btn.setAttribute('onclick', `deleteFile('${fileData.db_id}')`);
                        }
                        btn.disabled = false;
                    });
                }
            }

            function ensureSelectButton(fileData) {
                const finalId = fileData.db_id || fileData.id;
                const fileUrl = fileData.serverThumbnailUrl || fileData.serverPath || fileData.url || '';

                const gridItem = document.getElementById(`grid-file-${finalId}`);
                if (gridItem) {
                    const actions = gridItem.querySelector('.card-body .d-flex.gap-1');
                    if (actions) {
                        let selectBtn = actions.querySelector('.select-file-btn');
                        if (!selectBtn) {
                            selectBtn = document.createElement('button');
                            selectBtn.type = 'button';
                            selectBtn.className = 'btn btn-sm btn-outline-primary flex-fill select-file-btn';
                            selectBtn.textContent = 'Select';
                            actions.prepend(selectBtn);
                        }

                        selectBtn.dataset.id = finalId;
                        selectBtn.dataset.originalName = fileData.name;
                        selectBtn.dataset.url = fileUrl;
                    }
                }
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

                if (gridEmptyState) {
                    fileGridBody.insertBefore(col, gridEmptyState);
                } else {
                    fileGridBody.prepend(col);
                }
            }

            function showGridOverlay(fileId, show) {
                const overlay = document.getElementById(`grid-overlay-${fileId}`);
                if (overlay) {
                    overlay.classList.toggle('d-none', !show);
                }
            }

            function updateGridProgress(fileId, progress) {
                const progressEl = document.getElementById(`grid-progress-${fileId}`);
                if (progressEl) {
                    progressEl.textContent = progress + '%';
                }
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
            }

            function updateFileCount() {
                const totalFiles = document.querySelectorAll('.file-grid-item').length;
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
                                const gridItem = document.getElementById(`grid-file-${fileId}`);
                                if (gridItem) gridItem.remove();

                                updateFileCount();

                                const remainingFiles = document.querySelectorAll('.file-grid-item').length;
                                if (remainingFiles === 0 && gridEmptyState) {
                                    gridEmptyState.classList.remove('d-none');
                                }
                            }
                        })
                        .catch(error => {
                            alert('Failed to delete file: ' + error.message);
                        });
                }
            };

            // Initial count from server-rendered items
            updateFileCount();
        });
    </script>
@endsection
