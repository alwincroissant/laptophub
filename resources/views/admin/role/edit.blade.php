@extends('layouts.admin')

@section('title', 'LaptopHub - Edit Role')
@section('active_nav', 'role')
@section('page_title', 'Edit Role')
@section('page_subtitle', 'Update role label used for access grouping')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-form.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.role.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Roles
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

    @if ($isProtected)
        <div class="alert alert-warning">This is a protected system role and cannot be renamed.</div>
    @endif

    <div class="form-card">
        <div class="card-header">
            <h5>Role Information</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.role.update', $role) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-12">
                    <label class="form-label" for="role_name">Role Name</label>
                    <input id="role_name" name="role_name" type="text" class="form-control" value="{{ old('role_name', $role->role_name) }}" maxlength="30" required {{ $isProtected ? 'disabled' : '' }}>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark" {{ $isProtected ? 'disabled' : '' }}>Update Role</button>
                    <a href="{{ route('admin.role.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
