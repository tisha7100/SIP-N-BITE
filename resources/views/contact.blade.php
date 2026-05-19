@extends('layouts.app')

@push('css')
<style>
    .contact-hero {
        min-height: 42vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, rgba(239, 79, 95, 0.12), rgba(248, 248, 248, 0.95));
        padding: 4rem 0;
    }

    .contact-hero .page-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        letter-spacing: -0.04em;
        margin-bottom: 1rem;
        color: var(--zomato-dark);
    }

    .contact-hero .page-subtitle {
        color: var(--zomato-grey);
        font-size: 1rem;
        line-height: 1.75;
    }

    .contact-card {
        border-radius: 1rem;
        border: 1px solid var(--zomato-border);
        padding: 2rem;
        background: #fff;
    }

    .contact-card .form-control {
        border-radius: 0.75rem;
    }

    .contact-detail strong {
        display: block;
        margin-bottom: 0.5rem;
        color: var(--zomato-dark);
    }

    .contact-detail p {
        margin: 0;
        color: var(--zomato-grey);
        line-height: 1.7;
    }
</style>
@endpush

@section('content')
<section class="contact-hero">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <p class="text-uppercase fw-bold text-zomato mb-2">Contact Us</p>
                <h1 class="page-title">We’re here to help. Let’s connect.</h1>
                <p class="page-subtitle">Questions about ordering, reservations, or our menu? Send a message or use the details below and we’ll get back to you as soon as possible.</p>
            </div>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row gy-4">
        <div class="col-lg-5">
            <div class="contact-card">
                <h2 class="fw-bold mb-4">Reach out</h2>
                <div class="contact-detail mb-4">
                    <strong>Address</strong>
                    <p>Athwa Gate, Surat, Gujarat, India</p>
                </div>
                <div class="contact-detail mb-4">
                    <strong>Phone</strong>
                    <p>+91 12345 67890</p>
                </div>
                <div class="contact-detail mb-4">
                    <strong>Email</strong>
                    <p>hello@sipnbite.com</p>
                </div>
                <div class="contact-detail">
                    <strong>Opening hours</strong>
                    <p>Daily, 11:00 AM – 11:00 PM</p>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="contact-card">
                <h2 class="fw-bold mb-4">Send us a message</h2>
                <form action="#" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Your name</label>
                        <input type="text" class="form-control" placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" class="form-control" placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Subject</label>
                        <input type="text" class="form-control" placeholder="How can we help?">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea class="form-control" rows="6" placeholder="Type your message"></textarea>
                    </div>
                    <button type="submit" class="btn btn-zomato px-4">Submit message</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection