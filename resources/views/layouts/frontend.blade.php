<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sehat Medika - Solusi Alat Kesehatan')</title>

    <link rel="icon" href="{{ asset('backend/assets/images/logo.png') }}" type="image/x-icon">

    <!-- Google Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,700|Poppins:400,500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <!-- Custom CSS -->
    <style>
        body { font-family: 'Roboto', sans-serif; background-color: #f8f9fa; }
        .navbar-brand { font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 26px; color: #005dff; }
        .navbar .nav-link { font-weight: 500; color: #333; }
        .navbar .nav-link.active { font-weight: 700; color: #005dff; }
        .btn-primary { background-color: #005dff; border-color: #005dff; }
        .product-card { transition: all 0.3s ease; border: 1px solid #e9ecef; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.08); }
        .section-title h2 { font-weight: bold; color: #333; }
        .footer { background-color: #343a40; color: white; }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Header / Navbar -->
    <header class="bg-white shadow-sm sticky-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('backend/assets/images/logo.png') }}" alt="Sehat Medika Logo" style="height: 40px; width: auto; margin-right: 10px;">
                    Sehat Medika
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            {{-- Menggunakan wildcard agar aktif di semua halaman produk --}}
                            <a class="nav-link {{ request()->routeIs('shop.*') ? 'active' : '' }}" href="{{ route('shop.index') }}">Products</a>
                        </li>
                        @auth
                            @if(Auth::user()->role === 'customer')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('my-orders.index') ? 'active' : '' }}" href="{{ route('my-orders.index') }}">Orders</a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="{{ route('cart.index') }}" class="nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}">
                                <i class="fas fa-shopping-cart"></i> Cart
                                @auth
                                    @if($cartCount > 0)
                                        <span class="badge bg-danger">{{ $cartCount }}</span>
                                    @endif
                                @endauth
                            </a>
                        </li>
                        @guest
                            <li class="nav-item ms-3">
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm ms-2">Register</a>
                            </li>
                        @else
                            <li class="nav-item dropdown ms-3">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->nama }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(Auth::user()->role === 'admin')
                                        <li><a class="dropdown-item" href="{{ route('panel.dashboard') }}">Admin Dashboard</a></li>
                                    @else
                                        <li><a href="{{ route('profile.edit') }}" class="dropdown-item"><i class="ti ti-user"></i><span>Profile</span></a></li>

                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Konten Utama -->
    <main class="py-4 py-md-5">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Sehat Medika. All Rights Reserved.</p>
        </div>
    </footer>

    <style>
        main {
            min-height: calc(100vh - 56px - 85px);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
