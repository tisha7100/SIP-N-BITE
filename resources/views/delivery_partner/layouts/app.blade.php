<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dispatch - Sip N Bite</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>

        :root {
            --primary-color: #16a34a;
            --secondary-color: #15803d;
            --bg-light: #f0fdf4;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden;
        }

        /* SIDEBAR */

        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .logo {
            padding: 2rem 1.5rem;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }

        /* SIDEBAR LINKS */

        .nav-link {
            color: #4b5563;
            padding: 0.9rem 1.5rem;
            margin: 0.45rem 1rem;
            border-radius: 14px;
            display: flex;
            align-items: center;
            font-weight: 500;
            transition: 0.3s ease;
            text-decoration: none;
        }

        .nav-link i {
            margin-right: 0.85rem;
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .nav-link:hover,
        .nav-link.active {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: var(--primary-color);
            font-weight: 600;
            transform: translateX(4px);
        }

        /* TOP NAVBAR */

        .top-nav {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
            margin-left: var(--sidebar-width);
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 80px;
        }

        /* PROFILE BUTTON */

        .profile-btn {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 50px;
            padding: 8px 18px;
            display: flex;
            align-items: center;
            font-weight: 600;
            color: #111827;
            transition: 0.3s ease;
        }

        .profile-btn:hover {
            background: #dcfce7;
        }

        .profile-btn img {
            object-fit: cover;
        }

        /* DROPDOWN */

        .dropdown-menu {
            border-radius: 16px;
            min-width: 220px;
            padding: 10px;
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 12px 16px;
            transition: 0.3s ease;
        }

        .dropdown-item:hover {
            background: #fef2f2;
        }

        /* CONTENT */

        #content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }

        /* CARD */

        .card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            transition: 0.3s ease;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        /* ALERTS */

        .alert {
            border-radius: 14px;
        }

        /* STATUS BADGES */

        .status-active {
            background: #dcfce7;
            color: #166534;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        /* RESPONSIVE */

        @media(max-width: 991px){

            #sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .top-nav,
            #content {
                margin-left: 0;
            }

            #content {
                padding: 1rem;
            }
        }

    </style>

    @stack('css')
</head>

<body>

    <!-- SIDEBAR -->

    <div id="sidebar">

        <div class="logo">
            <i class="fas fa-motorcycle me-2"></i>
            DP Dispatch Hub
        </div>

        <nav class="mt-4">

            <!-- DASHBOARD -->

            <a href="{{ route('delivery-partner.dashboard') }}"
               class="nav-link {{ request()->routeIs('delivery-partner.dashboard') ? 'active' : '' }}">

                <i class="fas fa-chart-line"></i>
                Dashboard

            </a>

            <!-- ALL PARTNERS -->

            <a href="{{ route('delivery-partner.all-partners') }}"
               class="nav-link {{ request()->routeIs('delivery-partner.all-partners') ? 'active' : '' }}">

                <i class="fas fa-users"></i>
                All Delivery Partners

            </a>

        </nav>

    </div>

    <!-- TOP NAVBAR -->

    <div class="top-nav">

        <div class="dropdown">

            <button class="btn profile-btn dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">

                <img src="https://ui-avatars.com/api/?name={{ Auth::guard('delivery_partner')->user()->name }}&background=16a34a&color=ffffff"
                     class="rounded-circle me-2"
                     width="40"
                     height="40">

                <span>
                    {{ Auth::guard('delivery_partner')->user()->name }}
                </span>

            </button>

            <!-- DROPDOWN MENU -->

            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3">

                <li class="dropdown-header text-success fw-bold">
                    Dispatch Hub
                </li>

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>

                    <form action="{{ route('delivery-partner.logout') }}" method="POST">

                        @csrf

                        <button type="submit"
                                class="dropdown-item text-danger fw-semibold">

                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout

                        </button>

                    </form>

                </li>

            </ul>

        </div>

    </div>

    <!-- CONTENT -->

    <div id="content">

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">

                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>

            </div>

        @endif

        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">

                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"></button>

            </div>

        @endif

        @yield('content')

    </div>

    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('js')

</body>
</html>