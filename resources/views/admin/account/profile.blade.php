@extends('layouts.admin')

@section('title', 'LaptopHub - Admin Profile')
@section('active_nav', 'user')
@section('page_title', 'My Profile')
@section('page_subtitle', 'Manage your admin account details and security')

@section('admin_content')
    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-12 col-lg-7">
            <div class="table-card">
                <div class="card-header">
                    <h5>Account Information</h5>
                </div>
                <div class="p-3 p-md-4">
                    <form action="{{ route('admin.account.profile.update') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input id="full_name" name="full_name" type="text" class="form-control" value="{{ old('full_name', $user->full_name) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input id="contact_number" name="contact_number" type="text" class="form-control" value="{{ old('contact_number', $user->contact_number) }}">
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-dark btn-sm">Save Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="table-card mb-3">
                <div class="card-header">
                    <h5>Security</h5>
                </div>
                <div class="p-3 p-md-4">
                    <form action="{{ route('admin.account.password.update') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-12">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input id="current_password" name="current_password" type="password" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label for="new_password" class="form-label">New Password</label>
                            <input id="new_password" name="new_password" type="password" class="form-control" minlength="8" required>
                        </div>
                        <div class="col-12">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input id="new_password_confirmation" name="new_password_confirmation" type="password" class="form-control" minlength="8" required>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-outline-dark btn-sm">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-card">
                <div class="card-header">
                    <h5>Account Summary</h5>
                </div>
                <div class="p-3 p-md-4" style="font-size:.85rem">
                    <div class="mb-2"><strong>Role:</strong> {{ $user->role->role_name ?? 'N/A' }}</div>
                    <div class="mb-2"><strong>Status:</strong> {{ $user->is_active ? 'Active' : 'Inactive' }}</div>
                    <div><strong>User ID:</strong> #{{ $user->user_id }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
