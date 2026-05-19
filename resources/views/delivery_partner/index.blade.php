@extends('delivery_partner.layouts.app')

@section('content')
<h3 class="fw-bold mb-4">Delivery Partners Overview</h3>

<div class="card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 bg-transparent py-3">ID</th>
                    <th class="border-0 bg-transparent py-3">Name</th>
                    <th class="border-0 bg-transparent py-3">Contact</th>
                    <th class="border-0 bg-transparent py-3">Status</th>
                    <th class="border-0 bg-transparent py-3">Rating</th>
                    <th class="border-0 bg-transparent py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($partners as $p)
                <tr>
                    <td class="fw-bold">#DP{{ $p->id }}</td>
                    <td class="fw-bold text-success"><i class="fas fa-motorcycle me-2"></i>{{ $p->name }}</td>
                    <td>
                        <div><i class="fas fa-phone text-muted me-2 small"></i>{{ $p->phone }}</div>
                        <div class="small text-muted"><i class="fas fa-envelope text-muted me-2 small"></i>{{ $p->email }}</div>
                    </td>
                    <td>
                        <span class="badge {{ $p->status == 'Available' ? 'bg-success' : 'bg-warning text-dark' }} rounded-pill">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td>
                        <span class="text-warning"><i class="fas fa-star"></i> 4.8</span>
                    </td>
                    <td>
                        <a href="{{ route('delivery-partner.show', $p->id) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">
                            View Workboard <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
