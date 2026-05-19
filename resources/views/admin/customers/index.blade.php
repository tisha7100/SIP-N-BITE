@extends('admin.layouts.app')

@section('content')
<h3 class="fw-bold mb-4">Manage Customers</h3>

<div class="card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 bg-transparent py-3">Customer</th>
                    <th class="border-0 bg-transparent py-3">Registration</th>
                    <th class="border-0 bg-transparent py-3">Last Login</th>
                    <th class="border-0 bg-transparent py-3">Status</th>
                    <th class="border-0 bg-transparent py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{{ $customer->avatar ?? 'https://ui-avatars.com/api/?name='.$customer->name }}" class="rounded-circle me-3" width="40">
                            <div>
                                <div class="fw-bold">{{ $customer->name }}</div>
                                <div class="small text-muted">{{ $customer->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="small">{{ $customer->registration_time ? $customer->registration_time->format('d M Y, h:i A') : 'N/A' }}</td>
                    <td class="small">{{ $customer->login_time ? $customer->login_time->format('d M Y, h:i A') : 'N/A' }}</td>
                    <td>
                        <span class="badge rounded-pill {{ $customer->status == 'active' ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $customer->status == 'active' ? 'blocked' : 'active' }}">
                                <button type="submit" class="btn btn-sm {{ $customer->status == 'active' ? 'btn-outline-danger' : 'btn-outline-success' }} rounded-3">
                                    {{ $customer->status == 'active' ? 'Block' : 'Unblock' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Remove this customer?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-dark rounded-3">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>
@endsection
