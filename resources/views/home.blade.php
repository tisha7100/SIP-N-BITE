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

<section class="hero-container">
<div class="hero-bg"></div>
    <h1 class="hero-logo">
    <span class="logo-icon">🍽️</span> Sip N Bite
</h1>

<div class="hero-tagline">
    Find the best food & drinks in Surat
</div>
</section>

<div class="container py-5">
    <!-- Quick Options -->
    <div class="row g-4 mb-5">
    <div class="col-md-4">
        <a href="{{ route('menu') }}" class="option-card">
            <img src="{{ asset('storage/foods/order-food.png') }}" class="option-img" alt="">
            <div class="option-text">
                <h5>Order Online</h5>
                <p>Stay home and order to your doorstep</p>
            </div>
        </a>
    </div>

    <div class="col-md-4">
    <a href="{{ route('booking.index') }}" class="option-card">
        <img src="{{ asset('storage/foods/dinner.png') }}" class="option-img" alt="">
        <div class="option-text">
            <h5>Dining In</h5>
            <p>Reserve your table & enjoy café experience</p>
        </div>
    </a>
</div>

    <div class="col-md-4">
        <a href="#" class="option-card">
            <img src="{{ asset('storage/foods/night-club.png') }}" class="option-img" alt="">
            <div class="option-text">
                <h5>Nightlife</h5>
                <p>Explore the city's top nightlife outlets</p>
            </div>
        </a>
    </div>
</div>

    <!-- Menu Section -->
    <div id="menu">
        <h2 class="fw-bold mb-4">Inspiration for your first order</h2>
        
        <div class="category-filter">
            <a href="{{ route('home', ['category' => 'all']) }}" 
               class="cat-item {{ !request('category') || request('category') == 'all' ? 'active' : '' }}">
                All Items
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('home', ['category' => $cat->name]) }}" 
               class="cat-item {{ request('category') == $cat->name ? 'active' : '' }}">
                {{ $cat->name }}
            </a>
            @endforeach
        </div>

        
        <div class="row g-1">
            @forelse($foods as $food)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="food-card" onclick="window.location.href='{{ route('food.detail', $food->id) }}'">
                    <div class="food-img-wrapper">
                        <img src="{{ $food->image ? asset('storage/'.$food->image) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80' }}"
                             class="food-img" alt="{{ $food->name }}"
                             onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80';">
                        @if($food->discount)
                        <div class="position-absolute bottom-0 start-0 m-2 bg-info text-white px-2 py-1 rounded small fw-bold">
                            {{ $food->discount }}% OFF
                        </div>
                        @endif
                    </div>
                    <div class="food-info">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="mb-0 fw-bold fs-6">{{ $food->name }}</h4>
                            <span class="rating-badge">4.2 <i class="fas fa-star" style="font-size: 10px;"></i></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0 small text-muted text-truncate w-75">{{ $food->description }}</p>
                            <p class="mb-0 price-text fw-bold">₹{{ $food->price }}</p>
                        </div>
                       
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <h5 class="text-muted">No dishes found in this category.</h5>
            </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
    <a href="{{ route('menu') }}" class="btn btn-primary px-4 py-2">
        View Full Menu
    </a>
</div>
    </div>
</div>
@endsection
