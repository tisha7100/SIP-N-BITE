@extends('admin.layouts.app')

@section('content')
<h3 class="fw-bold mb-4">Manage Orders</h3>

<div class="card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 bg-transparent py-3">Order ID</th>
                    <th class="border-0 bg-transparent py-3">Customer</th>
                    <th class="border-0 bg-transparent py-3">Total</th>
                    <th class="border-0 bg-transparent py-3">Payment</th>
                    <th class="border-0 bg-transparent py-3">Status</th>
                    <th class="border-0 bg-transparent py-3">Delivery Partner</th>
                    <th class="border-0 bg-transparent py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="fw-bold">#ORD{{ $order->id }}</td>
                    <td>
                        <div class="fw-semibold">{{ $order->user->name }}</div>
                        <div class="small text-muted">{{ $order->phone }}</div>
                    </td>
                    <td class="fw-bold">₹{{ number_format($order->final_amount, 2) }}</td>
                    <td>
                        <span class="badge rounded-pill {{ strtolower($order->payment_method) != 'cod' ? 'bg-primary text-white' : 'bg-light text-dark' }}">{{ $order->payment_method }}</span>
                    </td>
                    <td>
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="m-0">
                            @csrf
                            @method('PUT')
                            <select name="status" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()" style="width:120px;">
                                @if($order->delivery_partner_id)
                                    @foreach(['Assigned', 'Delivered', 'Cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                @elseif($order->status == 'Ready')
                                    @foreach(['Ready', 'Assigned', 'Delivered', 'Cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                @else
                                    @foreach(['Pending', 'Preparing', 'Ready', 'Assigned', 'Delivered', 'Cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </form>
                    </td>
                    <td>
                        @if($order->status == 'Ready' && !$order->delivery_partner_id)
                            <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="delivery_partner_id" class="form-select form-select-sm rounded-pill border-info" onchange="this.form.submit()" style="width:140px;">
                                    <option value="">Assign Partner</option>
                                    @foreach($availablePartners as $partner)
                                    <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                            </form>
                        @elseif($order->delivery_partner_id && !in_array($order->status, ['Delivered', 'Cancelled']))
                            <span class="badge bg-info text-dark rounded-pill">{{ $order->deliveryPartner->name ?? 'Assigned' }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.bill.generate', $order->id) }}" class="btn btn-sm btn-outline-success rounded-3">
                                <i class="fas fa-file-invoice"></i> Bill
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
