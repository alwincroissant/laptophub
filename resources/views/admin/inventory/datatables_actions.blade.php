<div class="d-flex gap-2 justify-content-center">
    <a href="{{ route('admin.inventory.edit', $item->product_id) }}" class="btn btn-sm btn-outline-primary" title="Edit" aria-label="Edit">
        <i class="bi bi-pencil-square"></i>
    </a>
    <form method="POST" action="{{ route('admin.inventory.destroy', $item->product_id) }}" onsubmit="return confirm('Delete this inventory item?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete" aria-label="Delete">
            <i class="bi bi-trash3"></i>
        </button>
    </form>
</div>
