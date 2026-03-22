@if($review->orderItem)
    <span class="mono-id">#{{ $review->orderItem->order_id }}</span>
@else
    <span class="text-muted">N/A</span>
@endif
