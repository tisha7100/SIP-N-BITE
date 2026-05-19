@extends('admin.layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.menu.index') }}" class="text-muted text-decoration-none small">
        <i class="fas fa-arrow-left me-2"></i> Back to Menu
    </a>
    <h3 class="fw-bold mt-2">Edit Food Item</h3>
</div>

<div class="card border-0 shadow-sm p-4 rounded-4" style="max-width: 600px;">
    <form action="{{ route('admin.menu.update', $food->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Item Name -->
        <div class="mb-3">
            <label class="form-label small fw-bold">Item Name</label>
            <input type="text" name="name" class="form-control" value="{{ $food->name }}" required>
        </div>

        <!-- Category -->
        <div class="mb-3">
            <label class="form-label small fw-bold">Category</label>
            <select name="category_id" class="form-select" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $food->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Price -->
        <div class="mb-3">
            <label class="form-label small fw-bold">Price (₹50 - ₹500)</label>
            <input type="number" name="price" class="form-control" min="50" max="500" value="{{ $food->price }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label class="form-label small fw-bold">Description</label>
            <textarea name="description" class="form-control" rows="3">{{ $food->description }}</textarea>
        </div>

        <!-- Image Upload -->
        <div class="mb-3">
            <label class="form-label small fw-bold">Food Image (Leave blank to keep current)</label>
            <input type="file" name="image" class="form-control" accept="image/*">

            @if($food->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$food->image) }}" 
                         class="rounded-3" width="100"
                         onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80';">
                </div>
            @endif
        </div>

        <!-- ✅ CUSTOMIZABLE OPTION -->
        <div class="mb-3 form-check">
            <input type="checkbox"
                   name="is_customizable"
                   value="1"
                   class="form-check-input"
                   id="is_customizable"
                   {{ $food->is_customizable ? 'checked' : '' }}>

            <label class="form-check-label fw-bold text-success" for="is_customizable">
                Enable Customization (Toppings / Add-ons)
            </label>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-veg w-100 rounded-pill py-3 fw-bold">
            Update Item
        </button>
    </form>
</div>
@endsection