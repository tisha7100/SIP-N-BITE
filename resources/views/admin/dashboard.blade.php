@extends('admin.layouts.app')

@section('content')
@push('css')
<style>
    .dashboard-stat {
        border: 1px solid #e5edf8;
        border-radius: 14px;
        min-height: 126px;
        overflow: hidden;
        position: relative;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .dashboard-stat:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 34px rgba(37, 99, 235, 0.12);
    }

    .dashboard-stat::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 5px;
        background: var(--stat-accent);
    }

    .stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--stat-bg);
        color: var(--stat-accent);
        flex: 0 0 auto;
    }

    .stat-title {
        color: #64748b;
        font-size: 0.86rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .stat-value {
        color: #0f172a;
        font-size: 2rem;
        line-height: 1;
        font-weight: 800;
    }

    .stat-note {
        color: #64748b;
        font-size: 0.82rem;
    }

    .layout-tile {
        min-height: 76px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        box-shadow: inset 0 -10px 18px rgba(15, 23, 42, 0.08);
    }
</style>
@endpush

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card dashboard-stat p-4" style="--stat-accent:#059669; --stat-bg:#d1fae5;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon">
                    <i class="fas fa-users fs-4"></i>
                </div>
                <div class="min-w-0">
                    <div class="stat-title">Customers</div>
                    <div class="stat-value">{{ $stats['total_users'] }}</div>
                    <div class="stat-note">Registered customer accounts</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stat p-4" style="--stat-accent:#2563eb; --stat-bg:#dbeafe;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag fs-4"></i>
                </div>
                <div class="min-w-0">
                    <div class="stat-title">Orders</div>
                    <div class="stat-value">{{ $stats['total_orders'] }}</div>
                    <div class="stat-note">{{ $stats['recent_orders']->count() }} recent shown below</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stat p-4" style="--stat-accent:#d97706; --stat-bg:#fef3c7;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon">
                    <i class="fas fa-motorcycle fs-4"></i>
                </div>
                <div class="min-w-0">
                    <div class="stat-title">Delivery Partners</div>
                    <div class="stat-value">{{ $stats['total_partners'] }}</div>
                    <div class="stat-note">{{ $stats['available_partners'] }} available, {{ $stats['busy_partners'] }} busy</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card dashboard-stat p-4" style="--stat-accent:#0891b2; --stat-bg:#cffafe;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon">
                    <i class="fas fa-chair fs-4"></i>
                </div>
                <div class="min-w-0">
                    <div class="stat-title">Table Bookings</div>
                    <div class="stat-value">{{ $stats['total_bookings'] }}</div>
                    <div class="stat-note">{{ $stats['pending_bookings'] }} pending requests</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Recent Orders</h5>
               <a href="{{ url('/admin/orders') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 bg-transparent py-3">Order ID</th>
                            <th class="border-0 bg-transparent py-3">Customer</th>
                            <th class="border-0 bg-transparent py-3">Total Amount</th>
                            <th class="border-0 bg-transparent py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recent_orders'] as $order)
                        @php
                            $statusMap = [
                                'Under Preparation' => 'warning',
                                'Ready'             => 'info',
                                'Out for Delivery'  => 'primary',
                                'Delivered'         => 'success',
                                'Cancelled'         => 'danger',
                            ];
                            $color = $statusMap[$order->status] ?? 'secondary';
                        @endphp
                        <tr>
                            <td>#ORD{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>₹{{ number_format($order->final_amount, 2) }}</td>
                            <td>
                                <span class="badge rounded-pill text-bg-{{ $color }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No orders yet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4">
            <h5 class="fw-bold mb-4">Restaurant Layout Overview</h5>
            <div class="table-layout-grid p-3 bg-light rounded-3" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                @forelse($stats['tables'] as $table)
                <div class="layout-tile"
                     style="background-color: {{ $table->status === 'booked' ? '#ef4444' : '#10b981' }};">
                     T{{ $table->table_number }}
                </div>
                @empty
                <div class="text-muted text-center py-4" style="grid-column: 1 / -1;">No tables added yet</div>
                @endforelse
            </div>
            <div class="mt-3 d-flex justify-content-center gap-4">
                <div class="small"><i class="fas fa-square text-success"></i> {{ $stats['available_tables'] }} Available</div>
                <div class="small"><i class="fas fa-square text-danger"></i> {{ $stats['booked_tables'] }} Booked</div>
            </div>
        </div>
    </div>
</div>
@endsection
