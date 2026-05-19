@extends('layouts.app')

@push('css')
<style>
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; color: var(--zomato-grey); }
    .breadcrumb { font-size: 0.85rem; }
    .breadcrumb a { color: var(--zomato-grey); text-decoration: none; }
    .breadcrumb-item.active { color: #222; }

    .food-detail-card {
        border-radius: 1.5rem;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.08);
        background: #fff;
    }

    .food-detail-card img {
        border-radius: 1.5rem;
        min-height: 500px;
        max-height: 500px;
        object-fit: cover;
    }

    .food-detail-header {
        gap: 1.25rem;
        align-items: flex-start;
    }

    .food-detail-header h1 {
        font-size: clamp(2rem, 3vw, 2.5rem);
        letter-spacing: -0.02em;
    }

    .rating-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(239, 79, 85, 0.1);
        color: var(--zomato-red);
        border-radius: 999px;
        padding: 0.55rem 0.9rem;
        font-weight: 700;
    }

    .food-detail-meta {
        margin-bottom: 1.5rem;
    }

    .addon-card {
        border: 1px solid var(--zomato-border);
        border-radius: 1rem;
        padding: 1.75rem;
        background: #fff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.03);
    }

    .addon-card .form-check {
        padding: 0.9rem 0;
        margin: 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
    }

    .addon-card .form-check:last-child {
        border-bottom: none;
    }

    .addon-card .form-check-label {
        font-weight: 500;
    }

    .qty-selector {
        display: flex;
        align-items: center;
        background: #f8f8f8;
        border-radius: 0.8rem;
        padding: 0.35rem;
        min-width: 140px;
    }

    .qty-btn {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.06);
        font-size: 1rem;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--zomato-red);
        border-radius: 0.85rem;
    }

    .qty-selector input {
        width: 60px;
        min-width: 60px;
        margin: 0 0.5rem;
        padding: 0.5rem 0;
    }

    .btn-zomato {
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .btn-zomato:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 25px rgba(239, 79, 85, 0.15);
    }

    .review-card {
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 1rem;
        background: #fbfbfe;
        padding: 1.25rem;
    }

    .review-card strong {
        display: block;
        margin-bottom: 0.35rem;
    }

    .review-card .rating-badge {
        font-size: 0.95rem;
        padding: 0.45rem 0.8rem;
    }

    .review-card p {
        margin-bottom: 0.5rem;
    }

    .customer-reviews-section {
        margin-top: 3rem;
    }

    .review-form-card {
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 1rem;
    background: #fff;
    padding: 1.2rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    margin-top: 1.5rem;
    width: 100%;
}

    .review-form-card h4 {
        font-size: 1rem;
        margin-bottom: 0.75rem;
    }

    .review-form-card .form-label {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }

    .review-form-card textarea {
        min-height: 120px;
    }

    .review-actions {
        display: flex;
        justify-content: flex-start;
        gap: 0.75rem;
        flex-wrap: wrap;
        margin-top: 0.85rem;
        align-items: center;
    }

    .review-actions .btn-zomato {
        min-width: 140px;
        padding: 0.65rem 1rem;
    }

    .review-list {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .review-card {
        padding: 1rem;
    }

    .review-card .rating-badge {
        font-size: 0.9rem;
        padding: 0.35rem 0.65rem;
    }

    .review-note {
        border-radius: 1rem;
        padding: 0.85rem 1rem;
    }

    @media (max-width: 991px) {
        .review-form-card {
            max-width: 100%;
        }
    }

    .customer-reviews-title {
        margin-bottom: 1rem;
    }

    .review-note {
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        background: #f8f9fb;
    }

    .food-detail-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    @media (max-width: 767px) {
        .food-detail-card img {
            min-height: 350px;
            max-height: 350px;
        }

        .food-detail-header {
            flex-direction: column;
        }

        .qty-selector {
            width: 100%;
            justify-content: space-between;
        }
    }

    .form-check-input:checked {
        background-color: var(--zomato-red);
        border-color: var(--zomato-red);
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('home') }}#menu">Order Online</a></li>
            <li class="breadcrumb-item active">{{ $food->name }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        <div class="col-md-6">
            <div class="food-detail-card shadow-sm">
                <img src="{{ $food->image ? asset('storage/'.$food->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80' }}" 
                     class="w-100" style="height: 500px; object-fit: cover;"
                      onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80';">
            </div>
            <div class="review-form-card mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Leave feedback</h4>
                    <span class="text-muted small">{{ $food->reviews->count() }} review{{ $food->reviews->count() === 1 ? '' : 's' }}</span>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @auth
                    <form action="{{ route('menu.review', $food->id) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="rating" class="form-label">Rating</label>
                                <select id="rating" name="rating" class="form-select @error('rating') is-invalid @enderror">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-8">
                                <label for="comment" class="form-label">Comment</label>
                                <textarea id="comment" name="comment" class="form-control @error('comment') is-invalid @enderror">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="review-actions mt-3">
                            <button type="submit" class="btn btn-zomato btn-sm">Submit Review</button>
                            <p class="text-muted small mb-0">Share your experience with this dish.</p>
                        </div>
                    </form>
                @else
                    <div class="review-note mb-0">
                        <p class="mb-0">Want to leave a review? <a href="{{ route('login') }}">Log in</a> to share your feedback.</p>
                    </div>
                @endauth
                <div class="review-list mt-4">
                    @if($food->reviews->count())
                        @foreach($food->reviews as $review)
                            <div class="review-card mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>{{ $review->user->name ?? 'Guest' }}</strong>
                                    <span class="rating-badge">
                                        {{ $review->rating }} <i class="fas fa-star" style="font-size: 10px;"></i>
                                    </span>
                                </div>
                                <p class="text-muted small mb-2">{{ $review->created_at->format('M d, Y') }}</p>
                                <p class="mb-0">{{ $review->comment ?: 'No comment provided.' }}</p>
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-secondary mb-0">
                            No reviews yet. Be the first to share your feedback.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="food-detail-sidebar">
                <div class="d-flex justify-content-between align-items-start food-detail-header">
                    <div>
                        <h1 class="fw-800 mb-1">{{ $food->name }}</h1>
                        <p class="text-muted mb-3">{{ $food->category->name }} • {{ $food->delivery_time ?? 25 }} min</p>
                    </div>
                    <div class="text-end">
                        @php $foodAvgRating = $food->reviews->avg('rating'); @endphp
                        <span class="rating-badge fs-5 px-3 py-1">
                            {{ $foodAvgRating ? number_format($foodAvgRating, 1) : 'New' }} <i class="fas fa-star small"></i>
                        </span>
                        <p class="text-muted small mt-1">
                            {{ $food->reviews->count() ? $food->reviews->count() . ' review' . ($food->reviews->count() > 1 ? 's' : '') : 'Be the first to review' }}
                        </p>
                    </div>
                </div>

                <p class="fs-5 text-muted mb-4">{{ $food->description }}</p>


            <form action="{{ route('cart.add') }}" method="POST" id="cartForm">
                @csrf
                <input type="hidden" name="food_id" value="{{ $food->id }}">
                
                <h5 class="fw-bold mb-3">Customise as per your taste</h5>
                
                @if(count($groups) > 0)
                    <div class="addon-card mb-4">

    @foreach($groups as $group)

        <div class="mb-3">

            {{-- GROUP NAME --}}
            <h5 class="fw-bold mb-2">
                {{ $group->name }}
            </h5>

            {{-- OPTIONS --}}
            @foreach($group->addons as $addon)

                <div class="form-check d-flex justify-content-between align-items-center py-2 border-bottom">

                    <div>

                        {{-- INPUT TYPE (radio or checkbox) --}}
                        <input class="form-check-input addon-check"
                            type="{{ $group->type == 'radio' ? 'radio' : 'checkbox' }}"

                            name="{{ $group->type == 'radio'
                                    ? 'group_'.$group->id
                                    : 'addons[]' }}"

                            value="{{ $addon->id }}"
                            data-price="{{ $addon->price }}"
                            id="addon{{ $addon->id }}">

                        <label class="form-check-label ms-2" for="addon{{ $addon->id }}">
                            {{ $addon->name }}
                        </label>

                    </div>

                    {{-- PRICE --}}
                    <span class="text-muted">
                        ₹{{ $addon->price }}
                    </span>

                </div>

            @endforeach

        </div>

    @endforeach

    </div>
                @else
                    <div class="alert alert-secondary mb-4">
                        No customizations available for this item.
                    </div>
                @endif

                <div class="d-flex align-items-center gap-4 mb-5">
                    <div class="qty-selector">
                        <button class="qty-btn" type="button" onclick="changeQty(-1)"><i class="fas fa-minus"></i></button>
                        <input type="number" name="quantity" id="quantity" class="form-control text-center bg-transparent border-0 fw-bold" style="width: 50px;" value="1" min="1" readonly>
                        <button class="qty-btn" type="button" onclick="changeQty(1)"><i class="fas fa-plus"></i></button>
                    </div>
                    <div class="h3 fw-800 mb-0">₹<span id="total-price">{{ $food->price }}</span></div>
                </div>

                @auth
                <button type="submit" class="btn btn-zomato btn-lg w-100 py-3 rounded-3 shadow-sm fw-bold">
                    <i class="fas fa-plus me-2"></i> Add Item to Cart
                </button>
                @else
                <button type="button" onclick="requireLogin()" class="btn btn-zomato btn-lg w-100 py-3 rounded-3 shadow-sm fw-bold">
                    Join Sip N Bite to Order
                </button>
                @endauth
            </form>

        </div>
    </div>
</div>

@push('js')
<script>
    const basePrice = {{ $food->price }};

    function changeQty(amt) {
        let q = document.getElementById('quantity');
        let newVal = parseInt(q.value) + amt;
        if (newVal >= 1) {
            q.value = newVal;
            calculateTotal();
        }
    }

    function calculateTotal() {
        let qty = parseInt(document.getElementById('quantity').value);
        let addonsPrice = 0;
        document.querySelectorAll('.addon-check:checked').forEach(cb => {
            addonsPrice += parseFloat(cb.getAttribute('data-price'));
        });
        let total = (basePrice + addonsPrice) * qty;
        document.getElementById('total-price').innerText = total.toFixed(2);
    }

    document.querySelectorAll('.addon-check').forEach(cb => {
        cb.addEventListener('change', calculateTotal);
    });

    function requireLogin() {
        Swal.fire({
            title: 'Please Log in',
            text: 'You need to be logged in to add items to your cart.',
            icon: 'info',
            confirmButtonText: 'Log in Now',
            confirmButtonColor: '#ef4f5f',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("login") }}';
            }
        });
    }
</script>
@endpush
<script>

document.getElementById('cartForm').addEventListener('submit', function () {

    // remove old hidden inputs first
    document.querySelectorAll('.dynamic-addon').forEach(el => el.remove());

    // get all selected radio buttons
    document.querySelectorAll('input[type=radio]:checked').forEach(radio => {

        let hidden = document.createElement('input');

        hidden.type = 'hidden';
        hidden.name = 'addons[]';
        hidden.value = radio.value;
        hidden.classList.add('dynamic-addon');

        document.getElementById('cartForm').appendChild(hidden);

    });

});

</script>
@endsection

