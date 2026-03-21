@extends('layouts.admin')

@section('title', 'LaptopHub - Create User')
@section('active_nav', 'user')
@section('page_title', 'Create User')
@section('page_subtitle', 'Add a new account and assign role access')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-form.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Users
    </a>
@endsection

@section('admin_content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card">
        <div class="card-header">
            <h5>User Information</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.user.store') }}" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label" for="full_name">Full Name</label>
                    <input id="full_name" name="full_name" type="text" class="form-control" value="{{ old('full_name') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="role_id">Role</label>
                    <select id="role_id" name="role_id" class="form-select" required>
                        <option value="">Select role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->role_id }}" @selected(old('role_id') == $role->role_id)>{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="contact_number">Contact Number</label>
                    <input id="contact_number" name="contact_number" type="text" class="form-control" value="{{ old('contact_number') }}">
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', true))>
                        <label class="form-check-label" for="is_active">Set as active</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label" for="street_address">Default Address (optional)</label>
                    <p class="text-muted small mb-2">If provided, this will be saved to <code>user_addresses</code> as the user's default address.</p>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="address_label">Address Label</label>
                    <input id="address_label" name="address_label" type="text" class="form-control" value="{{ old('address_label') }}" placeholder="Home, Office, Condo">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="recipient_name">Recipient Name</label>
                    <input id="recipient_name" name="recipient_name" type="text" class="form-control" value="{{ old('recipient_name', old('full_name')) }}" placeholder="Full name of receiver">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="address_phone">Address Phone</label>
                    <input id="address_phone" name="address_phone" type="text" class="form-control" value="{{ old('address_phone', old('contact_number')) }}" placeholder="09XX XXX XXXX">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="region">Region / Province</label>
                    <input id="region" name="region" type="text" class="form-control" value="{{ old('region') }}" placeholder="e.g. Metro Manila">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="city">City / Municipality</label>
                    <input id="city" name="city" type="text" class="form-control" value="{{ old('city') }}" placeholder="e.g. Quezon City">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="postal_code">Postal Code</label>
                    <input id="postal_code" name="postal_code" type="text" class="form-control" value="{{ old('postal_code') }}" placeholder="e.g. 1100">
                </div>

                <div class="col-12">
                    <label class="form-label" for="street_address">Street Address</label>
                    <textarea id="street_address" name="street_address" class="form-control" rows="3" placeholder="House/Unit, street, barangay">{{ old('street_address') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Save User</button>
                    <a href="{{ route('admin.user.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
