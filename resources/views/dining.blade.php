@extends('layouts.app')

@php
use Illuminate\Support\Facades\Auth;
@endphp

@push('css')
<style>
    /* Scoped to dining page — does not redefine global :root */
    .dining-page-v2 {
        --d-accent: var(--zomato-red, #ef4f5f);
        --d-ink: var(--zomato-dark, #1c1c1c);
        --d-muted: var(--zomato-grey, #696969);
        --d-line: var(--zomato-border, #e8e8e8);
        --d-bg: var(--zomato-light-grey, #f8f8f8);
        --d-green: var(--zomato-rating-green, #24963f);
        --d-card: #ffffff;
        --d-shadow: 0 22px 50px rgba(28, 28, 28, 0.06);
        --d-shadow-lg: 0 32px 80px rgba(28, 28, 28, 0.08);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--d-ink);
        font-size: 0.9375rem;
        -webkit-font-smoothing: antialiased;
    }

    /* —— Hero —— */
    .dining-page-v2 .d2-hero {
        position: relative;
        min-height: min(88vh, 900px);
        display: flex;
        align-items: flex-end;
        color: #fff;
        overflow: hidden;
    }

    .dining-page-v2 .d2-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(to top, rgba(0, 0, 0, 0.82) 0%, rgba(0, 0, 0, 0.35) 45%, rgba(0, 0, 0, 0.2) 100%),
          url("https://images.unsplash.com/photo-1453614512568-c4024d13c247?q=90&w=2400&auto=format&fit=crop") center / cover no-repeat;
        transform: scale(1.06);
        animation: d2-hero-drift 22s ease-in-out infinite alternate;
    }

    @keyframes d2-hero-drift {
        from { transform: scale(1.06) translate(0, 0); }
        to   { transform: scale(1.1) translate(-1.2%, 0.8%); }
    }

    .dining-page-v2 .d2-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 90% 80% at 20% 10%, rgba(239, 79, 95, 0.22), transparent 50%),
                    radial-gradient(ellipse 70% 50% at 85% 60%, rgba(255, 255, 255, 0.06), transparent 45%);
        pointer-events: none;
    }

    .dining-page-v2 .d2-hero__inner {
        position: relative;
        z-index: 1;
        width: 100%;
        padding: clamp(2.5rem, 7vw, 5rem) 0;
    }

    .dining-page-v2 .d2-breadcrumb {
        font-size: 0.765rem;
        opacity: 0.88;
        margin-bottom: 1rem;
    }

    .dining-page-v2 .d2-breadcrumb a {
        color: inherit;
        text-decoration: none;
    }

    .dining-page-v2 .d2-breadcrumb a:hover {
        text-decoration: underline;
    }

    .dining-page-v2 .d2-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.35rem 0.95rem;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.28);
        font-size: 0.68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(12px);
    }

    .dining-page-v2 .d2-title {
        font-size: clamp(2rem, 4.8vw, 3.35rem);
        font-weight: 800;
        line-height: 1.06;
        letter-spacing: -0.035em;
        max-width: 18ch;
        margin-top: 1rem;
        text-shadow: 0 8px 40px rgba(0, 0, 0, 0.35);
    }

    .dining-page-v2 .d2-lead {
        max-width: 32rem;
        margin-top: 1rem;
        font-size: 0.99375rem;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.88);
    }

    .dining-page-v2 .d2-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 0;
        margin-top: clamp(1.35rem, 3vw, 2rem);
        padding: 0;
        border-radius: 0.875rem;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(14px);
    }

    .dining-page-v2 .d2-stat {
        flex: 1 1 140px;
        padding: 1rem 1.15rem;
        border-right: 1px solid rgba(255, 255, 255, 0.12);
        min-width: 0;
    }

    .dining-page-v2 .d2-stat:last-child {
        border-right: none;
    }

    .dining-page-v2 .d2-stat strong {
        display: block;
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        opacity: 0.75;
        margin-bottom: 0.35rem;
    }

    .dining-page-v2 .d2-stat span {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .dining-page-v2 .d2-stat .d2-rating-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        background: linear-gradient(135deg, #2bb168, var(--d-green));
        color: #fff;
        padding: 0.28rem 0.55rem;
        border-radius: 0.35rem;
        font-size: 0.8375rem;
        font-weight: 700;
        box-shadow: 0 2px 12px rgba(36, 150, 63, 0.35);
    }

    .dining-page-v2 .d2-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1.65rem;
    }

    .dining-page-v2 .d2-btn-primary {
        background: var(--d-accent);
        color: #fff !important;
        padding: 0.75rem 1.45rem;
        border-radius: 0.625rem;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        border: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        box-shadow: 0 4px 22px rgba(239, 79, 95, 0.4);
    }

    .dining-page-v2 .d2-btn-primary:hover {
        background: #d13f4d;
        color: #fff !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 32px rgba(239, 79, 95, 0.38);
    }

    .dining-page-v2 .d2-btn-ghost {
        border: 2px solid rgba(255, 255, 255, 0.55);
        color: #fff !important;
        padding: 0.72rem 1.35rem;
        border-radius: 0.625rem;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        background: rgba(255, 255, 255, 0.04);
        transition: background 0.2s ease, border-color 0.2s ease;
    }

    .dining-page-v2 .d2-btn-ghost:hover {
        background: rgba(255, 255, 255, 0.16);
        border-color: rgba(255, 255, 255, 0.85);
        color: #fff !important;
    }

    /* —— Image story —— */
    .dining-page-v2 .d2-story {
        padding: clamp(3rem, 8vw, 5rem) 0;
        background: var(--d-bg);
        border-bottom: 1px solid var(--d-line);
    }

    .dining-page-v2 .d2-story__head {
        text-align: center;
        max-width: 560px;
        margin-inline: auto;
    }

    .dining-page-v2 .d2-eyebrow {
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: var(--d-accent);
        margin-bottom: 0.45rem;
    }

    .dining-page-v2 .d2-h2 {
        font-size: clamp(1.45rem, 3.2vw, 1.9rem);
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1.18;
        color: var(--d-ink);
        margin-bottom: 0;
    }

    .dining-page-v2 .d2-story__sub {
        margin-top: 0.65rem;
        color: var(--d-muted);
        font-size: 0.9rem;
        line-height: 1.58;
        margin-bottom: 0;
    }

    .dining-page-v2 .d2-image-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 0.75rem;
        margin-top: 2rem;
    }

    @media (max-width: 991.98px) {
        .dining-page-v2 .d2-image-grid {
            grid-template-columns: 1fr 1fr;
        }

        .dining-page-v2 .d2-image-grid .d2-cell--featured {
            grid-column: span 2;
        }
    }

    @media (max-width: 575.98px) {
        .dining-page-v2 .d2-image-grid {
            grid-template-columns: 1fr;
        }

        .dining-page-v2 .d2-image-grid .d2-cell--featured {
            grid-column: span 1;
        }
    }

    .dining-page-v2 .d2-cell {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid rgba(232, 232, 232, 0.9);
        box-shadow: var(--d-shadow);
        background: var(--d-card);
    }

    .dining-page-v2 .d2-cell img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.55s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .dining-page-v2 .d2-cell:hover img {
        transform: scale(1.04);
    }

    .dining-page-v2 .d2-cell--featured .d2-aspect {
        height: clamp(260px, 42vw, 420px);
    }

   .d2-cell--featured .d2-aspect {
    height: 450px;
}

.d2-cell:not(.d2-cell--featured) .d2-aspect {
    height: 450px;
}

    .dining-page-v2 .d2-story__caption {
        margin-top: 1.75rem;
        text-align: center;
        color: var(--d-muted);
        font-size: 0.89375rem;
        line-height: 1.55;
        font-style: italic;
        max-width: 560px;
        margin-inline: auto;
    }

    /* —— Trust row —— */
    .dining-page-v2 .d2-trust {
        padding: clamp(2.25rem, 5vw, 3.25rem) 0;
        background: #fff;
        border-bottom: 1px solid var(--d-line);
    }

    .dining-page-v2 .d2-trust__grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.35rem;
    }

    .dining-page-v2 .d2-trust__item i {
        width: 40px;
        height: 40px;
        border-radius: 0.625rem;
        background: var(--d-bg);
        color: var(--d-accent);
        display: grid;
        place-items: center;
        font-size: 0.9375rem;
        margin-bottom: 0.5rem;
    }

    .dining-page-v2 .d2-trust__item strong {
        display: block;
        font-size: 0.853125rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
    }

    .dining-page-v2 .d2-trust__item span {
        font-size: 0.8rem;
        color: var(--d-muted);
        line-height: 1.42;
    }

    /* —— Reviews —— */
    .dining-page-v2 .d2-reviews {
        padding: clamp(3rem, 8vw, 5rem) 0;
        background: linear-gradient(180deg, var(--d-bg) 0%, #fff 72%);
    }

    .dining-page-v2 .d2-review-card {
        height: 100%;
        background: var(--d-card);
        border: 1px solid var(--d-line);
        border-radius: 1rem;
        padding: clamp(1.35rem, 3vw, 1.85rem);
        box-shadow: var(--d-shadow);
        transition: box-shadow 0.28s ease, transform 0.28s ease, border-color 0.28s ease;
        position: relative;
        overflow: hidden;
    }

    .dining-page-v2 .d2-review-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--d-accent), rgba(239, 79, 95, 0.25));
        opacity: 0;
        transition: opacity 0.25s ease;
    }

    .dining-page-v2 .d2-review-card:hover {
        box-shadow: var(--d-shadow-lg);
        transform: translateY(-4px);
        border-color: rgba(239, 79, 95, 0.2);
    }

    .dining-page-v2 .d2-review-card:hover::before {
        opacity: 1;
    }

    .dining-page-v2 .d2-review-stars {
        color: #e8a849;
        font-size: 0.78rem;
        letter-spacing: 0.06em;
        margin-bottom: 0.875rem;
    }

    .dining-page-v2 .d2-review-quote {
        margin: 0;
        font-size: 0.90625rem;
        line-height: 1.58;
        color: var(--d-muted);
    }

    .dining-page-v2 .d2-review-meta {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        margin-top: 1.125rem;
        padding-top: 1.125rem;
        border-top: 1px solid var(--d-line);
    }

    .dining-page-v2 .d2-review-avatar {
        width: 44px;
        height: 44px;
        border-radius: 999px;
        background: linear-gradient(135deg, var(--d-accent), #ff8a93);
        color: #fff;
        font-size: 0.75rem;
        font-weight: 800;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .dining-page-v2 .d2-review-name {
        font-weight: 600;
        font-size: 0.86875rem;
        color: var(--d-ink);
    }

    .dining-page-v2 .d2-review-tag {
        font-size: 0.75rem;
        color: var(--d-muted);
    }

    /* —— Visit strip —— */
    .dining-page-v2 .d2-visit {
        padding: clamp(2.75rem, 7vw, 4rem) 0;
        background: #fff;
        border-top: 1px solid var(--d-line);
    }

    .dining-page-v2 .d2-visit-card {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.75rem;
        align-items: start;
        max-width: 1080px;
        margin-inline: auto;
    }

    @media (max-width: 767.98px) {
        .dining-page-v2 .d2-visit-card {
            grid-template-columns: 1fr;
        }
    }

    .dining-page-v2 .d2-visit-block {
        padding: 1.5rem;
        border-radius: 1rem;
        border: 1px solid var(--d-line);
        background: linear-gradient(180deg, #fff, var(--d-bg));
    }

    .dining-page-v2 .d2-visit-block h3 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .dining-page-v2 .d2-visit-block p {
        margin: 0;
        font-size: 0.875rem;
        color: var(--d-muted);
        line-height: 1.5;
    }

    .dining-page-v2 .d2-visit-link {
        color: var(--d-accent);
        font-weight: 700;
        text-decoration: none;
    }

    .dining-page-v2 .d2-visit-link:hover {
        text-decoration: underline;
    }

    .dining-page-v2 .d2-btn-dark {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--d-line);
        color: var(--d-ink) !important;
        padding: 0.72rem 1.35rem;
        border-radius: 0.625rem;
        font-weight: 600;
        font-size: 0.9375rem;
        text-decoration: none;
        background: #fff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .dining-page-v2 .d2-btn-dark:hover {
        border-color: rgba(239, 79, 95, 0.45);
        color: var(--d-ink) !important;
        box-shadow: 0 8px 24px rgba(28, 28, 28, 0.06);
    }

    @media (prefers-reduced-motion: reduce) {
        .dining-page-v2 .d2-hero::before {
            animation: none;
            transform: none;
        }

        .dining-page-v2 .d2-cell:hover img {
            transform: none;
        }

        .dining-page-v2 .d2-review-card:hover {
            transform: none;
        }
    }
</style>
@endpush

@section('content')
<div class="dining-page-v2">

    <header class="d2-hero" role="banner">
        <div class="container d2-hero__inner">
            <nav class="d2-breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="opacity-75"> · </span>
                <span>Dining</span>
            </nav>

            <span class="d2-chip"><i class="fas fa-location-dot" style="color: var(--zomato-red, #ef4f5f);"></i> Sip N Bite - Athwa Gate, Surat</span>

            <h1 class="d2-title">
                Dine in, slow down, and stay a little longer
            </h1>

            <p class="d2-lead">
                Warm lighting, fresh plates, and tables made for family meals, coffee breaks, and relaxed evenings at Sip N Bite in Surat.
            </p>

            <div class="d2-stats" role="presentation">
                <div class="d2-stat">
                    <strong>Hours</strong>
                    <span>11:00 AM - 11:00 PM</span>
                </div>
                <div class="d2-stat">
                    <strong>For two</strong>
                    <span>Rs. 550 - Rs. 850</span>
                </div>
                <div class="d2-stat">
                    <strong>Guest rating</strong>
                    <span class="d2-rating-pill">4.6 <i class="fas fa-star" style="font-size: 0.78em;"></i></span>
                </div>
                <div class="d2-stat">
                    <strong>Vibe</strong>
                    <span>Modern cafe - dine-in</span>
                </div>
            </div>

            <div class="d2-actions">
                <a href="{{ route('booking.index') }}" class="d2-btn-primary">Book a table</a>
                <a href="{{ route('menu') }}" class="d2-btn-ghost">View menu</a>
            </div>
        </div>
    </header>

    <section class="d2-story" aria-labelledby="d2-story-title">
        <div class="container">
            <div class="d2-story__head">
                <p class="d2-eyebrow">The space</p>
                <h2 id="d2-story-title" class="d2-h2">Inside Sip N Bite</h2>
                <p class="d2-story__sub">Wood, soft light, and a relaxed service rhythm built for lunches, espresso breaks, and unhurried evenings in Surat.</p>
            </div>

    <div class="d2-image-grid">
    <div class="d2-cell d2-cell--featured">
        <div class="d2-aspect">
            <img 
                src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=90&w=2000&auto=format&fit=crop"
                alt="Luxury modern restaurant interior with ambient lighting">
        </div>
    </div>

   <div class="d2-cell">
    <div class="d2-aspect">
        <img 
            src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=100&w=2400&auto=format&fit=crop"
            alt="Aesthetic modern cafe interior with warm lights">
    </div>
</div>

<div class="d2-cell">
    <div class="d2-aspect">
        <img 
            src="https://images.unsplash.com/photo-1509042239860-f550ce710b93?q=100&w=2400&auto=format&fit=crop"
            alt="Beautiful coffee and pastry cafe aesthetic">
    </div>
</div>
</div>

            <p class="d2-story__caption">
                A warm, contemporary cafe tuned for slow mornings and evenings that linger just a little longer.
            </p>
        </div>
    </section>

    <section class="d2-trust" aria-label="Highlights">
        <div class="container">
            <div class="d2-trust__grid">
                <div class="d2-trust__item">
                    <div><i class="fas fa-mug-hot"></i></div>
                    <strong>Barista coffees</strong>
                    <span>Single-origin pours, classics, and seasonal specials at the counter.</span>
                </div>
                <div class="d2-trust__item">
                    <div><i class="fas fa-utensils"></i></div>
                    <strong>Comfort food</strong>
                    <span>North Indian staples, grills, and Indo-Chinese favourites plated for dine-in.</span>
                </div>
                <div class="d2-trust__item">
                    <div><i class="fas fa-users"></i></div>
                    <strong>Tables for groups</strong>
                    <span>Birthdays and gatherings work best with a reservation on busy evenings.</span>
                </div>
                <div class="d2-trust__item">
                    <div><i class="fas fa-wifi"></i></div>
                    <strong>Work-friendly</strong>
                    <span>Reliable Wi-Fi and calm acoustics during daytime hours.</span>
                </div>
            </div>
        </div>
    </section>

    <section class="d2-reviews" aria-labelledby="d2-reviews-heading">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 520px;">
                <p class="d2-eyebrow">From our guests</p>
                <h2 id="d2-reviews-heading" class="d2-h2">What diners say about Sip N Bite</h2>
                <p class="d2-story__sub">A quick glimpse of the experience guests expect when they visit for dine-in.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <article class="d2-review-card h-100">
                        <div class="d2-review-stars" aria-label="5 out of 5 stars">*****</div>
                        <blockquote class="d2-review-quote">The ambience feels premium but relaxed. Coffee tasted fresh and the staff made the table feel well looked after.</blockquote>
                        <div class="d2-review-meta">
                            <span class="d2-review-avatar" aria-hidden="true">PM</span>
                            <div>
                                <div class="d2-review-name">Priya Mehta</div>
                                <div class="d2-review-tag">Surat - family lunch</div>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="col-md-6 col-lg-4">
                    <article class="d2-review-card h-100">
                        <div class="d2-review-stars" aria-label="5 out of 5 stars">*****</div>
                        <blockquote class="d2-review-quote">Perfect for evening plans. The lighting is soft, the food has flavour, and the space lets you sit without feeling rushed.</blockquote>
                        <div class="d2-review-meta">
                            <span class="d2-review-avatar" aria-hidden="true">KV</span>
                            <div>
                                <div class="d2-review-name">Krish Vasavada</div>
                                <div class="d2-review-tag">Surat - evening dine-in</div>
                            </div>
                        </div>
                    </article>
                </div>
                <div class="col-md-12 col-lg-4">
                    <article class="d2-review-card h-100">
                        <div class="d2-review-stars" aria-label="4 out of 5 stars">****</div>
                        <blockquote class="d2-review-quote">Weekends get lively, so booking ahead helps. Once seated, service was attentive and plating felt thoughtful for the price.</blockquote>
                        <div class="d2-review-meta">
                            <span class="d2-review-avatar" aria-hidden="true">AR</span>
                            <div>
                                <div class="d2-review-name">Anika Rao</div>
                                <div class="d2-review-tag">Vesu - brunch</div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section class="d2-visit" aria-labelledby="d2-visit-heading">
        <div class="container">
            <div class="text-center mb-4">
                <p class="d2-eyebrow">Plan ahead</p>
                <h2 id="d2-visit-heading" class="d2-h2">Visit Sip N Bite, Surat</h2>
            </div>

            <div class="d2-visit-card">
                <div class="d2-visit-block">
                    <h3><i class="fas fa-clock text-muted me-2"></i>Hours</h3>
                    <p>Daily, 11:00 AM - 11:00 PM. Holiday hours may extend.</p>
                </div>
                <div class="d2-visit-block">
                    <h3><i class="fas fa-map-pin text-muted me-2"></i>Location</h3>
                    <p>Athwa Gate, Surat, Gujarat. <a class="d2-visit-link" href="https://maps.google.com/?q=Sip+N+Bite+Surat" target="_blank" rel="noopener noreferrer">Open in Maps</a></p>
                </div>
                <div class="d2-visit-block">
                    <h3><i class="fas fa-chair text-muted me-2"></i>Reservations</h3>
                    <p>Reserve for groups, weekends, and evening dining. Walk-ins are welcome when tables are free.</p>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
