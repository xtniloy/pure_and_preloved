@extends('admin.layout.main')
@section('page-title')
    Contact Messages
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold" data-coreui-i18n="dashboard">
            Contact Messages
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" data-coreui-i18n="home">Home</a></li>
                <li class="breadcrumb-item active">Contact Messages</li>
            </ol>
        </nav>
        @include('partials.notification')
        <div class="row ">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Messages</strong>
                            @if($unreadCount > 0)
                                <span class="badge bg-danger ms-2">{{ $unreadCount }} unread</span>
                            @endif
                        </div>
                        <span class="small ms-1">Total: {{$messages->total()??0}}</span>
                    </div>
                    <div class="card-body">
                        <div class="card border">
                            <div class="p-3" role="tabpanel">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Received</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($messages as $k => $msg)
                                        <tr class="align-middle {{ $msg->is_read ? '' : 'fw-semibold' }}">
                                            <th scope="row">{{$messages->firstItem() + $k}}</th>
                                            <td>
                                                @unless($msg->is_read)
                                                    <span class="badge bg-danger me-1">New</span>
                                                @endunless
                                                {{$msg->name}}
                                            </td>
                                            <td>{{$msg->email}}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($msg->subject ?: '—', 40) }}</td>
                                            <td><small class="text-medium-emphasis">{{$msg->created_at->format('Y-m-d H:i')}}</small></td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{route('admin.contact-messages.show', $msg->id)}}" class="btn btn-sm btn-outline-primary me-2">
                                                        <svg class="icon">
                                                            <use xlink:href="{{asset('panel/assets/vendors/@coreui/icons/svg/free.svg#cil-magnifying-glass')}}"></use>
                                                        </svg>
                                                    </a>
                                                    <form action="{{route('admin.contact-messages.destroy', $msg->id)}}" method="POST" onsubmit="return confirm('Are you sure?')">
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
                                            <td colspan="6" class="text-center">No messages yet.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $messages->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
