@extends('layouts.base')

@section('hide_footer', '1')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
    <link href="{{ asset('css/admin-product-index.css') }}" rel="stylesheet"/>
    @yield('admin_styles')
@endpush

@section('content')
@php
    $activeNav = trim((string) $__env->yieldContent('active_nav', ''));
@endphp
@php
    $adminUser = auth()->user();
    $adminName = trim((string) ($adminUser->full_name ?? 'Admin User'));
    $adminEmail = trim((string) ($adminUser->email ?? ''));
    $nameParts = preg_split('/\s+/', $adminName, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $initials = '';
    foreach ($nameParts as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
        if (strlen($initials) >= 2) {
            break;
        }
    }
    if ($initials === '') {
        $initials = 'AD';
    }
@endphp

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="wordmark">LaptopHub <span class="badge-admin">Admin</span></div>
        <div class="mt-1" style="font-size:.75rem;color:rgba(255,255,255,.4)">Management Console</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ (($activeNav ?? '') === 'dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>

        <div class="nav-section-label">Catalog</div>
        <a href="{{ route('admin.product.index') }}" class="nav-link {{ (($activeNav ?? '') === 'product') ? 'active' : '' }}"><i class="bi bi-laptop"></i> Products</a>
        <a href="{{ route('admin.category.index') }}" class="nav-link {{ (($activeNav ?? '') === 'category') ? 'active' : '' }}"><i class="bi bi-tags"></i> Categories</a>
        <a href="{{ route('admin.brand.index') }}" class="nav-link {{ (($activeNav ?? '') === 'brand') ? 'active' : '' }}"><i class="bi bi-award"></i> Brands</a>

        <div class="nav-section-label">Commerce</div>
        <a href="{{ route('admin.order.index') }}" class="nav-link {{ (($activeNav ?? '') === 'order') ? 'active' : '' }}"><i class="bi bi-bag-check"></i> Orders</a>
        <a href="{{ route('admin.review.index') }}" class="nav-link {{ (($activeNav ?? '') === 'review') ? 'active' : '' }}"><i class="bi bi-star-half"></i> Reviews</a>

        <div class="nav-section-label">Operations</div>
        <a href="{{ route('admin.inventory.index') }}" class="nav-link {{ (($activeNav ?? '') === 'inventory') ? 'active' : '' }}"><i class="bi bi-box-seam"></i> Inventory</a>
        <a href="{{ route('admin.supplier.index') }}" class="nav-link {{ (($activeNav ?? '') === 'supplier') ? 'active' : '' }}"><i class="bi bi-truck"></i> Suppliers</a>
        <a href="#" class="nav-link"><i class="bi bi-arrow-repeat"></i> Restock Log</a>

        <div class="nav-section-label">Users</div>
        <a href="{{ route('admin.user.index') }}" class="nav-link {{ (($activeNav ?? '') === 'user') ? 'active' : '' }}"><i class="bi bi-people"></i> All Users</a>
        <a href="{{ route('admin.role.index') }}" class="nav-link {{ (($activeNav ?? '') === 'role') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i> Roles</a>

        <div class="nav-section-label">System</div>
        <a href="#" class="nav-link"><i class="bi bi-gear"></i> Settings</a>
    </nav>

    <div class="sidebar-footer">
        <div class="account-menu sidebar-account-menu">
            <button type="button" class="sidebar-account-toggle" aria-label="Account menu">
                <div class="avatar">{{ $initials }}</div>
                <div class="sidebar-account-text">
                    <div class="sidebar-account-name">{{ $adminName }}</div>
                    <div class="sidebar-account-email" title="{{ $adminEmail }}">{{ $adminEmail !== '' ? $adminEmail : 'No email available' }}</div>
                </div>
                <i class="bi bi-chevron-up sidebar-account-chevron"></i>
            </button>
            <div class="account-dropdown sidebar-account-dropdown">
                <a href="{{ route('admin.account.profile') }}" class="account-link"><i class="bi bi-person me-2"></i>My Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="account-signout-form">
                    @csrf
                    <button type="submit" class="account-signout"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                </form>
            </div>
        </div>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div>
            <h1>@yield('page_title')</h1>
            <p class="sub">@yield('page_subtitle')</p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <a href="{{ route('customer.shop.index') }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener noreferrer">
                <i class="bi bi-box-arrow-up-right me-1"></i>View Site
            </a>
            @yield('topbar_actions')
        </div>
    </div>

    <div class="content">
        @yield('admin_content')
    </div>
</div>
@endsection
