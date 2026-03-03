@extends('layouts.admin')

@section('title', 'LaptopHub — Edit Category')
@section('active_nav', 'category')
@section('page_title', 'Edit Category')
@section('page_subtitle', 'Update category details.')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-form.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.category.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Categories
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
                <h5>Category Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.category.update', $category) }}" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-12">
                        <label class="form-label" for="name">Name</label>
                        <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $category->name) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $category->is_active))>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>

                    <div class="col-12 d-flex gap-2">
                        <button type="submit" class="btn btn-dark">Update</button>
                        <a href="{{ route('admin.category.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
@endsection
