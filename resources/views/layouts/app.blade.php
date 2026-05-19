<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sip N Bite | Order Online</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --zomato-red: #ef4f5f;
            --zomato-dark: #1c1c1c;
            --zomato-grey: #696969;
            --zomato-light-grey: #f8f8f8;
            --zomato-border: #e8e8e8;
            --zomato-rating-green: #24963f;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: var(--zomato-dark);
            -webkit-font-smoothing: antialiased;
        }

        /* Navbar Styles */
        .navbar {
            padding: 1rem 0;
            background: #ffffff;
            transition: all 0.3s;
        }

        .navbar.home-nav {
            background: transparent;
            position: absolute;
            width: 100%;
            z-index: 1000;
            border-bottom: none;
        }

        .navbar.home-nav .nav-link, 
        .navbar.home-nav .navbar-brand {
            color: #ffffff !important;
        }

        .navbar-brand {
            font-size: 2rem;
            font-weight: 800;
            color: var(--zomato-red) !important;
            letter-spacing: -1px;
        }

        .nav-link {
            font-weight: 400;
            font-size: 1.1rem;
            color: var(--zomato-dark) !important;
            margin: 0 1rem;
            opacity: 0.8;
        }

        .nav-link:hover {
            opacity: 1;
        }

        /* Buttons */
        .btn-zomato {
            background-color: var(--zomato-red);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .btn-zomato:hover {
            background-color: #d13f4d;
            color: white;
        }

        /* Footer Styles */
        .footer {
            background-color: var(--zomato-light-grey);
            padding: 3rem 0;
            margin-top: 5rem;
            border-top: 1px solid var(--zomato-border);
        }

        .footer h6 {
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
            color: var(--zomato-dark);
        }

        .footer-link {
            color: var(--zomato-grey);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .footer-link:hover {
            color: var(--zomato-dark);
        }

        /* Utility Classes */
        .ls-wide { letter-spacing: 0.1em; }
        .fw-800 { font-weight: 800; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #999; }
    </style>
    @stack('css')
</head>
<body>
    @php
        $isHome = Route::currentRouteName() == 'home' || Route::currentRouteName() == 'welcome';
    @endphp

    <nav class="navbar navbar-expand-lg {{ $isHome ? 'home-nav' : 'border-bottom sticky-top' }}">
        <div class="container">
            @if(!$isHome)
                <a class="navbar-brand" href="{{ route('home') }}">
                    Sip N Bite
                </a>
            @else
                <div class="navbar-brand" style="visibility: hidden;">Sip N Bite</div>
            @endif

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navContent">

    <!-- LEFT SIDE LINKS -->
    <ul class="navbar-nav align-items-right ms-auto">

    <li class="nav-item">
        <a href="{{ route('home') }}" class="nav-link">Home</a>
    </li>

    <li class="nav-item">
        <a href="{{ route('about') }}" class="nav-link">About Us</a>
    </li>

    <li class="nav-item">
        <a href="{{ route('menu') }}" class="nav-link">Menu</a>
    </li>

    <li class="nav-item">
        <a href="{{ route('dining') }}" class="nav-link">Dining</a>
    </li>

    <li class="nav-item">
        <a href="{{ route('booking.index') }}" class="nav-link">Book Table</a>
    </li>

    <li class="nav-item">
        <a href="{{ route('contact.index') }}" class="nav-link">Contact Us</a>
    </li>
    
</ul>
        @auth

        <!-- CART ICON -->
        <a href="{{ route('cart.index') }}" class="nav-link position-relative">
            <i class="fas fa-shopping-cart fs-5"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ \App\Models\Cart::where('user_id', Auth::id())->count() }}
            </span>
        </a>

        <!-- USER DROPDOWN -->
        <div class="dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                <img src="{{'https://ui-avatars.com/api/?name='.Auth::user()->name }}" width="35" height="35" class="rounded-circle border">
                <span>{{ explode(' ', Auth::user()->name)[0] }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2">
                <li>
                    <a class="dropdown-item py-2" href="{{ route('orders.index') }}">
                        <i class="fas fa-shopping-bag me-2"></i> My Orders
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-2" href="{{ route('cart.index') }}">
                        <i class="fas fa-shopping-cart me-2"></i> My Cart
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
    @csrf
    <button type="submit" class="dropdown-item text-danger py-2 border-0 bg-transparent text-start w-100">
        <i class="fas fa-sign-out-alt me-2"></i> Logout
    </button>
</form>
                </li>
            </ul>
        </div>

        @else
           <li class="nav-item">
<a href="{{ url('/login') }}" class="nav-link">Log in</a></li>
            
        @endauth

    </div>
</div>
           
    </nav>

    @yield('content')

    <footer class="footer py-5">
        <div class="container text-center">
            <h2 class="fw-800 mb-2" style="color: var(--zomato-dark); font-size: 2.5rem; letter-spacing: -1px;">Sip N Bite</h2>
            <p class="text-muted fs-5 mb-4">Quality food at your doorstep</p>
            
            <div class="d-flex justify-content-center gap-4 mb-4 fs-5">
                <a href="#" class="text-dark opacity-75"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-dark opacity-75"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-dark opacity-75"><i class="fab fa-facebook"></i></a>
            </div>

            <hr class="my-4 mx-auto w-25" style="opacity: 0.1;">
            <p class="text-muted small mb-0">2026-2030 © Sip N Bite™. All rights reserved.</p>
        </div>
    </footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('js')
</body>
</html>

