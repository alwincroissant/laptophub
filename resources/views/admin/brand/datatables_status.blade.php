@if ($brand->deleted_at)
    <span class="status-badge badge-archived">Trashed</span>
@elseif ($brand->is_active)
    <span class="status-badge badge-active">Active</span>
@else
    <span class="status-badge badge-low">Inactive</span>
@endif
