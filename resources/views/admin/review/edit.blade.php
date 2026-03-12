@extends('layouts.admin')

@section('title', 'LaptopHub — Edit Review')
@section('active_nav', 'review')
@section('page_title', 'Edit Review')
@section('page_subtitle', 'Update review details')

@section('topbar_actions')
    <a href="{{ route('admin.review.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to Reviews
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
            <h5>Review Information</h5>
            <span class="text-muted" style="font-size:.78rem">
                By {{ $review->user->full_name ?? 'Unknown' }} for {{ $review->product->name ?? 'Unknown product' }}
            </span>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.review.update', $review) }}" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-4">
                    <label class="form-label" for="rating">Rating</label>
                    <select id="rating" name="rating" class="form-select" required>
                        <option value="5" @selected(old('rating', $review->rating) == 5)>5 - Excellent</option>
                        <option value="4" @selected(old('rating', $review->rating) == 4)>4 - Very Good</option>
                        <option value="3" @selected(old('rating', $review->rating) == 3)>3 - Good</option>
                        <option value="2" @selected(old('rating', $review->rating) == 2)>2 - Fair</option>
                        <option value="1" @selected(old('rating', $review->rating) == 1)>1 - Poor</option>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label" for="title">Title <span class="text-muted" style="font-weight:400">(optional)</span></label>
                    <input id="title" name="title" type="text" class="form-control" maxlength="150" value="{{ old('title', $review->title) }}" placeholder="Short headline for the review">
                </div>

                <div class="col-12">
                    <label class="form-label" for="body">Review Text <span class="text-muted" style="font-weight:400">(optional)</span></label>
                    <textarea id="body" name="body" class="form-control" rows="4" maxlength="1500" placeholder="Review body text">{{ old('body', $review->body) }}</textarea>
                </div>

                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_visible" name="is_visible" value="1" @checked(old('is_visible', $review->is_visible))>
                        <label class="form-check-label" for="is_visible">Visible to customers</label>
                    </div>
                </div>

                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-dark">Update Review</button>
                    <a href="{{ route('admin.review.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
