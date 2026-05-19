@extends('admin.layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="card border-0 shadow-lg p-5 rounded-4" style="max-width: 800px; width: 100%;">
        <div class="d-flex justify-content-between align-items-start mb-5">
            <div>
                <h2 class="fw-bold text-success mb-1"><i class="fas fa-leaf"></i> Sip N Bite</h2>
                <p class="text-muted small mb-0">Pure Veg Restaurant & Delivery</p>
                <p class="text-muted small">Athwa, Surat, Gujarat | +91 93275 62890</p>
            </div>
            <div class="text-end">
                <h4 class="fw-bold mb-1">INVOICE</h4>
                <p class="text-muted small mb-0">{{ $bill->bill_number }}</p>
                <p class="text-muted small">{{ $bill->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-6">
                <p class="mb-1 fw-bold small text-uppercase text-muted">Bill To:</p>
                <h6 class="fw-bold">{{ $order->user->name }}</h6>
                <p class="text-muted small mb-0">{{ $order->shipping_address }}</p>
                <p class="text-muted small">{{ $order->phone }}</p>
            </div>
            <div class="col-6 text-end">
                <p class="mb-1 fw-bold small text-uppercase text-muted">Payment Method:</p>
                <h6 class="fw-bold text-success">{{ $order->payment_method }}</h6>
                @if(strtolower($order->payment_method) != 'cod')
                <span class="badge rounded-pill bg-success bg-opacity-10 text-success">PAID</span>
                @endif
            </div>
        </div>

        <div class="table-responsive mb-5">
            <table class="table align-middle">
                <thead>
                    <tr class="table-light">
                        <th class="border-0">Description</th>
                        <th class="border-0 text-center">Qty</th>
                        <th class="border-0 text-end">Price</th>
                        <th class="border-0 text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td class="py-3">
                            <div class="fw-bold">{{ $item->food->name }}</div>
                            @foreach($item->addons as $oa)
                            <div class="small text-muted">+ {{ $oa->addon->name }} (₹{{ $oa->price }})</div>
                            @endforeach
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                        <td class="text-end fw-bold">₹{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-md-5">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal:</span>
                    <span class="fw-bold">₹{{ number_format($bill->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">GST (5%):</span>
                    <span class="fw-bold">₹{{ number_format($bill->gst, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Delivery Charge:</span>
                    <span class="fw-bold text-success">₹{{ number_format($bill->delivery_charge, 2) }}</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5 class="fw-bold">Final Amount:</h5>
                    <h5 class="fw-bold text-success">₹{{ number_format($bill->final_amount, 2) }}</h5>
                </div>
            </div>
        </div>

        <div class="mt-5 text-center">
            <p class="text-muted small mb-4">Thank you for dining with Sip N Bite! Hope to see you again.</p>
            <button onclick="window.print()" class="btn btn-outline-dark rounded-pill px-4 me-2">
                <i class="fas fa-print me-2"></i> Print Bill
            </button>
        </div>
    </div>
</div>
@endsection
