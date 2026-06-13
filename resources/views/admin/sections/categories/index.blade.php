@extends('admin.layout.main')
@section('page-title')
    Category Management
@endsection
@php
    $genderLabel = \App\Enum\General::$gender_meta[$gender]['label'] ?? ucfirst($gender);
    $ancestors = isset($parent) ? $parent->ancestors() : collect();
    $backUrl = isset($parent)
        ? ($parent->parent_id
            ? route('admin.categories.index', ['parent_id' => $parent->parent_id])
            : route('admin.categories.index', ['gender' => $parent->gender]))
        : route('admin.categories.index');
@endphp

@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold">
            {{ $genderLabel }}
            @if(isset($parent))
                <small class="text-body-secondary fs-5">/ {{ $parent->name }}</small>
            @endif
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.categories.index')}}">Categories</a></li>
                <li class="breadcrumb-item {{ isset($parent) ? '' : 'active' }}">
                    <a href="{{route('admin.categories.index', ['gender' => $gender])}}">{{ $genderLabel }}</a>
                </li>
                @foreach($ancestors as $ancestor)
                    <li class="breadcrumb-item"><a href="{{route('admin.categories.index', ['parent_id' => $ancestor->id])}}">{{ $ancestor->name }}</a></li>
                @endforeach
                @if(isset($parent))
                    <li class="breadcrumb-item active">{{ $parent->name }}</li>
                @endif
            </ol>
        </nav>
        @include('partials.notification')
        <div class="row ">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ isset($parent) ? $parent->name . ' — subcategories' : $genderLabel }}</strong>
                            @if(isset($parent))
                                <span class="badge bg-info ms-2">Parent: {{ $parent->name }}</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="small">Total: {{$categories->total()??0}}</span>
                            <a href="{{ $backUrl }}" class="btn btn-sm btn-outline-secondary">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mx-2 mb-3">
                            <div class="col-auto ml-0 ml-lg-auto text-left text-lg-right mt-3 mt-lg-0 ms-auto">
                                <a href="{{route('admin.categories.create', isset($parent) ? ['parent_id' => $parent->id] : ['gender' => $gender])}}">
                                    <button class="btn btn-outline-secondary">
                                        <svg class="icon me-2">
                                            <use
                                                xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-plus')}}"></use>
                                        </svg>
                                        {{ isset($parent) ? 'Add subcategory' : 'Add category' }}
                                    </button>
                                </a>
                                <span id="order-status" class="ms-2 small text-body-secondary align-middle d-none"></span>
                            </div>
                        </div>

                        <div class="card border">
                            <div class=" p-3 " role="tabpanel">
                                <form action="{{ route('admin.categories.update_order') }}" method="POST" id="orderForm">
                                    @csrf
                                <table class="table table-hover" id="sortable-table">
                                    <thead>
                                    <tr>
                                        <th scope="col" style="width: 50px;"></th>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col" style="width: 100px;">Order</th>
                                        <th scope="col">Parent</th>
                                        <th scope="col">Gender</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="category-list" data-first-item="{{ $categories->firstItem() ?? 1 }}">
                                    @foreach($categories as $k=> $category)
                                        <tr class="align-middle" data-id="{{ $category->id }}">
                                            <td class="cursor-grab text-center">
                                                <svg class="icon text-secondary">
                                                    <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-cursor-move')}}"></use>
                                                </svg>
                                            </td>
                                            <th scope="row">{{$categories->firstItem() + $k}}</th>
                                            <td>
                                                @php $thumb = $category->asset?->thumbnail_url ?? $category->asset?->url; @endphp
                                                @if($thumb)
                                                    <img src="{{ $thumb }}" alt="{{ $category->name }}" class="rounded" style="width: 32px; height: 32px; object-fit: cover; margin-right: 6px;">
                                                @else
                                                    <span class="d-inline-flex align-items-center justify-content-center rounded bg-brand-soft text-body-secondary" style="width: 32px; height: 32px; margin-right: 6px;">
                                                        <svg class="icon icon-sm"><use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-image')}}"></use></svg>
                                                    </span>
                                                @endif
                                                <a href="{{ route('admin.categories.index', ['parent_id' => $category->id]) }}" class="text-decoration-none fw-medium">
                                                    {{$category->name}}
                                                </a>
                                                @if(($category->children_count ?? 0) > 0)
                                                    <span class="badge bg-brand-soft ms-1">{{ $category->children_count }} sub</span>
                                                @endif
                                                <svg class="icon icon-sm text-body-secondary ms-1"><use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-chevron-right')}}"></use></svg>
                                            </td>
                                            <td>
                                                <input type="hidden" name="categories[{{ $k }}][id]" value="{{ $category->id }}">
                                                <input type="number" name="categories[{{ $k }}][sort_order]" value="{{ $category->sort_order }}" class="form-control form-control-sm sort-order-input" style="width: 80px;" readonly>
                                            </td>
                                            <td>{{$category->parent ? $category->parent->name : '-'}}</td>
                                            <td>{{ucfirst($category->gender)}}</td>
                                            <td>
                                                @if($category->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('admin.categories.edit', $category->id)}}" class="btn btn-sm btn-primary">Edit</a>
                                                {{-- Separate delete button to avoid nested forms --}}
                                                <button type="button" class="btn btn-sm btn-danger" onclick="if(confirm('Are you sure?')) { document.getElementById('delete-form-{{$category->id}}').submit(); }">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                </form>
                                {{-- Hidden delete forms outside the main form --}}
                                @foreach($categories as $category)
                                    <form id="delete-form-{{$category->id}}" action="{{route('admin.categories.destroy', $category->id)}}" method="POST" style="display:none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endforeach
                            </div>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $categories->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var list = document.getElementById('category-list');
            if (!list) return;

            // Continue numbering across pages (e.g. page 2 starts at 31, not 1).
            var firstItem = parseInt(list.dataset.firstItem, 10) || 1;
            var statusEl = document.getElementById('order-status');
            var saveUrl = "{{ route('admin.categories.update_order') }}";
            var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            Sortable.create(list, {
                handle: '.cursor-grab',
                animation: 150,
                ghostClass: 'bg-brand-soft',
                onEnd: function () {
                    renumber();
                    saveOrder();
                }
            });

            function renumber() {
                list.querySelectorAll('tr').forEach(function (row, index) {
                    var input = row.querySelector('.sort-order-input');
                    if (input) input.value = firstItem + index;
                });
            }

            function setStatus(message, type) {
                if (!statusEl) return;
                statusEl.textContent = message;
                statusEl.className = 'ms-2 small align-middle text-' + type;
            }

            function saveOrder() {
                var categories = [];
                list.querySelectorAll('tr[data-id]').forEach(function (row, index) {
                    categories.push({ id: row.dataset.id, sort_order: firstItem + index });
                });

                setStatus('Saving order…', 'body-secondary');

                fetch(saveUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ categories: categories })
                })
                .then(function (res) {
                    if (!res.ok) throw new Error('Request failed');
                    return res.json();
                })
                .then(function () {
                    setStatus('✓ Order saved', 'success');
                    setTimeout(function () { if (statusEl) statusEl.classList.add('d-none'); }, 2000);
                })
                .catch(function () {
                    setStatus('✗ Could not save order — please retry', 'danger');
                });
            }
        });
    </script>
    <style>
        .cursor-grab {
            cursor: grab;
        }
        .cursor-grab:active {
            cursor: grabbing;
        }
        .bg-brand-soft { background: rgba(15, 118, 111, .12); color: #0f766f; }
    </style>
@endpush
