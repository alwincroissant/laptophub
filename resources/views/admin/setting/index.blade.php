@extends('layouts.admin')

@section('title', 'LaptopHub — Settings')
@section('active_nav', 'settings')
@section('page_title', 'Application Settings')
@section('page_subtitle', 'Manage global store parameters.')

@section('admin_content')
    <div class="row">
        <div class="col-lg-6">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">Tax & Shipping Settings</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold" style="font-size: .85rem;">Global Tax Rate (%)</label>
                            <input type="number" 
                                   name="tax_rate" 
                                   class="form-control" 
                                   step="0.01" 
                                   min="0" 
                                   max="100" 
                                   value="{{ old('tax_rate', $settings['tax_rate'] ?? 0) }}" 
                                   required>
                            <div class="form-text">Percentage applied to order subtotals (e.g. 12 for 12%).</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="font-size: .85rem;">Standard Shipping Fee (₱)</label>
                            <input type="number" 
                                   name="shipping_fee" 
                                   class="form-control" 
                                   step="0.01" 
                                   min="0" 
                                   value="{{ old('shipping_fee', $settings['shipping_fee'] ?? 0) }}" 
                                   required>
                            <div class="form-text">Flat rate shipping fee charged to customers per order.</div>
                        </div>

                        <button type="submit" class="btn btn-dark w-100">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
