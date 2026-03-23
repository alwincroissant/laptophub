<x-mail::message>
# Order Status Updated - #{{ $order->order_id }}

Hi {{ $order->user->full_name }},

Your order status has been updated to: **{{ $order->status->status_name }}**.

@if($note)
**Note:** {{ $note }}
@endif

---

## Order Summary

@php $subtotal = 0; @endphp
@foreach($order->items as $item)
* **{{ $item->product->name }}** (x{{ $item->quantity }})  — ₱{{ number_format($item->unit_price * $item->quantity, 2) }}
@php $subtotal += ($item->unit_price * $item->quantity); @endphp
@endforeach

@php
  $shipping = (float) ($order->shipping_fee ?? 0);
  $taxRateSetting = (float) ($order->tax_rate ?? 0);
  $taxAmount = $subtotal > 0 ? ($subtotal * ($taxRateSetting / 100)) : 0;
  $total = $subtotal + $shipping + $taxAmount;
@endphp

**Subtotal:** ₱{{ number_format($subtotal, 2) }}  
**Shipping:** ₱{{ number_format($shipping, 2) }}  
**Tax ({{ $taxRateSetting ?? 0 }}%):** ₱{{ number_format($taxAmount ?? 0, 2) }}  
**Total:** ₱{{ number_format($total, 2) }}  

---

**Shipping Address:**  
{{ $order->shipping_address }}

**Payment Method:**  
{{ $order->paymentMethod->method_name ?? 'N/A' }}

<x-mail::button :url="route('customer.orders.show', $order->order_id)">
View Order Details
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
