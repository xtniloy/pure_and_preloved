@extends('public.layouts.main')
@section('title')
    {{ $page->meta_title ?: $page->title }}
@endsection
@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>{{ $page->title }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .cms-page p { margin-bottom: 1rem; }
        .cms-page ul, .cms-page ol { list-style-position: outside; padding-left: 1.25rem; margin-bottom: 1rem; }
        .cms-page ul { list-style-type: disc; }
        .cms-page ul ul { list-style-type: circle; margin-top: .5rem; }
        .cms-page ol { list-style-type: decimal; }
        .cms-page li { display: list-item; margin-bottom: .35rem; }
        .cms-page li > ul, .cms-page li > ol { margin-top: .5rem; margin-bottom: .5rem; }
    </style>

    <div class="container mb-50px mt-50px cms-page">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="mb-4 fw-bold">{{ $page->title }}</h1>
                        {!! $page->body !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
