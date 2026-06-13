@extends('admin.layout.main')
@section('page-title')
    Category Management
@endsection

@php
    $genderMeta = \App\Enum\General::$gender_meta;
@endphp

@push('css')
    <style>
        .category-gender-card {
            cursor: pointer;
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
            border: 1px solid var(--cui-border-color);
        }
        .category-gender-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .12);
            border-color: #0f766f;
        }
        .category-gender-icon {
            width: 72px;
            height: 72px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(15, 118, 111, .12);
            color: #0f766f;
        }
        .category-gender-icon .icon { width: 36px; height: 36px; }
        .bg-brand-soft { background: rgba(15, 118, 111, .12); color: #0f766f; }
    </style>
@endpush

@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold">Category Management</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Category Management</li>
            </ol>
        </nav>

        @include('partials.notification')

        <p class="text-body-secondary mb-4">Choose a group to manage its categories.</p>

        <div class="row g-4">
            @foreach($genders as $gender)
                @php $meta = $genderMeta[$gender] ?? ['label' => ucfirst($gender), 'icon' => 'cil-folder', 'desc' => '']; @endphp
                <div class="col-sm-6 col-lg-4">
                    <a href="{{ route('admin.categories.index', ['gender' => $gender]) }}" class="text-decoration-none text-body">
                        <div class="card category-gender-card h-100">
                            <div class="card-body d-flex align-items-center gap-3 p-4">
                                <span class="category-gender-icon flex-shrink-0">
                                    <svg class="icon"><use xlink:href="{{ asset('panel/assets/vendors/@coreui/icons/svg/free.svg#'.$meta['icon']) }}"></use></svg>
                                </span>
                                <div>
                                    <div class="fs-4 fw-semibold">{{ $meta['label'] }}</div>
                                    <div class="small text-body-secondary">{{ $meta['desc'] }}</div>
                                    <div class="mt-2"><span class="badge bg-brand-soft">{{ $counts[$gender] ?? 0 }} categories</span></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
