
@extends('delivery_partner.layouts.app')

@section('content')

<style>
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
    }

    .page-subtitle {
        color: #64748b;
        margin-top: 5px;
    }

    .partner-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }

    .partner-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 14px;
    }

    .partner-table thead th {
        font-size: 0.9rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        border: none;
        padding-bottom: 1rem;
    }

    .partner-table tbody tr {
        background: #f8fafc;
        transition: all 0.25s ease;
        border-radius: 16px;
    }

    .partner-table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(37,99,235,0.08);
        background: #ffffff;
    }

    .partner-table td {
        padding: 1.2rem 1rem;
        vertical-align: middle;
        border-top: none;
    }

    .partner-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #dbeafe;
    }

    .partner-name {
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .partner-email {
        color: #64748b;
        font-size: 0.9rem;
    }

    .status-badge {
        padding: 0.45rem 1rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-active {
        background: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-view {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37,99,235,0.25);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-state h4 {
        font-weight: 700;
        color: #334155;
    }

    .empty-state p {
        color: #64748b;
    }
</style>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-title">Delivery Partners</h1>
            <p class="page-subtitle">Manage and monitor all delivery staff</p>
        </div>
    </div>

    <div class="partner-card">

        @if($partners->count() > 0)

        <div class="table-responsive">
            <table class="partner-table table align-middle">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Partner</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($partners as $partner)
                    <tr>

                        <td>
                            <strong>#{{ $partner->id }}</strong>
                        </td>

                        <td>
                            <div class="d-flex align-items-center gap-3">

                                <img
                                    src="https://ui-avatars.com/api/?name={{ urlencode($partner->name) }}&background=dbeafe&color=1d4ed8"
                                    class="partner-avatar"
                                >

                                <div>
                                    <div class="partner-name">{{ $partner->name }}</div>
                                    <div class="partner-email">{{ $partner->email }}</div>
                                </div>

                            </div>
                        </td>

                        <td>
                            {{ $partner->phone ?? 'N/A' }}
                        </td>

                        <td>
                            @if($partner->status === 'Available')
                                <span class="status-badge status-active">
                                    Available
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    Busy
                                </span>
                            @endif
                        </td>

                        <td class="text-end">
                            <a href="{{ route('delivery-partner.show', $partner->id) }}" class="btn-view">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                        </td>

                    </tr>
                    @endforeach

                </tbody>

            </table>
        </div>

        @else

        <div class="empty-state">
            <i class="fas fa-motorcycle"></i>
            <h4>No Delivery Partners Found</h4>
            <p>No delivery partners are currently available in the system.</p>
        </div>

        @endif

    </div>

</div>

@endsection
```
