@extends('public.layouts.main')
@section('page-title')
    User Dashboard
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('partials.notification')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Welcome, {{ $user->name }}</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    @if($user->phone)
                        <p><strong>Phone:</strong> {{ $user->phone }}</p>
                    @endif
                    <a href="{{ route('account.profile') }}" class="btn btn-outline-primary">Edit Profile</a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Purchase History</h5>
                </div>
                <div class="card-body">
                    @if($orders->isEmpty())
                        <p>You have no orders yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reference</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->reference }}</td>
                                        <td>${{ number_format($order->total, 2) }}</td>
                                        <td>{{ ucfirst($order->status) }}</td>
                                        <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

