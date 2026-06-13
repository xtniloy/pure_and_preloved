@extends('public.layouts.main')
@section('title')
    Contact Us
@endsection
@section('content')
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-content">
                        <ul class="nav">
                            <li><a href="{{ route('home') }}">Home</a></li>
                            <li>Contact Us</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-50px mt-50px">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="text-center mb-5">
                    <h1 class="fw-bold mb-2">Get in Touch</h1>
                    <p class="text-muted">Have a question about a piece, an order, or anything else? We'd love to hear from you.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row g-4">
                    {{-- Contact details --}}
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4 p-md-5">
                                <h2 class="fw-bold mb-4" style="font-size:1.4rem;">Contact Information</h2>

                                <div class="d-flex mb-4">
                                    <div class="d-inline-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width:48px;height:48px;border-radius:50%;background:#ccfbf1;color:#0f766e;">
                                        <i class="fa fa-map-marker" style="font-size:20px;"></i>
                                    </div>
                                    <div>
                                        <h3 style="font-size:1rem;font-weight:700;margin:0 0 .25rem;">Address</h3>
                                        <p class="text-muted mb-0">Dixon Street, Lincoln, United Kingdom</p>
                                    </div>
                                </div>

                                <div class="d-flex mb-4">
                                    <div class="d-inline-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width:48px;height:48px;border-radius:50%;background:#ccfbf1;color:#0f766e;">
                                        <i class="fa fa-envelope" style="font-size:18px;"></i>
                                    </div>
                                    <div>
                                        <h3 style="font-size:1rem;font-weight:700;margin:0 0 .25rem;">Email</h3>
                                        <p class="mb-0"><a href="mailto:support@pureandpreloved.co.uk" style="color:#0f766e;">support@pureandpreloved.co.uk</a></p>
                                    </div>
                                </div>

                                <div class="d-flex">
                                    <div class="d-inline-flex align-items-center justify-content-center flex-shrink-0 me-3" style="width:48px;height:48px;border-radius:50%;background:#ccfbf1;color:#0f766e;">
                                        <i class="fa fa-phone" style="font-size:20px;"></i>
                                    </div>
                                    <div>
                                        <h3 style="font-size:1rem;font-weight:700;margin:0 0 .25rem;">Phone</h3>
                                        <p class="mb-0"><a href="tel:+447396823194" style="color:#0f766e;">+44 7396 823194</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Contact form --}}
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4 p-md-5">
                                <h2 class="fw-bold mb-4" style="font-size:1.4rem;">Send us a Message</h2>

                                <form action="{{ route('contact.submit') }}" method="POST">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="subject" class="form-label">Subject</label>
                                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}">
                                            @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-12">
                                            <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                                            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" style="background:#0f766e;color:#fff;border:none;padding:.7rem 2rem;border-radius:.5rem;font-weight:600;">Send Message</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
