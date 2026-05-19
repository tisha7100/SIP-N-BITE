@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-800 mb-0">Your Cart</h2>
        <span class="text-muted">{{ $cartItems->count() }} Items</span>
    </div>
    
    @if($cartItems->isEmpty())
    <div class="text-center py-5">
        <img src="https://b.zmtcdn.com/webFrontend/1169622-EmptyCart.png" width="250" class="mb-4" alt="Empty Cart">
        <h3 class="fw-bold">Your cart is empty</h3>
        <p class="text-muted">Good food is always cooking! Go ahead, order some yummy items from the menu.</p>
        <a href="{{ route('home') }}" class="btn btn-zomato rounded-3 px-5 mt-3">Browse Menu</a>
    </div>
    @else
    <div class="row g-5">
        <div class="col-lg-8">
            <div class="border rounded-4 p-4 bg-white shadow-sm">
                @foreach($cartItems as $item)
                <div class="d-flex align-items-start py-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <img src="{{ $item->food->image ? asset('storage/'.$item->food->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80' }}" 
                         class="rounded-3 me-4" width="100" height="100" style="object-fit: cover;"
                         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80';">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold mb-1">{{ $item->food->name }}</h5>
                            @php
                                $itemTotal = $item->food->price;
                                foreach($item->addons as $ca) $itemTotal += $ca->addon->price;
                            @endphp
                            <span class="fw-800">₹{{ number_format($itemTotal * $item->quantity, 2) }}</span>
                        </div>
                        <div class="small text-muted mb-3">
                            @forelse($item->addons as $ca)
                            <span class="d-block">+ {{ $ca->addon->name }} (₹{{ $ca->addon->price }})</span>
                            @empty
                            <span class="d-block">Standard Portion</span>
                            @endforelse
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="qty-display bg-light px-3 py-1 rounded small fw-bold">
                                Quantity: {{ $item->quantity }}
                            </div>
                            <a href="{{ route('cart.remove', $item->id) }}" class="text-danger small text-decoration-none fw-500">
                                <i class="fas fa-trash-alt me-1"></i> Remove Item
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-lg-4">
            <div class="border rounded-4 p-4 bg-white shadow-sm sticky-top" style="top: 100px;">
                <h5 class="fw-bold mb-4">Summary</h5>
                @php
                    $total = 0;
                    foreach($cartItems as $item) {
                        $it = $item->food->price;
                        foreach($item->addons as $ca) $it += $ca->addon->price;
                        $total += $it * $item->quantity;
                    }
                @endphp
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Total (Net)</span>
                    <span class="fw-bold">₹{{ number_format($total, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 text-success">
                    <span class="small">Taxes & Charges (5%)</span>
                    <span class="small">₹{{ number_format($total * 0.05, 2) }}</span>
                </div>
                <hr class="my-4">
                <div class="d-flex justify-content-between mb-4 h4 fw-800">
                    <span>Grand Total</span>
                    <span class="text-danger">₹{{ number_format($total * 1.05, 2) }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn btn-zomato w-100 rounded-3 py-3 fw-bold">Proceed to Checkout</a>
                <p class="text-muted small text-center mt-3">By clicking proceed, you agree to our terms.</p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

