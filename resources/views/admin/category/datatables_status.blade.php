@if ($category->deleted_at)
    <span class="status-badge badge-archived">Trashed</span>
@elseif ($category->is_active)
    <span class="status-badge badge-active">Active</span>
@else
    <span class="status-badge badge-low">Inactive</span>
@endif
