<div class="d-flex gap-2 justify-content-center">
    @if ($supplier->deleted_at)
        <form method="POST" action="{{ route('admin.supplier.restore', $supplier->supplier_id) }}" onsubmit="return confirm('Recover this supplier?')">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm btn-outline-success" title="Recover" aria-label="Recover">
                <i class="bi bi-arrow-counterclockwise"></i>
            </button>
        </form>
    @else
        <a href="{{ route('admin.supplier.show', $supplier) }}" class="btn btn-sm btn-outline-info" title="View details" aria-label="View details">
            <i class="bi bi-eye"></i>
        </a>
        <a href="{{ route('admin.supplier.edit', $supplier) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
            <i class="bi bi-pencil-square"></i>
        </a>
        <form method="POST" action="{{ route('admin.supplier.destroy', $supplier) }}" onsubmit="return confirm('Soft delete this supplier?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-warning" title="Soft Delete" aria-label="Soft Delete">
                <i class="bi bi-archive"></i>
            </button>
        </form>
    @endif
    <form method="POST" action="{{ route('admin.supplier.force-destroy', $supplier->supplier_id) }}" onsubmit="return confirm('Delete permanently?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" aria-label="Delete">
            <i class="bi bi-trash3"></i>
        </button>
    </form>
</div>
