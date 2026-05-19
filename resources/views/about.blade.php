@extends('layouts.app')

@push('css')
<style>
    :root {
        --text-red-brand: #ef4f5f;
    }

    .page-hero {
        min-height: 50vh;
        display: flex;
        align-items: center;
        /* Tuned high-transparency overlay mask to let the white cafe image look transparent */
        background: linear-gradient(135deg, rgba(30, 30, 30, 0.4), rgba(20, 20, 20, 0.5)), 
                    url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=80');
        background-size: cover;
        background-position: center;
        padding: 4rem 0;
        position: relative;
    }

    .page-hero .page-title {
        font-size: clamp(2.2rem, 5vw, 3.5rem);
        font-weight: 800;
        letter-spacing: -0.04em;
        margin-bottom: 1.2rem;
        color: #ffffff; /* Remains perfectly sharp and high contrast */
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3); /* Adds readability over transparent bg */
    }

    .page-hero .page-subtitle {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #ffffff; 
        max-width: 750px;
        text-shadow: 0 1px 6px rgba(0, 0, 0, 0.3);
    }

    /* Core Metrics / Stats Counter Styling */
    .stat-card {
        background: #fff;
        border: 1px solid var(--zomato-border);
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        border-color: rgba(239, 79, 95, 0.3);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--zomato-red);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .about-value {
        border-radius: 1rem;
        border: 1px solid var(--zomato-border);
        padding: 1.5rem;
        background: #fff;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        height: 100%;
    }

    .about-value:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 45px rgba(0, 0, 0, 0.06);
        border-color: rgba(239, 79, 95, 0.2);
    }

    .about-value .icon-box {
        width: 45px;
        height: 45px;
        background-color: rgba(239, 79, 95, 0.1);
        color: var(--zomato-red);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .about-value h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--zomato-dark);
    }

    .about-value p {
        margin: 0;
        color: var(--zomato-grey);
        line-height: 1.7;
    }

    /* Embedded showcase imagery aesthetics */
    .about-img-frame {
        position: relative;
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.08);
        border: 1px solid var(--zomato-border);
    }

    .about-img-frame img {
        transition: transform 0.5s ease;
        object-fit: cover;
    }

    .about-img-frame:hover img {
        transform: scale(1.04);
    }

    .section-divider {
        opacity: 0.08;
        margin: 4rem 0;
    }
</style>
@endpush

@section('content')
<section class="page-hero">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-8">
                <p class="text-uppercase fw-bold mb-2 ls-wide" style="color: rgba(255, 255, 255, 0.85); font-size: 0.9rem; letter-spacing: 0.05em;">About Sip N Bite</p>
                <h1 class="page-title">Local flavour, thoughtful service.</h1>
                <p class="page-subtitle">From the first refreshing sip to the final savory bite, our mission is to combine authentic hospitality with approachable plates, creating a relaxed digital and physical dining experience across Surat.</p>
                <div class="mt-4 d-flex gap-3">
                    <a href="{{ route('menu') }}" class="btn btn-light px-4 py-2 text-dark fw-bold" style="border-radius: 0.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">Explore Our Menu</a>
                    <a href="{{ route('booking.index') }}" class="btn btn-outline-light px-4 py-2" style="border-radius: 0.5rem;">Book a Table</a>
                </div>
            </div>
            <div class="col-lg-4 text-center d-none d-lg-block">
                <div class="p-4 bg-white rounded-4 shadow-lg border text-center opacity-95">
                    <i class="fas fa-utensils mb-3" style="font-size: 3.5rem; color: var(--zomato-red);"></i>
                    <h5 class="fw-bold mb-0 text-dark">SIP N' BITE</h5>
                    <p class="small text-muted mt-1 mb-0">ATHWA  GATE ,SURAT</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    
    <div class="row g-4 mb-5 text-center">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number">100%</div>
                <div class="fw-600 text-dark">Hygienic Kitchen Management</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number">Real-Time</div>
                <div class="fw-600 text-dark">Table Reservation Checks</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-number">MVC</div>
                <div class="fw-600 text-dark">Laravel Framework Architecture</div>
            </div>
        </div>
    </div>

    <hr class="section-divider">

    <div class="row g-5 align-items-center">
        <div class="col-lg-5">
            <div class="pe-lg-3">
                <h2 class="fw-800 mb-3" style="font-size: 2.2rem; letter-spacing: -0.03em; color: var(--zomato-dark);">What we stand for</h2>
                <p class="text-muted fs-6 mb-4" style="line-height: 1.7;">Sip N Bite was built for food lovers who enjoy fresh flavors without complications. Our system links a robust administrative engine with client-centric web convenience.</p>
                
                <div class="about-img-frame" style="height: 320px;">
                    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=800&q=80" class="w-100 h-100" alt="Sip N Bite Minimal White Cafe Interior">
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="about-value">
                        <div class="icon-box"><i class="fas fa-fire"></i></div>
                        <h3>Local favourites</h3>
                        <p>Our menu celebrates local palates with approachable North Indian, Indo-Chinese, and classic café favorites structured for seamless online selection.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-value">
                        <div class="icon-box"><i class="fas fa-chair"></i></div>
                        <h3>Friendly dining</h3>
                        <p>Whether pre-booking an evening table slot or managing bulk menu items, our UI routes ensure a calm and responsive user journey.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-value">
                        <div class="icon-box"><i class="fas fa-sparkles"></i></div>
                        <h3>Clean, cozy space</h3>
                        <p>Comfortable table assignments, intuitive structural design, and clean alignment blocks that fit desktop views and mobile formats smoothly.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="about-value">
                        <div class="icon-box"><i class="fas fa-clock"></i></div>
                        <h3>Made for moments</h3>
                        <p>Quick weekday office lunches, weekend family meals, or fast doorstep delivery drop-offs are all part of our operations logic matrix.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection