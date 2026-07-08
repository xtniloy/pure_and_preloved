@extends('admin.layout.main')
@section('page-title')
    Blog Comments
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Blog Comments
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a></li>
                <li class="breadcrumb-item active">Blog Comments</li>
            </ol>
        </nav>
        @include('partials.notification')
        <div class="row ">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Comments</strong>
                        </div>
                        <span class="small ms-1">Total: {{$comments->total()??0}}</span>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center mx-2 mb-3">
                            <div class="col-12">
                                <form action="{{ route('admin.blog-comments.index') }}" method="get" class="row g-2">
                                    <div class="col-12 col-md-5">
                                        <select class="form-select" name="post">
                                            <option value="">All posts</option>
                                            @foreach($posts as $post)
                                                <option value="{{ $post->id }}" {{ request('post') == $post->id ? 'selected' : '' }}>{{ $post->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <select class="form-select" name="status">
                                            <option value="">All statuses</option>
                                            <option value="visible" {{ request('status') === 'visible' ? 'selected' : '' }}>Visible</option>
                                            <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Hidden</option>
                                        </select>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card border">
                            <div class="p-3" role="tabpanel">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Comment</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Post</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($comments as $k => $comment)
                                        <tr class="align-middle">
                                            <th scope="row">{{$comments->firstItem() + $k}}</th>
                                            <td style="max-width: 320px;">
                                                @if($comment->parent_id)
                                                    <span class="badge bg-light text-dark border me-1" title="Reply to another comment">↳ Reply</span>
                                                @endif
                                                {{ \Illuminate\Support\Str::limit($comment->body, 120) }}
                                            </td>
                                            <td>{{ $comment->user->name ?? 'Deleted user' }}</td>
                                            <td>
                                                @if($comment->post)
                                                    @if($comment->post->status === \App\Models\BlogPost::STATUS_PUBLISHED)
                                                        <a href="{{ route('blog.show', $comment->post->slug) }}" target="_blank">{{ \Illuminate\Support\Str::limit($comment->post->title, 40) }}</a>
                                                    @else
                                                        {{ \Illuminate\Support\Str::limit($comment->post->title, 40) }}
                                                    @endif
                                                @else
                                                    <span class="text-body-secondary small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($comment->status)
                                                    <span class="badge bg-success">Visible</span>
                                                @else
                                                    <span class="badge bg-danger">Hidden</span>
                                                @endif
                                            </td>
                                            <td><span class="small">{{ $comment->created_at->format('d M Y, H:i') }}</span></td>
                                            <td>
                                                <div class="d-flex">
                                                    <form action="{{ route('admin.blog-comments.toggle', $comment->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        @if($comment->status)
                                                            <button type="submit" class="btn btn-sm btn-outline-warning me-2" title="Hide from the site">
                                                                <svg class="icon">
                                                                    <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-ban')}}"></use>
                                                                </svg>
                                                            </button>
                                                        @else
                                                            <button type="submit" class="btn btn-sm btn-outline-success me-2" title="Show on the site">
                                                                <svg class="icon">
                                                                    <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-check-circle')}}"></use>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </form>
                                                    <form action="{{ route('admin.blog-comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Are you sure? Replies to this comment will also be deleted.')">
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
                                            <td colspan="7" class="text-center">No comments found.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $comments->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
