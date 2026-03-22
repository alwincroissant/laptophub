<div class="d-flex gap-2">
    <a href="{{ route('admin.review.edit', $review->review_id) }}" class="btn btn-sm btn-outline-primary" title="Edit review">
        <i class="bi bi-pencil"></i>
    </a>
    <form action="{{ route('admin.review.toggle-visibility', $review->review_id) }}" method="POST" style="margin:0">
        @csrf
        @method('PATCH')
        <button type="submit" class="btn btn-sm {{ $review->is_visible ? 'btn-outline-secondary' : 'btn-outline-success' }}" title="{{ $review->is_visible ? 'Hide review' : 'Show review' }}">
            <i class="bi {{ $review->is_visible ? 'bi-eye-slash' : 'bi-eye' }}"></i>
        </button>
    </form>

    <form action="{{ route('admin.review.destroy', $review->review_id) }}" method="POST" style="margin:0" onsubmit="return confirm('Delete this review permanently?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete review">
            <i class="bi bi-trash"></i>
        </button>
    </form>
</div>
