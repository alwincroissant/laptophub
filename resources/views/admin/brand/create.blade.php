@extends('layouts.admin')

@section('title', 'LaptopHub — Create Brand')
@section('active_nav', 'brand')
@section('page_title', 'Create Brand')
@section('page_subtitle', 'Add a new brand.')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-form.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.brand.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Brands
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
                <h5>Brand Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.brand.store') }}" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label class="form-label" for="name">Name</label>
                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', true))>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-dark">Save</button>
                        <a href="{{ route('admin.brand.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
@endsection
