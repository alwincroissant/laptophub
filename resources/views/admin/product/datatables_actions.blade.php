<div class="d-flex gap-2">
    <a href="{{ route('admin.product.show', $product->product_id) }}" class="btn btn-sm btn-outline-secondary" title="View" aria-label="View">
        <i class="bi bi-eye"></i>
    </a>
    @if ($product->deleted_at)
        <form method="POST" action="{{ route('admin.product.restore', $product->product_id) }}" onsubmit="return confirm('Recover this product?')">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-outline-success" title="Recover" aria-label="Recover">
                <i class="bi bi-arrow-counterclockwise"></i>
            </button>
        </form>
    @else
        <a href="{{ route('admin.product.edit', $product) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
            <i class="bi bi-pencil-square"></i>
        </a>
        <form method="POST" action="{{ route('admin.product.destroy', $product) }}" onsubmit="return confirm('Soft delete this product?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-warning" title="Soft Delete" aria-label="Soft Delete">
                <i class="bi bi-archive"></i>
            </button>
        </form>
    @endif
</div>
