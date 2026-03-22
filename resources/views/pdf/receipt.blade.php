<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt - Order #{{ $order->order_id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #c0392b; margin-bottom: 20px; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #c0392b; font-size: 24px; }
        .details { margin-bottom: 30px; }
        .details th { text-align: left; padding-right: 20px; color: #555; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th, .table td { border-bottom: 1px solid #ddd; padding: 10px; text-align: left; }
        .table th { background-color: #f5f1ea; color: #333; }
        .text-right { text-align: right !important; }
        .totals { width: 50%; float: right; }
        .totals th, .totals td { padding: 5px 10px; }
        .footer { clear: both; margin-top: 50px; text-align: center; font-size: 12px; color: #777; border-top: 1px solid #ddd; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LaptopHub</h1>
        <p>Official Order Receipt</p>
    </div>

    <table class="details">
        <tr>
            <th>Order ID:</th>
            <td>#{{ $order->order_id }}</td>
        </tr>
        <tr>
            <th>Date Placed:</th>
            <td>{{ \Carbon\Carbon::parse($order->placed_at)->format('F j, Y, g:i a') }}</td>
        </tr>
        <tr>
            <th>Customer Name:</th>
            <td>{{ $order->user->full_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Email:</th>
            <td>{{ $order->user->email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Shipping Address:</th>
            <td>{{ $order->shipping_address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Order Status:</th>
            <td>{{ $order->status->status_name ?? 'Pending' }}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Item Description</th>
                <th>Qty</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
            @foreach($order->items as $item)
                @php
                    $lineTotal = $item->quantity * $item->unit_price;
                    $subtotal += $lineTotal;
                @endphp
                <tr>
                    <td>{{ $item->product ? $item->product->name : 'Unknown Product' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">P{{ number_format((float)$item->unit_price, 2) }}</td>
                    <td class="text-right">P{{ number_format((float)$lineTotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <th class="text-right">Subtotal:</th>
            <td class="text-right">P{{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <th class="text-right">Shipping:</th>
            @php 
                $settings = \App\Models\Setting::pluck('value', 'key');
                $shippingFeeSetting = isset($settings['shipping_fee']) ? (float) $settings['shipping_fee'] : 0;
                $taxRateSetting = isset($settings['tax_rate']) ? (float) $settings['tax_rate'] : 0;
                $shipping = $subtotal > 0 ? $shippingFeeSetting : 0;
                $taxAmount = $subtotal > 0 ? ($subtotal * ($taxRateSetting / 100)) : 0;
                $total = $subtotal + $shipping + $taxAmount;
            @endphp
            <td class="text-right">P{{ number_format($shipping, 2) }}</td>
        </tr>
        <tr>
            <th class="text-right">Tax ({{ $taxRateSetting ?? 0 }}%):</th>
            <td class="text-right">P{{ number_format($taxAmount ?? 0, 2) }}</td>
        </tr>
        <tr>
            <th class="text-right" style="font-size: 18px;">Total:</th>
            <td class="text-right" style="font-size: 18px; font-weight: bold; color: #c0392b;">P{{ number_format($total, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Thank you for shopping with LaptopHub!</p>
        <p>If you have any questions about your order, please contact our support team.</p>
    </div>
</body>
</html>
