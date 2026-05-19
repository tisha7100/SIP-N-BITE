@extends('admin.layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.menu.index') }}" class="text-muted text-decoration-none small">
        <i class="fas fa-arrow-left me-2"></i> Back to Menu
    </a>
    <h3 class="fw-bold mt-2">Add New Food Item</h3>
</div>

<div class="card border-0 shadow-sm p-4 rounded-4" style="max-width: 600px;">
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">Please fix the following:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">        
        @csrf

        <!-- Item Name -->
        <div class="mb-3">
            <label class="form-label small fw-bold">Item Name</label>
            <input type="text" name="name" class="form-control"
                   placeholder="e.g. Cheese Burst Pizza"
                   value="{{ old('name') }}" required>
        </div>

        <!-- Category -->
        <div class="mb-3">
            <label class="form-label small fw-bold">Category</label>
            <select name="category_id" class="form-select" required>
                <option value="">Select Category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        @selected((string) old('category_id') === (string) $cat->id)>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label class="form-label small fw-bold">Price (₹50 - ₹500)</label>
            <input type="number" name="price" class="form-control"
                   min="50" max="500"
                   value="{{ old('price') }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label class="form-label small fw-bold">Description</label>
            <textarea name="description" class="form-control" rows="3"
                      placeholder="Brief description of the item">{{ old('description') }}</textarea>
        </div>

        <!-- Image -->
        <div class="mb-4">
            <label class="form-label small fw-bold">Food Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <!-- ✅ CUSTOMIZATION TOGGLE (IMPORTANT) -->
        <!-- CUSTOMIZATION -->
<div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input"
           name="is_customizable"
           id="is_customizable"
           {{ old('is_customizable') ? 'checked' : '' }}>

    <label class="form-check-label fw-bold" for="is_customizable">
        Enable Customization (Add-ons allowed)
    </label>
</div>

        <!-- Submit -->
        <button type="submit" class="btn btn-veg w-100 rounded-pill py-3 fw-bold">
            Add Item to Menu
        </button>

    </form>
</div>
@endsection