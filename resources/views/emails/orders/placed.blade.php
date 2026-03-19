<x-mail::message>
# Order Confirmation - #{{ $order->order_id }}

Hi {{ $order->user->full_name }},

Thank you for shopping with **LaptopHub**! We've received your order and are getting it ready for shipment.

## Order Summary

@php $subtotal = 0; @endphp
@foreach($order->items as $item)
* **{{ $item->product->name }}** (x{{ $item->quantity }})  — ₱{{ number_format($item->unit_price * $item->quantity, 2) }}
@php $subtotal += ($item->unit_price * $item->quantity); @endphp
@endforeach

**Subtotal:** ₱{{ number_format($subtotal, 2) }}  
**Shipping:** ₱200.00  
**Total:** ₱{{ number_format($subtotal + 200, 2) }}  

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
