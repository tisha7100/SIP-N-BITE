@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-5 rounded-4 bg-white">
                <div class="text-start mb-5">
                    <h2 class="fw-800 mb-2">Create Account</h2>
                    <p class="text-muted">Start ordering your favorite food and book tables with ease.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-3 small">{{ session('error') }}</div>
                @endif

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">FULL NAME</label>
                        <input type="text" name="name" class="form-control py-3 rounded-3 @error('name') is-invalid @enderror" placeholder="Enter your full name" value="{{ old('name') }}" required minlength="2">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">EMAIL</label>
                        <input type="email" name="email" class="form-control py-3 rounded-3 @error('email') is-invalid @enderror" placeholder="Enter your email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">PASSWORD</label>
                        <input type="password" name="password" class="form-control py-3 rounded-3 @error('password') is-invalid @enderror" placeholder="Create a password" required minlength="6">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-5">
                        <label class="form-label small fw-bold text-muted">CONFIRM PASSWORD</label>
                        <input type="password" name="password_confirmation" class="form-control py-3 rounded-3" placeholder="Repeat password" required>
                    </div>

                    <button type="submit" class="btn btn-zomato w-100 rounded-3 py-3 fw-bold mb-4 shadow-sm">
                        Create Account
                    </button>

                    <div class="text-center">
                        <span class="text-muted small">Already on Sip N Bite? <a href="{{route('customer.login') }}" class="text-danger text-decoration-none fw-bold">Login here</a></span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

