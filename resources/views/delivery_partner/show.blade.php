@extends('delivery_partner.layouts.app')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h3 class="fw-bold mb-0">Delivery Details: {{ $partner->name }}</h3>
    <a href="{{ route('delivery-partner.dashboard') }}" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-arrow-left me-2"></i>Back to Hub</a>
</div>

<div class="row g-4">
    <!-- Sidebar: Personal Details & Status -->
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-light d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px;">
                    <i class="fas fa-user text-success fs-1"></i>
                </div>
                <h4 class="fw-bold mb-1">{{ $partner->name }}</h4>
                <p class="text-muted mb-3"><i class="fas fa-envelope me-2"></i>{{ $partner->email }}<br><i class="fas fa-phone me-2"></i>{{ $partner->phone }}</p>

                <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                    <span class="badge {{ $partner->status == 'Available' ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill px-3 py-2">
                        <i class="fas fa-circle me-1" style="font-size: 8px; vertical-align: middle;"></i> {{ $partner->status }}
                    </span>
                </div>

                <form action="{{ route('delivery-partner.toggle-status', $partner->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-{{ $partner->status == 'Available' ? 'warning' : 'success' }} w-100 rounded-pill fw-bold">
                        Mark as {{ $partner->status == 'Available' ? 'Busy' : 'Available' }}
                    </button>
                </form>

                <div class="mt-4 pt-4 border-top">
                    <h6 class="fw-bold text-muted text-uppercase mb-3">Ratings</h6>
                    <div class="text-warning fs-4">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                    <span class="fw-bold fs-5">4.8</span> <span class="text-muted small">/ 5.0</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content: Orders -->
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-body p-4">
                <ul class="nav nav-pills mb-4 gap-2" id="orderTabs">
                    <li class="nav-item">
                        <button class="nav-link active px-4" data-bs-toggle="pill" data-bs-target="#active-orders">
                            <i class="fas fa-box-open me-2"></i>Assigned Orders
                            @if($assignedOrders->count() > 0)
                                <span class="badge bg-white text-success rounded-pill ms-2">{{ $assignedOrders->count() }}</span>
                            @endif
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link px-4" data-bs-toggle="pill" data-bs-target="#delivery-history">
                            <i class="fas fa-history me-2"></i>History
                        </button>
                    </li>
                </ul>

                <div class="tab-content">
                    <!-- Active Orders -->
                    <div class="tab-pane fade show active" id="active-orders">
                        @forelse($assignedOrders as $order)
                            <div class="border rounded-4 p-4 mb-3 position-relative overflow-hidden">
                                <div class="position-absolute top-0 start-0 w-100" style="height: 4px; background: linear-gradient(135deg, #16a34a, #15803d);"></div>
                                <div class="d-flex justify-content-between align-items-start mb-3 mt-2">
                                    <div>
                                        <h5 class="fw-bold mb-1">#ORD{{ $order->id }}</h5>
                                        <span class="badge bg-primary rounded-pill">{{ $order->status }}</span>
                                    </div>
                                    <div class="text-end text-muted small">
                                        {{ $order->created_at->diffForHumans() }}
                                    </div>
                                </div>
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-sm-6">
                                        <div class="fw-bold text-muted small text-uppercase">Customer</div>
                                        <div class="fw-semibold"><i class="fas fa-user text-muted me-2"></i>{{ $order->user->name }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="fw-bold text-muted small text-uppercase">Phone</div>
                                        <div class="fw-semibold"><i class="fas fa-phone text-muted me-2"></i>{{ $order->phone }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="fw-bold text-muted small text-uppercase">Delivery Address</div>
                                        <div class="fw-semibold"><i class="fas fa-map-marker-alt text-muted me-2"></i>{{ $order->shipping_address }}</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="fw-bold text-muted small text-uppercase mb-1">Food Items</div>
                                        <ul class="list-unstyled mb-0">
                                            @foreach($order->orderItems as $item)
                                                <li class="small fw-medium"><span class="badge bg-light text-dark me-2">{{ $item->quantity }}x</span> {{ $item->food->name ?? 'Food Item Deleted' }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <div class="fw-bold fs-5 text-success">₹{{ number_format($order->final_amount, 2) }} <small class="text-muted fs-6">({{ $order->payment_method }})</small></div>
                                    <form action="{{ route('delivery-partner.orders.update', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success fw-bold rounded-pill px-4" onclick="return confirm('Confirm order delivered?');">
                                            Mark as Delivered <i class="fas fa-check ms-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="display-1 text-muted mb-3"><i class="fas fa-box-open opacity-25"></i></div>
                                <h5 class="fw-bold text-muted">No active orders assigned!</h5>
                            </div>
                        @endforelse
                    </div>

                    <!-- Delivery History -->
                    <div class="tab-pane fade" id="delivery-history">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 py-3">Order ID</th>
                                        <th class="border-0 py-3">Customer</th>
                                        <th class="border-0 py-3">Amount</th>
                                        <th class="border-0 py-3">Payment</th>
                                        <th class="border-0 py-3">Status</th>
                                        <th class="border-0 py-3">Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($historyOrders as $order)
                                        <tr>
                                            <td class="fw-bold">#{{ $order->id }}</td>
                                            <td>{{ $order->user->name }}</td>
                                            <td class="fw-medium">₹{{ number_format($order->final_amount, 2) }}</td>
                                            <td>
                                                @if($order->payment_method == 'Paid Online')
                                                    <span class="badge rounded-pill px-3" style="background:#dbeafe;color:#1d4ed8;">Paid Online</span>
                                                @else
                                                    <span class="badge bg-secondary rounded-pill">COD</span>
                                                @endif
                                            </td>
                                            <td><span class="badge {{ $order->status == 'Delivered' ? 'bg-success' : 'bg-danger' }} rounded-pill">{{ $order->status }}</span></td>
                                            <td class="text-muted small">{{ $order->updated_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">No delivery history found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
