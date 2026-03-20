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
        <form action="{{ route('admin.account.profile.update') }}" method="POST" enctype="multipart/form-data" class="col-12 row g-3 m-0 p-0">
            @csrf
            
            <div class="col-12 col-lg-7">
                <div class="table-card h-100">
                    <div class="card-header">
                        <h5>Account Information</h5>
                    </div>
                    <div class="p-3 p-md-4">
                        <div class="row g-3">
                            <div class="col-12 mt-2 d-flex align-items-center gap-3">
                                <div style="width:64px;height:64px;border-radius:50%;overflow:hidden;background:#f8f9fa;border:1px solid #dee2e6;display:flex;align-items:center;justify-content:center;">
                                    @if($user->profile_image_url)
                                        <img src="{{ $user->profile_image_url }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        <i class="bi bi-person text-secondary" style="font-size:2rem;"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <label for="profile_image" class="form-label mb-1" style="font-size:.8rem;font-weight:600;">Profile Photo</label>
                                    <input id="profile_image" name="profile_image" type="file" class="form-control form-control-sm @error('profile_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
                                    @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input id="full_name" name="full_name" type="text" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $user->full_name) }}" required>
                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="contact_number" class="form-label">Contact Number</label>
                                <input id="contact_number" name="contact_number" type="text" class="form-control @error('contact_number') is-invalid @enderror" value="{{ old('contact_number', $user->contact_number) }}">
                                @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="table-card h-100">
                    <div class="card-header">
                        <h5>Security</h5>
                    </div>
                    <div class="p-3 p-md-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input id="current_password" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text text-muted" style="font-size: .75rem;">Required only when updating password</div>
                            </div>
                            <div class="col-12">
                                <label for="new_password" class="form-label">New Password</label>
                                <input id="new_password" name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror">
                                @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input id="new_password_confirmation" name="new_password_confirmation" type="password" class="form-control">
                            </div>
                            <div class="col-12 mt-4 d-flex justify-content-end">
                                <button type="submit" class="btn btn-dark w-100 py-2" style="font-weight: 600;">Save Profile Updates</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

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
