@extends('layouts.base')

@section('hide_footer', '1')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    @yield('admin_styles')
@endpush

@section('content')
@php($activeNav = trim($__env->yieldContent('active_nav', '')))

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="wordmark">LaptopHub <span class="badge-admin">Admin</span></div>
        <div class="mt-1" style="font-size:.75rem;color:rgba(255,255,255,.4)">Management Console</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ $activeNav === 'dashboard' ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>

        <div class="nav-section-label">Catalog</div>
        <a href="{{ route('admin.product.index') }}" class="nav-link {{ $activeNav === 'product' ? 'active' : '' }}"><i class="bi bi-laptop"></i> Products</a>
        <a href="{{ route('admin.category.index') }}" class="nav-link {{ $activeNav === 'category' ? 'active' : '' }}"><i class="bi bi-tags"></i> Categories</a>
        <a href="{{ route('admin.brand.index') }}" class="nav-link {{ $activeNav === 'brand' ? 'active' : '' }}"><i class="bi bi-award"></i> Brands</a>

        <div class="nav-section-label">Commerce</div>
        <a href="#" class="nav-link"><i class="bi bi-bag-check"></i> Orders</a>
        <a href="#" class="nav-link"><i class="bi bi-cart3"></i> Carts</a>
        <a href="#" class="nav-link"><i class="bi bi-star-half"></i> Reviews</a>

        <div class="nav-section-label">Operations</div>
        <a href="#" class="nav-link"><i class="bi bi-box-seam"></i> Inventory</a>
        <a href="#" class="nav-link"><i class="bi bi-truck"></i> Suppliers</a>
        <a href="#" class="nav-link"><i class="bi bi-arrow-repeat"></i> Restock Log</a>

        <div class="nav-section-label">Users</div>
        <a href="#" class="nav-link"><i class="bi bi-people"></i> All Users</a>
        <a href="#" class="nav-link"><i class="bi bi-shield-lock"></i> Roles</a>

        <div class="nav-section-label">System</div>
        <a href="#" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
    </nav>

    <div class="sidebar-footer d-flex align-items-center gap-2">
        <div class="avatar">AD</div>
        <div>
            <div style="color:#fff;font-weight:500;font-size:.8rem">Admin User</div>
            <div>admin@laptophub.ph</div>
        </div>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div>
            <h1>@yield('page_title')</h1>
            <p class="sub">@yield('page_subtitle')</p>
        </div>
        <div class="d-flex gap-2">
            @yield('topbar_actions')
        </div>
    </div>

    <div class="content">
        @yield('admin_content')
    </div>
</div>
@endsection
