@extends('layouts.app')

@push('css')
<style>
    /* Hero Section */
    .hero-container {
    height: 75vh;
    min-height: 420px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    text-align: center;
    color: white;
}

.hero-bg {
    position: absolute;
    inset: 0;
    background-image: url("https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=1920&auto=format&fit=crop");
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    z-index: -1;
}

.hero-bg::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
}

/* Main Title */
.hero-logo {
    font-family: 'Poppins', sans-serif;
    font-size: 4rem;
    font-weight: 800;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;

    background: linear-gradient(90deg, #f8f5f5, #ede8e7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;

    text-shadow: 0 5px 25px rgba(0,0,0,0.4);
}


.hero-logo, .hero-tagline {
    animation: fadeUp 1s ease forwards;
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
/* Tagline */
.hero-tagline {
    font-family: 'Poppins', sans-serif;
    font-size: 1.4rem;
    font-weight: 400;
    margin-top: 10px;
    letter-spacing: 0.5px;
    opacity: 0.9;
}
    /* Quick Options */
    .option-card {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 20px;
    border-radius: 16px;
    background: #fff;
    border: 1px solid #eee;
    text-decoration: none;
    color: #333;
    transition: 0.3s ease;
    height: 100%;
}

.option-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
}

.option-img {
    width: 70px;
    height: 70px;
    object-fit: contain;
}

.option-text h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.1rem;
}

.option-text p {
    margin: 0;
    font-size: 0.9rem;
    color: #777;
}
    /* Category Filter */
    .category-filter {
        display: flex;
        gap: 1.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--zomato-border);
    }

    .cat-item {
        color: var(--zomato-grey);
        font-weight: 500;
        padding: 0.5rem 0;
        text-decoration: none;
        white-space: nowrap;
        position: relative;
    }

    .cat-item.active {
        color: var(--zomato-red);
    }

    .cat-item.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--zomato-red);
    }

    /* Food Card */
    .food-card {
        border-radius: 1rem;
        overflow: hidden;
        transition: box-shadow 0.2s;
        cursor: pointer;
        padding: 0.8rem;
        border: 1px solid transparent;
        height: 100%;
    }

    .food-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}


    .food-img-wrapper {
    height: 250px;
    border-radius: 12px;
    overflow: hidden;
}

    .food-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

    .food-info {
        padding-top: 0.8rem;
    }

    .rating-badge {
        background: var(--zomato-rating-green);
        color: white;
        padding: 0.2rem 0.4rem;
        border-radius: 0.4rem;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .price-text {
        color: var(--zomato-grey);
        font-size: 0.9rem;
    }
</style>

@endpush

@section('content')

<div class="container py-5">
    <h2 class="fw-bold mb-4">Our Menu</h2>

    <!-- Category Filter -->
   <div class="category-filter">
    <a href="{{ route('menu', ['category' => 'all']) }}" 
       class="cat-item {{ request('category') == 'all' ? 'active' : '' }}">
        All
    </a>

    @foreach($categories as $cat)
        <a href="{{ route('menu', ['category' => $cat->name]) }}" 
           class="cat-item {{ request('category') == $cat->name ? 'active' : '' }}">
            {{ $cat->name }}
        </a>
    @endforeach
</div>

    <!-- Food Grid -->
    <div class="row g-4">
        @forelse($foods as $food)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="food-card" onclick="window.location.href='{{ route('food.detail', $food->id) }}'">
                <div class="food-img-wrapper">
                    <img src="{{ $food->image ? asset('storage/'.$food->image) : 'https://via.placeholder.com/300' }}"
                         class="food-img" alt="{{ $food->name }}">
                </div>

                <div class="food-info">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h4 class="mb-0 fw-bold fs-6">{{ $food->name }}</h4>
                        <span class="rating-badge">
                            {{ $food->reviews_count ? number_format($food->reviews_avg_rating, 1) : 'New' }}
                            <i class="fas fa-star" style="font-size: 10px;"></i>
                        </span>
                    </div>

                    <p class="text-muted small mb-1">
                        {{ $food->delivery_time ?? 25 }} min •
                        {{ $food->reviews_count ? $food->reviews_count . ' review' . ($food->reviews_count > 1 ? 's' : '') : 'No reviews yet' }}
                    </p>
                    <p class="text-muted small">{{ $food->description }}</p>
                    <p class="fw-bold">₹{{ $food->price }}</p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center">
            <h5>No food items found</h5>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $foods->links() }}
    </div>
</div>

@endsection