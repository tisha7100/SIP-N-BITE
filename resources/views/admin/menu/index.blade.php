@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Manage Menu</h3>
    <a href="{{ route('admin.menu.create') }}" class="btn btn-primary rounded-pill px-4">
        <i class="fas fa-plus me-2"></i> Add Item
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card border-0 shadow-sm p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="border-0 bg-transparent py-3">Image</th>
                    <th class="border-0 bg-transparent py-3">Name</th>
                    <th class="border-0 bg-transparent py-3">Category</th>
                    <th class="border-0 bg-transparent py-3">Price</th>
                    <th class="border-0 bg-transparent py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($foods as $food)
                <tr>
                    <td>
                        <img src="{{ $food->image ? Storage::url($food->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80' }}"
                             class="rounded-3" width="55" height="55" style="object-fit: cover;"
                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80';">
                    </td>
                    <td class="fw-semibold">{{ $food->name }}</td>
                    <td><span class="badge bg-light text-dark">{{ $food->category?->name ?? 'No Category' }}</span></td>
                    <td class="fw-bold" style="color:#2563eb;">₹{{ $food->price }}</td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.menu.edit', $food->id) }}" class="btn btn-sm btn-outline-info rounded-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.menu.destroy', $food->id) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-3">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No items found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4 d-flex justify-content-center">
        {{ $foods->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
