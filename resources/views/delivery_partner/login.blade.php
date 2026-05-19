@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-5 rounded-4 bg-white">
                <div class="text-start mb-5">
                    <h2 class="fw-800 mb-2">Delivery Partner Login</h2>
                    <p class="text-muted">
                        Sign in to manage assigned deliveries.
                    </p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-3 small">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('delivery-partner.login.post') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">
                            EMAIL
                        </label>
                        <input type="email"
                               name="email"
                               class="form-control py-3 rounded-3 @error('email') is-invalid @enderror"
                               placeholder="Enter your email"
                               value="{{ old('email') }}"
                               required>

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label class="form-label small fw-bold text-muted">
                            PASSWORD
                        </label>
                        <input type="password"
                               name="password"
                               class="form-control py-3 rounded-3 @error('password') is-invalid @enderror"
                               placeholder="Enter your password"
                               required>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button type="submit"
                            class="btn btn-zomato w-100 rounded-3 py-3 fw-bold shadow-sm">
                        Login to Delivery Hub
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
