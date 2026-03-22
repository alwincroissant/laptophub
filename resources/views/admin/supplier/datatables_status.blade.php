@if ($supplier->deleted_at)
    <span class="status-badge badge-archived">Trashed</span>
@elseif ($supplier->is_active)
    <span class="status-badge badge-active">Active</span>
@else
    <span class="status-badge badge-low">Inactive</span>
@endif
