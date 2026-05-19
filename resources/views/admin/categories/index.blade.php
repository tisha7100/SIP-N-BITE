@extends('admin.layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Manage Categories</h3>

    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm"
            data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="fas fa-plus me-2"></i> Add Category
    </button>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="table-responsive">
        <table class="table align-middle table-hover">

            <thead class="bg-light">
                <tr>
                    <th class="py-3">ID</th>
                    <th>Name</th>
                    <th>Items</th>
                    <th>Customizable</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($categories as $category)
                <tr>

                    <td class="text-muted fw-semibold">
                        #{{ $category->id }}
                    </td>

                    <td class="fw-bold">
                        {{ $category->name }}
                    </td>

                    <td>
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                            {{ \App\Models\Food::where('category_id', $category->id)->count() }} Items
                        </span>
                    </td>

                    <td>
                        @if($category->is_customizable)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </td>

                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">

                            <!-- EDIT -->
                            <button type="button"
                                    class="btn btn-sm btn-outline-info rounded-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCategoryModal{{ $category->id }}">
                                <i class="fas fa-pen"></i>
                            </button>

                            <!-- DELETE -->
                            <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-outline-danger rounded-3">
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
</div>
<!-- ================= ADD CATEGORY MODAL ================= -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">

            <div class="modal-header border-0">
                <h5 class="fw-bold">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <label class="form-label fw-semibold">Category Name</label>

                    <input type="text"
                           name="name"
                           class="form-control form-control-lg rounded-3"
                           placeholder="Enter category name..."
                           required>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_customizable" id="category_customizable">
                        <label class="form-check-label" for="category_customizable">
                            Enable customization for this category
                        </label>
                    </div>

                </div>

                <div class="modal-footer border-0">
                    <button type="submit"
                            class="btn btn-success w-100 rounded-pill py-2 fw-bold">
                        Save Category
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================= EDIT MODALS ================= -->
@foreach($categories as $category)
<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">

            <div class="modal-header border-0">
                <h5 class="fw-bold">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    <label class="form-label fw-semibold">Category Name</label>

                    <input type="text"
                           name="name"
                           value="{{ $category->name }}"
                           class="form-control form-control-lg rounded-3"
                           required>

                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" name="is_customizable" id="category_customizable_{{ $category->id }}" {{ $category->is_customizable ? 'checked' : '' }}>
                        <label class="form-check-label" for="category_customizable_{{ $category->id }}">
                            Enable customization for this category
                        </label>
                    </div>

                </div>

                <div class="modal-footer border-0">
                    <button type="submit"
                            class="btn btn-primary w-100 rounded-pill py-2 fw-bold">
                        Update Category
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endforeach

@endsection