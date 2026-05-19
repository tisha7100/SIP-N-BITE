<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sip N Bite</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1d4ed8;
            --bg-light: #f0f7ff;
            --sidebar-width: 260px;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            overflow-x: hidden;
        }
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            z-index: 1000;
            transition: all 0.3s;
        }
        #content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }
        .nav-link {
            color: #4b5563;
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 1rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            font-weight: 500;
        }
        .nav-link:hover, .nav-link.active {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            color: var(--primary-color);
            font-weight: 600;
        }
        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        .logo {
            padding: 2rem 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .top-nav {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
            margin-left: var(--sidebar-width);
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-success, .badge-delivered { background: #dbeafe; color: #1e40af; }
        .badge-danger, .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-under-preparation { background: #eff6ff; color: #1d4ed8; }
        .badge-ready { background: #fdf2f8; color: #9d174d; }
        .badge-out-for-delivery { background: #ede9fe; color: #5b21b6; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        /* Blue Pagination */
        .pagination { gap: 6px; flex-wrap: wrap; }
        .page-item .page-link { border-radius: 50px !important; border: 1.5px solid #dbeafe; color: #2563eb; font-weight: 600; padding: 0.4rem 0.9rem; transition: all 0.25s; background: #fff; }
        .page-item .page-link:hover { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; border-color: #2563eb; box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
        .page-item.active .page-link { background: linear-gradient(135deg, #2563eb, #1d4ed8); border-color: #2563eb; color: #fff; }
        .page-item.disabled .page-link { color: #9ca3af; background: #f9fafb; border-color: #e5e7eb; }
    </style>
    @stack('css')
</head>
<body>
    <div id="sidebar">
        <div class="logo">
            <i class="fas fa-utensils me-2"></i> Sip N Bite
        </div>
        <nav class="mt-4">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i> Categories
            </a>
            <a href="{{ route('admin.menu.index') }}" class="nav-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                <i class="fas fa-hamburger"></i> Manage Menu
            </a>
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Customers
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i> Orders
            </a>
            <a href="{{ route('admin.tables.index') }}" class="nav-link {{ request()->routeIs('admin.tables.*') ? 'active' : '' }}">
                <i class="fas fa-chair"></i> Table Bookings
            </a>
           
        </nav>
    </div>

    <div class="top-nav">
        <div class="dropdown">
            <button class="btn dropdown-toggle d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name=Admin" class="rounded-circle me-2" width="32"
                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name=Admin';">
                <span>
                    {{ Auth::guard('admin')->check() ? Auth::guard('admin')->user()->name : 'Admin' }}
                </span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                <!-- <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog me-2"></i> Profile</a></li> -->
                <!-- <li><hr class="dropdown-divider"></li> -->
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button class="dropdown-item text-danger" type="submit">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul> 
        </div>
    </div>

    <div id="content">

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
                <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('js')
</body>
</html>
