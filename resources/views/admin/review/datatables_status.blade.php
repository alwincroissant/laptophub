@if($review->is_visible)
    <span class="status-badge badge-delivered">Visible</span>
@else
    <span class="status-badge badge-cancelled">Hidden</span>
@endif
