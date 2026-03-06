@extends('layouts.admin')

@section('title', 'LaptopHub - Create Supplier')
@section('active_nav', 'supplier')
@section('page_title', 'Create Supplier')
@section('page_subtitle', 'Add a new supplier profile')

@section('admin_styles')
    <link href="{{ asset('css/admin-product-form.css') }}" rel="stylesheet"/>
@endsection

@section('topbar_actions')
    <a href="{{ route('admin.supplier.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Suppliers
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
            <h5>Supplier Information</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.supplier.store') }}" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label" for="name">Supplier Name</label>
                    <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="contact_name">Contact Person</label>
                    <input id="contact_name" name="contact_name" type="text" class="form-control" value="{{ old('contact_name') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="contact_email">Contact Email</label>
                    <input id="contact_email" name="contact_email" type="email" class="form-control" value="{{ old('contact_email') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="contact_phone">Contact Phone</label>
                    <input id="contact_phone" name="contact_phone" type="text" class="form-control" value="{{ old('contact_phone') }}">
                </div>

                <div class="col-12">
                    <label class="form-label" for="address">Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label" for="product_ids">Supplied Products</label>
                    <select id="product_ids" name="product_ids[]" class="form-select" multiple size="10">
                        @foreach($products as $product)
                            <option value="{{ $product->product_id }}" @selected(collect(old('product_ids', []))->contains($product->product_id))>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple products.</small>
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', true))>
                        <label class="form-check-label" for="is_active">Set as active supplier</label>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Save Supplier</button>
                    <a href="{{ route('admin.supplier.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
