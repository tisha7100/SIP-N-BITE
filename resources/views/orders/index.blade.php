@extends('layouts.app')

@push('css')
<style>
    .order-history-card {
        border: 1px solid var(--zomato-border);
        border-radius: 1rem;
        background: #fff;
        transition: all 0.2s;
        overflow: hidden;
    }
    
    .order-history-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    
    .status-pill {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 0.4rem 0.8rem;
        border-radius: 0.4rem;
    }
    
    .booking-feature-card {
        background: #fcfcfc;
        border-radius: 0.8rem;
        padding: 1.2rem;
        border-left: 4px solid var(--zomato-red);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    {{-- Alerts --}}
    @if(session('booking_success') || session('success'))
    <div class="alert alert-success border-0 rounded-3 shadow-sm mb-5 py-3">
        <i class="fas fa-check-circle me-2"></i> {{ session('booking_success') ?? session('success') }}
    </div>
    @endif

    <div class="row">
        <div class="col-lg-3">
            <div class="border rounded-4 p-4 sticky-top d-none d-lg-block" style="top: 100px;">
                <h5 class="fw-800 mb-4">Account</h5>
                <nav class="nav flex-column gap-2">
                    <a href="#" class="nav-link px-0 text-danger fw-bold"><i class="fas fa-shopping-bag me-3"></i> Order History</a>
                    <a href="{{ route('booking.index') }}" class="nav-link px-0 text-muted"><i class="fas fa-chair me-3"></i> Table Bookings</a>
                    <a href="{{ route('cart.index') }}" class="nav-link px-0 text-muted"><i class="fas fa-shopping-cart me-3"></i> My Cart</a>
                    <hr>
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="nav-link px-0 text-muted border-0 bg-transparent">
        <i class="fas fa-sign-out-alt me-3"></i> Logout
    </button>
</form>                </nav>
            </div>
        </div>

        <div class="col-lg-9">
            <h2 class="fw-800 mb-5">Order History</h2>

            {{-- Food Orders Section --}}
            <div class="mb-5">
                <div class="row g-4">
                    @forelse($orders as $order)
                    <div class="col-12">
                        <div class="order-history-card p-4">
                            <div class="d-flex justify-content-between align-items-start mb-4">
                                <div class="d-flex gap-3">
                                    <div class="bg-light rounded-3 p-3">
                                        <i class="fas fa-utensils text-muted fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">Order #ORD{{ $order->id }}</h5>
                                        <p class="text-muted small mb-0">{{ $order->created_at->format('M d, Y • h:i A') }}</p>
                                    </div>
                                </div>
                                <div>
                                    @php
                                        $statuses = [
                                            'Under Preparation' => ['bg' => '#FFFDE7', 'text' => '#FBC02D'],
                                            'Ready'             => ['bg' => '#E3F2FD', 'text' => '#1976D2'],
                                            'Out for Delivery'  => ['bg' => '#F3E5F5', 'text' => '#7B1FA2'],
                                            'Delivered'         => ['bg' => '#E8F5E9', 'text' => '#2E7D32'],
                                            'Cancelled'         => ['bg' => '#FFEBEE', 'text' => '#C62828'],
                                        ];
                                        $style = $statuses[$order->status] ?? ['bg' => '#F5F5F5', 'text' => '#616161'];
                                    @endphp
                                    <span class="status-pill" style="background-color: {{ $style['bg'] }}; color: {{ $style['text'] }};">
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="fas fa-map-marker-alt me-2"></i> {{ Str::limit($order->address, 50) }}
                                    <span class="mx-2">•</span>
                                    <span class="fw-bold text-dark">₹{{ number_format($order->final_amount, 2) }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm rounded-3 px-3 fw-bold">Track Order</a>
                                    <a href="{{ route('orders.bill', $order->id) }}" target="_blank" class="btn btn-zomato btn-sm rounded-3 px-3 fw-bold">View Bill</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <img src="https://b.zmtcdn.com/webFrontend/1169622-EmptyCart.png" width="150" class="mb-4 opacity-50">
                        <h5 class="text-muted">No orders found.</h5>
                        <a href="{{ route('home') }}" class="btn btn-zomato rounded-3 px-5 mt-3">Order Something Now</a>
                    </div>
                    @endforelse
                </div>
                
                @if($orders->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>

            {{-- Table Bookings Section --}}
            <h3 class="fw-800 mb-4 pt-4 border-top">Your Table Bookings</h3>
            <div class="row g-4">
                @forelse($bookings as $booking)
                <div class="col-md-6">
                    <div class="booking-feature-card">
                        <div class="d-flex justify-content-between mb-3">
                            <h6 class="fw-800 mb-0">Table {{ $booking->table->table_number ?? 'Any' }}</h6>
                            @php
                                $bStyles = [
                                    'pending'   => 'text-warning',
                                    'accepted'  => 'text-success',
                                    'rejected'  => 'text-danger',
                                    'completed' => 'text-muted',
                                ];
                            @endphp
                            <span class="small fw-bold text-uppercase {{ $bStyles[$booking->status] ?? '' }}">
                                {{ $booking->status }}
                            </span>
                        </div>
                        <div class="small text-muted mb-3">
                            <i class="far fa-calendar-alt me-2"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('D, M d') }}
                            <span class="mx-2">•</span>
                            <i class="far fa-clock me-2"></i> {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }}
                        </div>
                        <div class="bg-white rounded-3 p-2 small border">
                            <span class="text-muted">Occasion:</span> {{ $booking->purpose ?? 'Dining' }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-4 bg-light rounded-4">
                    <p class="text-muted mb-0">No table bookings yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

