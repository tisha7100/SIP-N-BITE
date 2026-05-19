@extends('admin.layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="text-muted text-decoration-none small">
        <i class="fas fa-arrow-left me-2"></i> Back to Orders
    </a>
    <h3 class="fw-bold mt-2">Order Details #ORD{{ $order->id }}</h3>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
            <h5 class="fw-bold mb-4">Items Summary</h5>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $item->food->name }}</div>
                                @foreach($item->addons as $oa)
                                <div class="small text-muted">+ {{ $oa->addon->name }} (₹{{ $oa->addon->price }})</div>
                                @endforeach
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>₹{{ $item->food->price }}</td>
                            <td class="text-end fw-bold">₹{{ $item->subtotal }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
            <h5 class="fw-bold mb-4">Order Status</h5>
            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <select name="status" class="form-select rounded-pill">
                        <option value="Under Preparation" {{ $order->status == 'Under Preparation' ? 'selected' : '' }}>Under Preparation</option>
                        <option value="Ready" {{ $order->status == 'Ready' ? 'selected' : '' }}>Ready</option>
                        <option value="Out for Delivery" {{ $order->status == 'Out for Delivery' ? 'selected' : '' }}>Out for Delivery</option>
                        <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100 rounded-pill">Update Status</button>
            </form>
        </div>

        <div class="card border-0 shadow-sm p-4 rounded-4">
            <h5 class="fw-bold mb-4">Customer Info</h5>
            <p class="mb-1 fw-bold">{{ $order->user->name }}</p>
            <p class="mb-1 text-muted">{{ $order->phone }}</p>
            <p class="mb-3 text-muted small">{{ $order->address }}</p>
            <hr>
            <div class="d-flex justify-content-between mb-2">
                <span>Subtotal</span>
                <span class="fw-bold">₹{{ $order->total_amount }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>GST (5%)</span>
                <span class="fw-bold">₹{{ $order->gst }}</span>
            </div>
            <div class="d-flex justify-content-between mb-3 text-success">
                <span>Delivery Charge</span>
                <span class="fw-bold">₹{{ $order->delivery_charge }}</span>
            </div>
            <div class="d-flex justify-content-between h5 fw-bold text-success border-top pt-3">
                <span>Total</span>
                <span>₹{{ $order->final_amount }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                <span class="text-muted small fw-semibold">Payment</span>
                @if($order->payment_method == 'Paid Online')
                    <span class="d-flex align-items-center gap-2">
                        <span class="fw-bold" style="color:#2563eb;"><i class="fas fa-wallet me-1"></i>Paid Online</span>
                        <span class="badge bg-success rounded-pill">PAID</span>
                    </span>
                @else
                    <span class="fw-bold text-secondary"><i class="fas fa-money-bill me-1"></i>COD</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
