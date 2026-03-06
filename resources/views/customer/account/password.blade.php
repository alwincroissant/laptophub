@extends('layouts.base')

@section('title', 'Change Password - LaptopHub')

@section('content')
<div class="container py-5" style="max-width: 760px; margin-top: 72px;">
  <h2 class="mb-3">Change Password</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form action="{{ route('customer.account.password.update') }}" method="post" class="card border-0 shadow-sm">
    @csrf
    <div class="card-body p-4">
      <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="new_password_confirmation" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-danger">Update Password</button>
      <a href="{{ route('customer.shop.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>
  </form>
</div>
@endsection
