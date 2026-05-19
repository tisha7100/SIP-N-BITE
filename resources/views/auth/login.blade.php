@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card border-0 shadow-sm p-5 rounded-4 bg-white">

                <div class="text-start mb-5">
                    <h2 class="fw-800 mb-2">Login</h2>
                    <p class="text-muted">
                        Enter your details to track your orders and book tables.
                    </p>
                </div>

                {{-- ERROR MESSAGE --}}
                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-3 small">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    {{-- LOGIN AS --}}
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">
                            LOGIN AS
                        </label>

                        <select name="role"
                                class="form-control py-3 rounded-3"
                                required>

                            <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="delivery_partner" {{ old('role') == 'delivery_partner' ? 'selected' : '' }}>Delivery Partner</option>

                        </select>
                    </div>

                    {{-- EMAIL --}}
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

                    {{-- PASSWORD --}}
                    <div class="mb-5">
                        <label class="form-label small fw-bold text-muted">
                            PASSWORD
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control py-3 rounded-3 @error('password') is-invalid @enderror"
                               placeholder="••••••••"
                               required>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- LOGIN BUTTON --}}
                    <button type="submit"
                            class="btn btn-zomato w-100 rounded-3 py-3 fw-bold mb-4 shadow-sm">

                        Login to Sip N Bite

                    </button>

                    {{-- OR --}}
                    <div class="text-center position-relative mb-4">
                        <hr>
                        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
                            or
                        </span>
                    </div>

                    {{-- GOOGLE LOGIN --}}
                    <a href="{{ route('google.login') }}"
                       class="btn btn-outline-dark w-100 rounded-3 py-3 fw-bold mb-4 d-flex align-items-center justify-content-center gap-2">

                        <img src="https://www.google.com/favicon.ico" width="20">

                        Continue with Google

                    </a>

                    {{-- REGISTER --}}
                    <div class="text-center">
                        <span class="text-muted small">
                            New to Sip N Bite?

                            <a href="{{ route('register') }}"
                               class="text-danger text-decoration-none fw-bold">

                                Create account

                            </a>
                        </span>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>
@endsection
