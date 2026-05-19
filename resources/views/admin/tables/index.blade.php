@extends('admin.layouts.app')

@section('content')
@push('css')
<style>
:root {
    --primary: #ef4f5f;
    --bg: #f7f8fc;
    --text: #1f1f1f;
    --muted: #6c6c6c;
    --border: #eaeaea;
}

/* PAGE BACKGROUND */
body {
    background: var(--bg);
}

/* HEAD TITLE */
h3.fw-bold {
    font-size: 28px;
    letter-spacing: -0.5px;
    color: var(--text);
}

/* CARD STYLE */
.card {
    border-radius: 18px;
    backdrop-filter: blur(8px);
    background: rgba(255,255,255,0.9);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 45px rgba(0,0,0,0.08);
}

/* ================= TABLE GRID ================= */
.table-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}

/* TABLE CARD BASE */
.table-card {
    position: relative;
    padding: 18px 12px;
    border-radius: 16px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
    overflow: hidden;
}

/* AVAILABLE */
.table-card.bg-white {
    border: 1px solid var(--border);
    background: linear-gradient(145deg, #ffffff, #f9f9f9);
}

.table-card.bg-white:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.08);
    border-color: var(--primary);
}

/* BOOKED */
.table-card.bg-danger {
    background: linear-gradient(135deg, #ff5b6e, #e63b4f);
    color: #fff;
    box-shadow: 0 10px 25px rgba(239,79,95,0.25);
}

/* ICON */
.table-card i {
    font-size: 20px;
    margin-bottom: 6px;
    color: inherit;
}

/* DELETE BUTTON */
.table-card form button {
    background: rgba(255,255,255,0.9);
    border-radius: 50%;
    width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* ================= FORM ================= */
.form-control {
    border-radius: 12px;
    padding: 10px 14px;
    border: 1px solid var(--border);
    transition: 0.3s;
}

.form-control:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(239,79,95,0.1);
}

/* BUTTON */
.btn-danger {
    background: linear-gradient(135deg, #ef4f5f, #ff6b7a);
    border: none;
    transition: 0.3s;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(239,79,95,0.25);
}

/* ================= TABLE BOOKINGS ================= */
.table {
    border-collapse: separate;
    border-spacing: 0 10px;
}

.table thead th {
    border: none;
    color: var(--muted);
    font-size: 13px;
    text-transform: uppercase;
}

.table tbody tr {
    background: #fff;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border-radius: 14px;
    transition: 0.3s;
}

.table tbody tr:hover {
    transform: scale(1.01);
}

/* STATUS SELECT */
.form-select {
    border-radius: 12px;
    border: 1px solid var(--border);
    font-size: 13px;
}

/* BADGES */
.badge {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

</style>
@endpush

<h3 class="fw-bold mb-4">Table Management</h3>

<div class="row g-4 mb-5">

    <!-- TABLE VISUAL -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4 rounded-4">
            <h5 class="fw-bold mb-4">Visual Layout</h5>

            <div class="p-4 bg-light rounded-4"
                 style="display:grid; grid-template-columns: repeat(4, 1fr); gap:16px;">

                @foreach($tables as $table)
                <div class="position-relative text-center p-3 rounded-4
                    {{ $table->status == 'booked' ? 'bg-danger text-white' : 'bg-white border' }}
                    shadow-sm transition">

                    <i class="fas fa-chair mb-2 fs-5"></i>

                    <div class="fw-bold">T-{{ $table->table_number }}</div>
                    <div style="font-size:12px;">
                        Capacity: {{ $table->capacity }}
                    </div>

                    @if($table->status !== 'booked')
                    <form action="{{ route('admin.tables.destroy', $table->id) }}"
                          method="POST"
                          onsubmit="return confirm('Delete this table?')"
                          class="position-absolute"
                          style="top:8px; right:10px;">

                        @csrf
                        @method('DELETE')

                        <button class="btn btn-sm p-0 text-danger">
                            <i class="fas fa-times"></i>
                        </button>
                    </form>
                    @else
                        <span class="badge bg-light text-dark mt-2">Booked</span>
                    @endif

                </div>
                @endforeach

            </div>
        </div>
    </div>

    <!-- ADD TABLE -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 rounded-4">
            <h5 class="fw-bold mb-4">Add New Table</h5>

            <form action="{{ route('admin.tables.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-bold">Table Number</label>
                    <input type="text" name="table_number"
                           class="form-control rounded-3"
                           placeholder="e.g. 12"
                           required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Capacity</label>
                    <input type="number" name="capacity"
                           class="form-control rounded-3"
                           placeholder="e.g. 4"
                           required>
                </div>

                <button class="btn btn-danger w-100 rounded-3 fw-bold">
                    + Add Table
                </button>
            </form>
        </div>
    </div>
</div>

<!-- BOOKINGS -->
<div class="card border-0 shadow-sm p-4 rounded-4">
    <h5 class="fw-bold mb-4">Recent Bookings</h5>

    <div class="table-responsive">
        <table class="table align-middle table-hover">

            <thead class="bg-light">
                <tr>
                    <th>Customer</th>
                    <th>Table</th>
                    <th>Date & Time</th>
                    <th>Purpose</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
            @foreach($bookings as $booking)
                <tr>
                    <td class="fw-bold">{{ $booking->name }}</td>

                    <td>
                        {{ $booking->table?->table_number ?? '—' }}
                    </td>

                    <td>
                        {{ $booking->booking_date }}
                        <br>
                        <small class="text-muted">{{ $booking->booking_time }}</small>
                    </td>

                    <td>
                        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                            {{ $booking->purpose ?? 'General' }}
                        </span>
                    </td>

                    <td>
                        <!-- FIXED: correct booking update route -->
                        <form action="{{ route('admin.bookings.update', $booking->id) }}"
                              method="POST">

                            @csrf
                            @method('PUT')

                            <select name="status"
                                    class="form-select form-select-sm rounded-pill"
                                    onchange="this.form.submit()">

                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>

                                <option value="accepted" {{ $booking->status == 'accepted' ? 'selected' : '' }}>
                                    Accepted
                                </option>

                                <option value="rejected" {{ $booking->status == 'rejected' ? 'selected' : '' }}>
                                    Rejected
                                </option>

                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>
                                    Completed
                                </option>

                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>
</div>

@endsection