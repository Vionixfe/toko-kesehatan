 <!-- [ Sidebar Menu ] start -->
<nav class="pc-sidebar">
  <div class="navbar-wrapper">
    <div class="m-header">
      <span class="b-brand text-primary" style="font-size: 1.5rem; font-weight: 600;">
        Sehat Medika
      </span>
    </div>
    <div class="navbar-content">
      <ul class="pc-navbar">
        <li class="pc-item {{ request()->routeIs('panel.dashboard') ? 'active' : '' }}">
          <a href="{{ route('panel.dashboard') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
            <span class="pc-mtext">Dashboard</span>
          </a>
        </li>

        <li class="pc-item {{ request()->routeIs('categories.index') ? 'active' : '' }}">
          <a href="{{ route('categories.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-zodiac-aquarius"></i></span>
            <span class="pc-mtext">Category</span>
          </a>
        </li>

        <li class="pc-item {{ request()->routeIs('products.index') ? 'active' : '' }}">
          <a href="{{ route('products.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-color-swatch"></i></span>
            <span class="pc-mtext">Product</span>
          </a>
        </li>
        <li class="pc-item {{ request()->routeIs('customers.index') ? 'active' : '' }}">
          <a href="{{ route('customers.index') }}" class="pc-link">
            <span class="pc-micon"><i class="ti ti-user"></i></span>
            <span class="pc-mtext">Customer</span>
          </a>
        </li>

        <li class="pc-item {{ request()->routeIs('reviews.index') ? 'active' : '' }}">
            <a href="{{ route('reviews.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-star"></i></span>
                <span class="pc-mtext">Review</span>
            </a>
        </li>
        <li class="pc-item {{ request()->routeIs('orders.index') ? 'active' : '' }}">
            <a href="{{ route('orders.index') }}" class="pc-link">
                <span class="pc-micon"><i class="ti ti-truck"></i></span>
                <span class="pc-mtext">Shipping Order</span>
            </a>
        </li>
    </div>
  </div>
</nav>
<!-- [ Sidebar Menu ] end -->
