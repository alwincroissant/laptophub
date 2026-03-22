@php
    $statusClass = match (strtolower((string) $order->status_name)) {
        'pending' => 'badge-pending',
        'processing' => 'badge-processing',
        'shipped' => 'badge-shipped',
        'delivered' => 'badge-delivered',
        'cancelled' => 'badge-cancelled',
        default => 'badge-default',
    };
@endphp
<span class="status-badge {{ $statusClass }}">{{ $order->status_name ?? 'Unknown' }}</span>
