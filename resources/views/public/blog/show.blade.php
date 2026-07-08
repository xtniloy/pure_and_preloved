@extends('public.layouts.main')

@section('title')
    {{ $post->meta_title ?: $post->title }}
@endsection

@section('meta_description', $post->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->body), 160))

@section('meta')
    @if($post->meta_keywords)
        <meta name="keywords" content="{{ $post->meta_keywords }}">
    @endif
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $post->meta_title ?: $post->title }}">
    <meta property="og:description" content="{{ $post->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->body), 160) }}">
    <meta property="og:url" content="{{ route('blog.show', $post->slug) }}">
    @php $ogImage = $post->metaImage->public_url ?? $post->featuredImage->public_url ?? null; @endphp
    @if($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
@endsection

@section('content')
    <!-- Breadcrumb Area Start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li><a href="{{ route('blog.index') }}">Blog</a></li>
                            <li>{{ $post->title }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Area End-->

    <style>
        /* Rich-text body coming from the admin editor */
        .single-post-content p { margin-bottom: 1rem; }
        .single-post-content ul, .single-post-content ol { list-style-position: outside; padding-left: 1.25rem; margin-bottom: 1rem; }
        .single-post-content ul { list-style-type: disc; }
        .single-post-content li { display: list-item; margin-bottom: .35rem; }
        .single-post-content h1, .single-post-content h2, .single-post-content h3,
        .single-post-content h4, .single-post-content h5 { margin: 1.5rem 0 1rem; }
        .single-post-content blockquote { border-left: 4px solid #eee; padding-left: 1rem; margin: 1.5rem 0; font-style: italic; }
        .single-post-content img { max-width: 100%; height: auto; }
        .single-post-content table { width: 100%; margin-bottom: 1rem; border-collapse: collapse; }
        .single-post-content table td, .single-post-content table th { border: 1px solid #eee; padding: .5rem; }
        .comment-alert { margin-bottom: 20px; }
        #reply-indicator { margin-bottom: 15px; }
        .review-img img { width: 70px; height: 70px; border-radius: 50%; object-fit: cover; }
    </style>

    <div class="shop-category-area single-blog-page mtb-50px">
        <div class="container">
            <div class="row">
                <div class="col-lg-9  col-md-12">
                    <div class="blog-posts ">
                        <div class="single-blog-post blog-grid-post">
                            @if($post->featuredImage)
                                <div class="blog-post-media">
                                    <div class="blog-image single-blog">
                                        <img src="{{ $post->featuredImage->public_url }}" alt="{{ $post->title }}" />
                                    </div>
                                </div>
                            @endif
                            <div class="blog-post-content-inner mt-30px">
                                <h4 class="blog-title">{{ $post->title }}</h4>
                                <ul class="blog-page-meta">
                                    <li>
                                        <a href="#"><i class="ion-person"></i> {{ $post->author->name ?? 'Admin' }}</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="ion-calendar"></i> {{ $post->display_date->format('d F, Y') }}</a>
                                    </li>
                                    @if($post->categories->count())
                                        <li>
                                            <a href="{{ route('blog.index', ['category' => $post->categories->first()->slug]) }}"><i class="ion-folder"></i> {{ $post->categories->pluck('name')->implode(', ') }}</a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="single-post-content">
                                {!! $post->body !!}
                            </div>
                        </div>
                        <!-- single blog post -->
                    </div>

                    <div class="blog-single-tags-share d-sm-flex justify-content-between">
                        @if($post->tags->count())
                            <div class="blog-single-tags d-flex">
                                <span class="title">Tags: </span>
                                <ul class="tag-list">
                                    @foreach($post->tags as $tag)
                                        <li><a href="{{ route('blog.index', ['tag' => $tag->slug]) }}">{{ $tag->name }}{{ !$loop->last ? ',' : '' }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="blog-single-share d-flex">
                            <span class="title">Share:</span>
                            @php
                                $shareUrl = urlencode(route('blog.show', $post->slug));
                                $shareTitle = urlencode($post->title);
                            @endphp
                            <ul class="social">
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener"><i class="ion-social-facebook"></i></a>
                                </li>
                                <li>
                                    <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener"><i class="ion-social-twitter"></i></a>
                                </li>
                                <li>
                                    <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener"><i class="ion-social-whatsapp"></i></a>
                                </li>
                                <li>
                                    <a href="mailto:?subject={{ $shareTitle }}&body={{ $shareUrl }}"><i class="ion-email"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @if($relatedPosts->count())
                        <div class="blog-related-post">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <!-- Section Title -->
                                    <div class="section-title underline-shape">
                                        <h2>Related Post</h2>
                                    </div>
                                    <!-- Section Title -->
                                </div>
                            </div>
                            <div class="row">
                                @foreach($relatedPosts as $related)
                                    <div class="col-md-4 {{ !$loop->last ? 'mb-lm-30px' : '' }}">
                                        <div class="blog-post-media">
                                            <div class="blog-image single-blog">
                                                <a href="{{ route('blog.show', $related->slug) }}"><img class="img-responsive" src="{{ $related->featuredImage->public_url ?? asset('assets/images/blog-image/blog-1.jpg') }}" alt="{{ $related->title }}" /></a>
                                            </div>
                                        </div>
                                        <div class="blog-post-content-inner mt-30px">
                                            <h4 class="blog-title"><a href="{{ route('blog.show', $related->slug) }}">{{ $related->title }}</a></h4>
                                            <ul class="blog-page-meta">
                                                <li>
                                                    <a href="#"><i class="ion-person"></i> {{ $related->author->name ?? 'Admin' }}</a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="ion-calendar"></i> {{ $related->display_date->format('d F, Y') }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="comment-area" id="comments">
                        <h2 class="comment-heading">{{ $commentCount }} {{ \Illuminate\Support\Str::plural('Comment', $commentCount) }}</h2>
                        @if($comments->count())
                            <div class="review-wrapper">
                                @foreach($comments as $comment)
                                    <div class="single-review">
                                        <div class="review-img">
                                            <img src="{{ $comment->user->avatar_url ?? 'https://ui-avatars.com/api/?name=Deleted+user&background=6b7280&color=ffffff&bold=true' }}" alt="{{ $comment->user->name ?? 'Deleted user' }}" />
                                        </div>
                                        <div class="review-content">
                                            <div class="review-top-wrap">
                                                <div class="review-left">
                                                    <div class="review-name">
                                                        <h4>{{ $comment->user->name ?? 'Deleted user' }}</h4>
                                                        <span class="date">{{ $comment->created_at->format('F d, Y \a\t g:i a') }}</span>
                                                    </div>
                                                </div>
                                                <div class="review-left">
                                                    @auth
                                                        @if($post->allow_comments)
                                                            <a href="#comment-form" class="reply-link" data-comment-id="{{ $comment->id }}" data-comment-author="{{ $comment->user->name ?? 'Deleted user' }}">Reply</a>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('login') }}">Reply</a>
                                                    @endauth
                                                </div>
                                            </div>
                                            <div class="review-bottom">
                                                <p>{{ $comment->body }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    @foreach($comment->replies as $reply)
                                        <div class="single-review child-review">
                                            <div class="review-img">
                                                <img src="{{ $reply->user->avatar_url ?? 'https://ui-avatars.com/api/?name=Deleted+user&background=6b7280&color=ffffff&bold=true' }}" alt="{{ $reply->user->name ?? 'Deleted user' }}" />
                                            </div>
                                            <div class="review-content">
                                                <div class="review-top-wrap">
                                                    <div class="review-left">
                                                        <div class="review-name">
                                                            <h4>{{ $reply->user->name ?? 'Deleted user' }}</h4>
                                                            <span class="date">{{ $reply->created_at->format('F d, Y \a\t g:i a') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="review-left">
                                                        @auth
                                                            @if($post->allow_comments)
                                                                <a href="#comment-form" class="reply-link" data-comment-id="{{ $comment->id }}" data-comment-author="{{ $reply->user->name ?? 'Deleted user' }}">Reply</a>
                                                            @endif
                                                        @else
                                                            <a href="{{ route('login') }}">Reply</a>
                                                        @endauth
                                                    </div>
                                                </div>
                                                <div class="review-bottom">
                                                    <p>{{ $reply->body }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="blog-comment-form" id="comment-form">
                        <h2 class="comment-heading">Leave a Reply</h2>

                        @if(session('comment_success'))
                            <div class="alert alert-success comment-alert">{{ session('comment_success') }}</div>
                        @endif
                        @if(session('comment_error'))
                            <div class="alert alert-danger comment-alert">{{ session('comment_error') }}</div>
                        @endif

                        @if(!$post->allow_comments)
                            <p>Comments are closed for this post.</p>
                        @else
                            @auth
                                <p>Commenting as <strong>{{ auth()->user()->name }}</strong>. Your email address will not be published.</p>

                                @error('body')
                                    <div class="alert alert-danger comment-alert">{{ $message }}</div>
                                @enderror

                                <form action="{{ route('blog.comments.store', $post->slug) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="parent_id" id="parent_id" value="">
                                    <div class="alert alert-info comment-alert d-none" id="reply-indicator">
                                        Replying to <strong id="reply-to-name"></strong> — <a href="#" id="cancel-reply">Cancel</a>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="single-form">
                                                <label>Your Comment:</label>
                                                <textarea name="body" placeholder="Write a comment" required minlength="2" maxlength="2000">{{ old('body') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="single-form">
                                                <input class="submit" type="submit" value="Post Comment" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <p>Only registered customers can comment. Please <a href="{{ route('login') }}"><u>log in</u></a> or <a href="{{ route('registration') }}"><u>create an account</u></a> to join the conversation.</p>
                            @endauth
                        @endif
                    </div>
                </div>
                <!-- Sidebar Area Start -->
                <div class="col-lg-3 mt-lm-50px mt-md-50px">
                    @include('public.blog.partials.sidebar')
                </div>
                <!-- Sidebar Area End -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const parentInput = document.getElementById('parent_id');
            const indicator = document.getElementById('reply-indicator');
            const replyToName = document.getElementById('reply-to-name');
            const cancelReply = document.getElementById('cancel-reply');

            if (!parentInput) {
                return;
            }

            document.querySelectorAll('.reply-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    parentInput.value = this.getAttribute('data-comment-id');
                    replyToName.textContent = this.getAttribute('data-comment-author');
                    indicator.classList.remove('d-none');
                });
            });

            if (cancelReply) {
                cancelReply.addEventListener('click', function (e) {
                    e.preventDefault();
                    parentInput.value = '';
                    indicator.classList.add('d-none');
                });
            }
        });
    </script>
@endpush
