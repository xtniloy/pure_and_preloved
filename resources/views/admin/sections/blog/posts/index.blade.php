@extends('admin.layout.main')
@section('page-title')
    Blog Posts
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Blog Posts
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a></li>
                <li class="breadcrumb-item active">Blog Posts</li>
            </ol>
        </nav>
        @include('partials.notification')
        <div class="row ">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Posts</strong>
                        </div>
                        <span class="small ms-1">Total: {{$posts->total()??0}}</span>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mx-2 mb-3 g-2">
                            <div class="col-12 col-lg">
                                <form action="{{ route('admin.blog-posts.index') }}" method="get" class="row g-2">
                                    <div class="col-12 col-md-4">
                                        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Search by title...">
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <select class="form-select" name="status">
                                            <option value="">All statuses</option>
                                            @foreach(\App\Models\BlogPost::statuses() as $value => $label)
                                                <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <select class="form-select" name="category">
                                            <option value="">All categories</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-auto ms-auto">
                                <a href="{{route('admin.blog-posts.create')}}">
                                    <button class="btn btn-outline-secondary">
                                        <svg class="icon me-2">
                                            <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-plus')}}"></use>
                                        </svg>
                                        Add
                                    </button>
                                </a>
                            </div>
                        </div>

                        <div class="card border">
                            <div class="p-3" role="tabpanel">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Post</th>
                                        <th scope="col">Categories</th>
                                        <th scope="col">Comments</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Published</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($posts as $k => $post)
                                        <tr class="align-middle">
                                            <th scope="row">{{$posts->firstItem() + $k}}</th>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($post->featured_image_id)
                                                        <img src="{{ route('admin.file.view', ['fileId' => $post->featured_image_id]) }}" class="img-thumbnail" style="width: 48px; height: 48px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold">{{ $post->title }}</div>
                                                        <code class="small">{{ $post->slug }}</code>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @forelse($post->categories as $category)
                                                    <span class="badge bg-secondary">{{ $category->name }}</span>
                                                @empty
                                                    <span class="text-body-secondary small">—</span>
                                                @endforelse
                                            </td>
                                            <td>{{ $post->comments_count }}</td>
                                            <td>
                                                @if($post->status === \App\Models\BlogPost::STATUS_PUBLISHED)
                                                    <span class="badge bg-success">Published</span>
                                                @elseif($post->status === \App\Models\BlogPost::STATUS_PRIVATE)
                                                    <span class="badge bg-warning text-dark">Private</span>
                                                @else
                                                    <span class="badge bg-secondary">Draft</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($post->published_at)
                                                    <span class="small">{{ $post->published_at->format('d M Y, H:i') }}</span>
                                                @else
                                                    <span class="text-body-secondary small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @if($post->status === \App\Models\BlogPost::STATUS_PUBLISHED)
                                                        <a href="{{route('blog.show', $post->slug)}}" target="_blank" class="btn btn-sm btn-outline-secondary me-2" title="View">
                                                            <svg class="icon">
                                                                <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-external-link')}}"></use>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    <a href="{{route('admin.blog-posts.edit', $post->id)}}" class="btn btn-sm btn-outline-primary me-2">
                                                        <svg class="icon">
                                                            <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-pencil')}}"></use>
                                                        </svg>
                                                    </a>
                                                    <form action="{{route('admin.blog-posts.destroy', $post->id)}}" method="POST" onsubmit="return confirm('Are you sure? Comments on this post will also be deleted.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <svg class="icon">
                                                                <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-trash')}}"></use>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No blog posts found.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $posts->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
