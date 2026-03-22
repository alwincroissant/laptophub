<div class="d-flex gap-2 justify-content-center">
    @if ($brand->deleted_at)
        <form method="POST" action="{{ route('admin.brand.restore', $brand->brand_id) }}" onsubmit="return confirm('Recover this brand?')">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-outline-success" title="Recover" aria-label="Recover">
                <i class="bi bi-arrow-counterclockwise"></i>
            </button>
        </form>
    @else
        <a href="{{ route('admin.brand.edit', $brand) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
            <i class="bi bi-pencil-square"></i>
        </a>
        <form method="POST" action="{{ route('admin.brand.destroy', $brand) }}" onsubmit="return confirm('Soft delete this brand?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-warning" title="Soft Delete" aria-label="Soft Delete">
                <i class="bi bi-archive"></i>
            </button>
        </form>
    @endif
    <form method="POST" action="{{ route('admin.brand.force-destroy', $brand->brand_id) }}" onsubmit="return confirm('Delete permanently?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" aria-label="Delete">
            <i class="bi bi-trash3"></i>
        </button>
    </form>
</div>
