@extends('layouts.app')

@push('css')
<style>
:root {
    --zomato-red: #ef4f5f;
    --border: #ececec;
    --text: #1e1e1e;
    --muted: #6c6c6c;
    --bg: #fafafa;
}

/* ================= HERO ================= */
.dine-in-header {
    background:
        linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.25)),
        url('https://images.unsplash.com/photo-1552566626-52f8b828add9?auto=format&fit=crop&w=1600&q=80');
    background-size: cover;
    background-position: center;
    height: 320px;
    border-radius: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 3rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 30px 80px rgba(0,0,0,0.18);
}

.dine-in-header::after {
    content: "";
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 20% 20%, rgba(239,79,95,0.22), transparent 50%);
}

.dine-in-header h1 {
    position: relative;
    z-index: 2;
}

.dine-in-header p {
    position: relative;
    z-index: 2;
}

/* ================= ALERT SUCCESS ================= */
.alert-success {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
    border: none;
    border-radius: 18px;
    padding: 16px 22px;
    margin-bottom: 25px;
    font-weight: 600;
    font-size: 15px;
    box-shadow: 0 15px 40px rgba(34,197,94,0.18);
    animation: smoothFade 0.4s ease;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success::before {
    content: "✓";
    width: 32px;
    height: 32px;
    background: rgba(255,255,255,0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: bold;
}

/* ================= ALERT ERROR ================= */
.alert-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    border: none;
    border-radius: 18px;
    padding: 16px 22px;
    margin-bottom: 25px;
    box-shadow: 0 15px 40px rgba(239,68,68,0.18);
    animation: smoothFade 0.4s ease;
}

.alert-danger ul {
    margin: 0;
    padding-left: 18px;
}

.alert-danger li {
    margin-bottom: 5px;
}

/* ================= TABLE GRID ================= */
.table-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(135px, 1fr));
    gap: 1.2rem;
}

/* ================= BASE CARD ================= */
.table-card {
    border-radius: 1.3rem;
    padding: 1.4rem 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s ease;
    border: 1px solid var(--border);
    background: #fff;
    box-shadow: 0 8px 22px rgba(0,0,0,0.05);
    position: relative;
}

.table-card:hover:not(.booked) {
    transform: translateY(-6px);
    border-color: rgba(239,79,95,0.35);
    box-shadow: 0 18px 45px rgba(239,79,95,0.12);
}

.table-icon {
    font-size: 1.8rem;
    margin-bottom: 0.4rem;
    color: var(--zomato-red);
}

/* ================= AVAILABLE ================= */
.table-card.available {
    background: linear-gradient(180deg, #fff, #fff9fa);
}

.table-card.available::after {
    content: "";
    position: absolute;
    inset: 0;
    border-radius: 1.3rem;
    background: radial-gradient(circle at center, rgba(239,79,95,0.06), transparent 70%);
    pointer-events: none;
}

/* ================= BOOKED ================= */
.table-card.booked {
    background: #f3f3f3;
    color: #a0a0a0;
    cursor: not-allowed;
    opacity: 0.85;
}

.table-card.booked::after {
    content: "BOOKED";
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 10px;
    font-weight: 700;
    background: #333;
    color: #fff;
    padding: 3px 8px;
    border-radius: 20px;
}

/* ================= SELECTED ================= */
.table-card.selected {
    background: var(--zomato-red);
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0 25px 60px rgba(239,79,95,0.25);
    border: none;
}

.table-card.selected .table-icon {
    color: #fff;
}

/* ================= FORM ================= */
.booking-form-card {
    border-radius: 1.5rem;
    border: 1px solid var(--border);
    background: #fff;
    box-shadow: 0 18px 50px rgba(0,0,0,0.08);
}

.form-control-zomato {
    height: 48px;
    border-radius: 0.8rem;
    border: 1px solid var(--border);
}

.form-control-zomato:focus {
    border-color: var(--zomato-red);
    box-shadow: 0 0 0 3px rgba(239,79,95,0.12);
}

.btn-zomato {
    background: var(--zomato-red);
    color: #fff;
    font-weight: 700;
    border-radius: 1rem;
    transition: 0.25s;
    border: none;
}

.btn-zomato:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 40px rgba(239,79,95,0.25);
    color: #fff;
}

/* ================= ANIMATION ================= */
@keyframes smoothFade {
    from {
        opacity: 0;
        transform: translateY(-12px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@section('content')
<div class="container py-4">

    <!-- HERO -->
    <div class="dine-in-header text-center">
        <div>
            <h1 class="display-5 fw-bold">Dining Experience</h1>
            <p class="opacity-75">Premium café table reservation system</p>
        </div>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- ERROR MESSAGE -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-5">

        <!-- TABLES -->
        <div class="col-lg-7">
            <h4 class="fw-bold mb-3">Choose Table</h4>

            <div class="table-grid">
                @foreach($tables as $table)
                <div class="table-card {{ in_array($table->id, $bookedTableIds) ? 'booked' : 'available' }}"
                id="table{{ $table->id }}"
                onclick="selectTable(
                    '{{ $table->id }}',
                    '{{ $table->table_number }}',
                    '{{ in_array($table->id, $bookedTableIds) ? 'booked' : 'available' }}'
                )">

                    <div class="table-icon">
                        <i class="fas fa-chair"></i>
                    </div>

                    <div class="fw-bold">T-{{ $table->table_number }}</div>
                    <div class="small text-muted">{{ $table->capacity }} Seater</div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- FORM -->
        <div class="col-lg-5">
            <div class="booking-form-card p-4">

                <h4 class="fw-bold mb-4">Reservation Details</h4>

                <form action="{{ route('booking.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="table_id" id="selected_table_id">

                    <div class="mb-3">
                        <label>Selected Table</label>
                        <input type="text"
                               id="selected_table_name"
                               class="form-control form-control-zomato bg-light"
                               readonly>
                    </div>

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text"
                               name="name"
                               class="form-control form-control-zomato"
                               value="{{ Auth::user()->name ?? '' }}"
                               placeholder="Enter your name">
                    </div>

                    <!-- <form method="GET" action="{{ route('booking.index') }}"> -->

    <div class="row g-2">

    <div class="col-6">
        <label>Date</label>

        <input type="date"
               name="booking_date"
               value="{{ $selectedDate }}"
               class="form-control form-control-zomato"
               onchange="window.location='?booking_date='+this.value+'&booking_time='+document.getElementById('booking_time').value">
    </div>

    <div class="col-6">
        <label>Time</label>

        <input type="time"
               id="booking_time"
               name="booking_time"
               value="{{ $selectedTime }}"
               class="form-control form-control-zomato"
               onchange="window.location='?booking_date='+document.querySelector('[name=booking_date]').value+'&booking_time='+this.value">
    </div>

</div>

<div class="mt-3">
    <label>Duration</label>

    <select name="duration_hours"
            class="form-select form-control-zomato">

        <option value="1">1 Hour</option>
        <option value="2">2 Hours</option>
        <option value="3">3 Hours</option>
        <option value="4">4 Hours</option>

    </select>
</div>
                    <div class="mt-3 mb-4">
                        <label>Purpose</label>

                        <select name="purpose"
                                class="form-select form-control-zomato">

                            <option>Birthday</option>
                            <option>Anniversary</option>
                            <option>Business</option>
                            <option>Casual</option>

                        </select>
                    </div>

                    <button class="btn btn-zomato w-100 py-2">
                        Confirm Booking
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

<script>
function selectTable(id, name, status)
{
    if(status === 'booked')
    {
        Swal.fire(
            "Booked",
            "Try another table",
            "warning"
        );
        return;
    }

    document.querySelectorAll('.table-card')
        .forEach(e => e.classList.remove('selected'));

    document.getElementById('table'+id)
        .classList.add('selected');

    document.getElementById('selected_table_id').value = id;

    document.getElementById('selected_table_name').value =
        "Table " + name;
}
</script>
@endsection