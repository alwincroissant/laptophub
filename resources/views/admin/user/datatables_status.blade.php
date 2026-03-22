@if ($user->is_active)
    <span class="status-badge badge-active">Active</span>
@else
    <span class="status-badge badge-archived">Inactive</span>
@endif
