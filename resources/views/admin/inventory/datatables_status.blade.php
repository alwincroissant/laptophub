@php
    $isLowStock = ! $item->is_archived && (int) $item->stock_qty <= (int) $item->low_stock_threshold && (int) $item->stock_qty > 0;
@endphp

@if($item->is_archived)
    <span class="status-badge badge-archived">Archived</span>
@elseif((int) $item->stock_qty === 0)
    <span class="status-badge badge-out">Out of Stock</span>
@elseif($isLowStock)
    <span class="status-badge badge-low">Low Stock</span>
@else
    <span class="status-badge badge-active">In Stock</span>
@endif
