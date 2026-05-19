@extends('layouts.app')

@push('css')
<style>
    .tracking-card {
        border-radius: 1.5rem;
        border: 1px solid var(--zomato-border);
        background: #fff;
        padding: 3rem;
    }
    
    .tracking-step {
        display: flex;
        gap: 1.5rem;
        position: relative;
        padding-bottom: 2rem;
    }
    
    .tracking-step::before {
        content: "";
        position: absolute;
        left: 15px;
        top: 30px;
        height: calc(100% - 30px);
        width: 2px;
        background: var(--zomato-border);
    }
    
    .tracking-step:last-child {
        padding-bottom: 0;
    }
    
    .tracking-step:last-child::before {
        display: none;
    }
    
    .step-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid var(--zomato-border);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        font-size: 0.8rem;
    }
    
    .tracking-step.active .step-icon {
        border-color: #2e7d32;
        background: #e8f5e9;
        color: #2e7d32;
    }
    
    .tracking-step.completed .step-icon {
        background: #2e7d32;
        border-color: #2e7d32;
        color: white;
    }
    
    .tracking-step.active .step-text h6 {
        color: #2e7d32;
        font-weight: 800;
    }
    
    .partner-card {
        background: #fcfcfc;
        border: 1px solid var(--zomato-border);
        border-radius: 1rem;
        padding: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="tracking-card shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-800 mb-1">Track Order</h2>
                        <p class="text-muted mb-0">ID: #ORD{{ $order->id }} • {{ $order->created_at->format('h:i A') }}</p>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('orders.bill', $order->id) }}" target="_blank" class="btn btn-outline-dark rounded-3 px-4 fw-bold">
                            <i class="fas fa-file-invoice me-2"></i> Download Bill
                        </a>
                    </div>
                </div>

                <div class="row g-5">
                    <div class="col-md-5">
                        <div class="tracking-steps-container">
                            @php
                                $steps = [
                                    ['label' => 'Order Placed', 'icon' => 'check', 'val' => 0],
                                    ['label' => 'Preparing', 'icon' => 'utensils', 'val' => 20],
                                    ['label' => 'Ready', 'icon' => 'box', 'val' => 50],
                                    ['label' => 'Out for Delivery', 'icon' => 'motorcycle', 'val' => 80],
                                    ['label' => 'Delivered', 'icon' => 'home', 'val' => 100],
                                ];
                            @endphp

                            @if($order->status == 'Cancelled')
                                <div class="tracking-step active">
                                    <div class="step-icon text-danger border-danger">
                                        <i class="fas fa-times"></i>
                                    </div>
                                    <div class="step-text">
                                        <h6 class="mb-0 text-danger">Order Cancelled</h6>
                                        <p class="small text-muted mb-0">Refund (if any) will be processed</p>
                                    </div>
                                </div>
                            @else
                                @foreach($steps as $s)
                                    @php
                                        $isComplete = $progress > $s['val'] || ($progress == 100 && $s['val'] == 100);
                                        $isActive = $progress == $s['val'] && $progress < 100;
                                        if ($progress > $s['val'] && $progress < ($steps[$loop->index+1]['val'] ?? 101)) $isActive = true;
                                    @endphp
                                    <div class="tracking-step {{ $isComplete ? 'completed' : ($isActive ? 'active' : '') }}">
                                        <div class="step-icon">
                                            <i class="fas fa-{{ $isComplete ? 'check' : $s['icon'] }}"></i>
                                        </div>
                                        <div class="step-text">
                                            <h6 class="mb-0">{{ $s['label'] }}</h6>
                                            @if($isActive) <p class="small text-muted mb-0">In progress...</p> @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-7">
                        @if($order->deliveryPartner && $order->status != 'Cancelled')
                        <div class="partner-card mb-4 {{ $order->status == 'Delivered' ? 'border-success bg-light' : '' }}">
                            <h6 class="fw-800 mb-3 text-uppercase small text-muted">
                                {{ $order->status == 'Delivered' ? 'Delivered By' : 'Delivery Executive' }}
                            </h6>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-zomato rounded-circle text-white d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px;">
                                    {{ strtoupper(substr($order->deliveryPartner->name, 0, 1)) }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-0">{{ $order->deliveryPartner->name }}</h6>
                                    @if($order->status == 'Delivered')
                                        <p class="small text-success mb-0 fw-bold"><i class="fas fa-check-circle"></i> Successfully delivered your order</p>
                                    @else
                                        <p class="small text-muted mb-0"><i class="fas fa-star text-warning"></i> 4.9 • 2k+ orders</p>
                                    @endif
                                </div>
                                <a href="tel:{{ $order->deliveryPartner->phone }}" class="btn btn-light rounded-circle shadow-sm">
                                    <i class="fas fa-phone text-success"></i>
                                </a>
                            </div>
                            @if($order->status == 'Delivered')
                            <div class="mt-3 small text-muted border-top pt-2">
                                For any issues, contact: <span class="fw-bold text-dark">{{ $order->deliveryPartner->phone }}</span>
                            </div>
                            @endif
                        </div>
                        @endif


                        <div class="border rounded-4 p-4">
                            <h6 class="fw-800 mb-4 text-uppercase small text-muted">Order Details</h6>
                            @foreach($order->orderItems as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small">{{ $item->quantity }} x {{ $item->food->name }}</span>
                                <span class="small fw-bold">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                            @foreach($item->addons as $oa)
                            <div class="small text-muted ms-3 mb-2">+ {{ $oa->addon->name }}</div>
                            @endforeach
                            @endforeach
                            
                            <hr class="my-3">
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Subtotal</span>
                                <span>₹{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Taxes & Charges</span>
                                <span>₹{{ number_format($order->gst + $order->delivery_charge, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mt-3 h5 fw-800">
                                <span>Total Amount</span>
                                <span class="text-danger">₹{{ number_format($order->final_amount, 2) }}</span>
                            </div>
                            <div class="mt-2 small text-muted">
                                Paid via {{ $order->payment_method }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

