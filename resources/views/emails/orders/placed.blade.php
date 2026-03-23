<x-mail::message>
# Order Confirmation - #{{ $order->order_id }}


@php
  $subtotal = $order->items->sum(function($item) {
      return $item->unit_price * $item->quantity;
  });
  $shipping = $order->shipping_fee ?? 0;
  $taxRate = $order->tax_rate ?? 0;
  $taxAmount = $subtotal * ($taxRate / 100);
  $total = $subtotal + $shipping + $taxAmount;
@endphp

**Subtotal:** ₱{{ number_format($subtotal, 2) }}  
**Shipping:** ₱{{ number_format($shipping, 2) }}  
**Tax ({{ $taxRate }}%):** ₱{{ number_format($taxAmount, 2) }}  
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
