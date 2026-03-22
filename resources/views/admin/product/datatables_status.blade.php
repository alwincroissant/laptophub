@php
    $isLowStock = ! $product->is_archived && $product->stock_qty <= $product->low_stock_threshold;
@endphp

@if ($product->deleted_at)
    <span class="status-badge badge-archived">Trashed</span>
@elseif ($product->is_archived)
    <span class="status-badge badge-archived">Archived</span>
@elseif ($product->stock_qty == 0)
    <span class="status-badge badge-out">Out of Stock</span>
@elseif ($isLowStock)
    <span class="status-badge badge-low">Low Stock</span>
@else
    <span class="status-badge badge-active">Active</span>
@endif
