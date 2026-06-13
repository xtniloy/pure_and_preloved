@extends('admin.layout.main')
@section('page-title')
    Contact Message
@endsection
@section('content')
    <div class="container-lg px-4">
        <div class="fs-2 fw-semibold">Contact Message</div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.contact-messages.index')}}">Contact Messages</a></li>
                <li class="breadcrumb-item active">#{{ $contactMessage->id }}</li>
            </ol>
        </nav>
        @include('partials.notification')

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>{{ $contactMessage->subject ?: 'No subject' }}</strong>
                        <small class="text-medium-emphasis">{{ $contactMessage->created_at->format('d M Y, H:i') }}</small>
                    </div>
                    <div class="card-body">
                        <p style="white-space: pre-wrap;">{{ $contactMessage->message }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Sender</div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Name:</strong> {{ $contactMessage->name }}</p>
                        <p class="mb-1"><strong>Email:</strong> <a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></p>
                        @if($contactMessage->phone)
                            <p class="mb-1"><strong>Phone:</strong> {{ $contactMessage->phone }}</p>
                        @endif
                        <hr>
                        <a href="mailto:{{ $contactMessage->email }}?subject=RE: {{ $contactMessage->subject }}" class="btn btn-primary w-100 mb-2">Reply by Email</a>
                        <form action="{{route('admin.contact-messages.destroy', $contactMessage->id)}}" method="POST" onsubmit="return confirm('Delete this message?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">Delete</button>
                        </form>
                    </div>
                </div>
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary w-100">Back to messages</a>
            </div>
        </div>
    </div>
@endsection
