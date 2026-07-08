@extends('public.layouts.main')

@section('title')
    Blog - Pure and Preloved
@endsection

@section('meta_description', 'News, guides and stories from Pure and Preloved.')

@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            @if($activeCategory)
                                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                                <li>{{ $activeCategory->name }}</li>
                            @elseif($activeTag)
                                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                                <li>Tag: {{ $activeTag->name }}</li>
                            @elseif($search)
                                <li><a href="{{ route('blog.index') }}">Blog</a></li>
                                <li>Search: “{{ $search }}”</li>
                            @else
                                <li>Blog</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->

    <div class="shop-category-area blog-grid mtb-50px">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12 ">
                    @forelse($posts as $post)
                        <div class="row mb-50px">
                            <div class="col-lg-5 col-md-6">
                                <div class="single-blog-post blog-list-post">
                                    <div class="blog-post-media">
                                        <div class="blog-image">
                                            <a href="{{ route('blog.show', $post->slug) }}"><img class="img-responsive" src="{{ $post->featuredImage->public_url ?? asset('assets/images/blog-image/blog-1.jpg') }}" alt="{{ $post->title }}" /></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-6 align-self-center align-items-center">
                                <div class="blog-post-content-inner mt-lm-30px">
                                    <h4 class="blog-title"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h4>
                                    <ul class="blog-page-meta">
                                        <li>
                                            <a href="#"><i class="ion-person"></i> {{ $post->author->name ?? 'Admin' }}</a>
                                        </li>
                                        <li>
                                            <a href="#"><i class="ion-calendar"></i> {{ $post->display_date->format('d F, Y') }}</a>
                                        </li>
                                    </ul>
                                    <p>
                                        {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 180) }}
                                    </p>
                                    <a class="read-more-btn" href="{{ route('blog.show', $post->slug) }}"> Read More <i class="ion-android-arrow-dropright-circle"></i></a>
                                </div>
                            </div>
                            <!-- single blog post -->
                        </div>
                    @empty
                        <div class="row mb-50px">
                            <div class="col-12">
                                <p>No blog posts found. Please check back soon.</p>
                            </div>
                        </div>
                    @endforelse

                    @if($posts->hasPages())
                        <!--  Pagination Area Start -->
                        <div class="pro-pagination-style text-center">
                            {{ $posts->links('vendor.pagination.custom') }}
                        </div>
                        <!--  Pagination Area End -->
                    @endif
                </div>
                <!-- Sidebar Area Start -->
                <div class="col-lg-3 col-md-12 mt-lm-50px mt-md-50px">
                    @include('public.blog.partials.sidebar')
                </div>
                <!-- Sidebar Area End -->
            </div>
        </div>
    </div>
@endsection
