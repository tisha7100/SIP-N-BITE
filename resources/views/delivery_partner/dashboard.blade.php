@extends('delivery_partner.layouts.app')

@section('content')

<style>
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .dashboard-header h3 {
        font-weight: 600;
    }

    .logout-btn {
        background: #dc3545;
        border: none;
        color: #fff;
        padding: 6px 14px;
        border-radius: 6px;
        transition: 0.3s;
    }

    .logout-btn:hover {
        background: #bb2d3b;
    }

    .custom-card {
        border-radius: 12px;
        padding: 18px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        background: #fff;
    }

    .section-title {
        font-weight: 600;
        margin-bottom: 15px;
    }

    .order-box {
        padding: 14px;
        border-radius: 10px;
        background: #f8f9fa;
        margin-bottom: 12px;
        transition: 0.2s;
    }

    .order-box:hover {
        background: #eef3f7;
        transform: translateY(-2px);
    }

    .badge {
        font-size: 12px;
        padding: 6px 10px;
        border-radius: 20px;
    }

    .btn-sm {
        border-radius: 20px;
        padding: 4px 12px;
    }

</style>

<!-- HEADER -->
<div class="dashboard-header">
    <h3>
    Welcome, {{ Auth::guard('delivery_partner')->user()->name }} 🚴
</h3>

</div>

<div class="row g-4">

    <!-- PARTNER INFO -->
    <div class="col-md-4">
        <div class="custom-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">{{ $partner->name }}</h5>
                    <small class="text-muted">{{ $partner->email }}</small>
                </div>

                <span class="badge bg-{{ $partner->status == 'Available' ? 'success' : 'warning text-dark' }}">
                    {{ $partner->status }}
                </span>
            </div>

            <hr>

            <div class="text-muted">
                📞 {{ $partner->phone }}
            </div>
        </div>
    </div>

    <!-- ACTIVE ORDERS -->
    <div class="col-md-8">
        <div class="custom-card">
            <h5 class="section-title text-primary">🚚 Active Orders</h5>

            @forelse($assignedOrders as $order)

                <div class="order-box">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Order #{{ $order->id }}</strong>

                        <span class="badge bg-warning text-dark">
                            {{ $order->status }}
                        </span>
                    </div>

                    <small class="text-muted">
                        Customer: {{ $order->user->name ?? 'N/A' }}
                    </small>

                    <div class="mt-3 d-flex gap-2">

                        <form method="POST" action="{{ route('delivery-partner.orders.update', $order->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Delivered">
                            <button class="btn btn-success btn-sm">✔ Delivered</button>
                        </form>

                        <form method="POST" action="{{ route('delivery-partner.orders.update', $order->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="Cancelled">
                            <button class="btn btn-danger btn-sm">✖ Cancel</button>
                        </form>

                    </div>
                </div>

            @empty
                <p class="text-muted">No active orders assigned.</p>
            @endforelse

        </div>
    </div>

    <!-- HISTORY -->
    <div class="col-md-12">
        <div class="custom-card">
            <h5 class="section-title text-success">📦 Delivery History</h5>

            @forelse($historyOrders as $order)

                <div class="order-box">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Order #{{ $order->id }}</strong>

                        <span class="badge {{ $order->status == 'Delivered' ? 'bg-success' : 'bg-danger' }}">
                            {{ $order->status }}
                        </span>
                    </div>

                    <small class="text-muted">
                        Customer: {{ $order->user->name ?? 'N/A' }}
                    </small>
                </div>

            @empty
                <p class="text-muted">No history found.</p>
            @endforelse

        </div>
    </div>

</div>

@endsection