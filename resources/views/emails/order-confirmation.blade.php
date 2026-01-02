<x-mail::message>
# Order Confirmation

Thank you for your order, **{{ $order->customer_name }}**! 

We've received your order and we're getting it ready for you. You can track your order status using the Order ID below on our website.

**Order ID for Tracking:** `{{ $order->order_number }}`

<x-mail::button :url="config('app.url') . '/track-order?orderNumber=' . $order->order_number">
Track Your Order
</x-mail::button>

### Order Summary:
**Order Type:** {{ ucfirst($order->type) }}  
**Order Date:** {{ $order->created_at->format('d M Y, H:i') }}

<x-mail::table>
| Item | Qty | Price | Total |
| :--- | :--- | :--- | :--- |
@foreach($order->items as $item)
| {{ $item->name }} | {{ $item->quantity }} | £{{ number_format($item->price, 2) }} | £{{ number_format($item->price * $item->quantity, 2) }} |
@endforeach
</x-mail::table>

**Subtotal:** £{{ number_format($order->subtotal, 2) }}  
**Delivery Fee:** £{{ number_format($order->delivery_fee, 2) }}  
**VAT (20%):** £{{ number_format($order->vat, 2) }}  
**Total Amount:** **£{{ number_format($order->total, 2) }}**

@if($order->delivery_address)
### Delivery To:
{{ $order->delivery_address }}
@endif

@if($order->special_instructions)
### Note:
*{{ $order->special_instructions }}*
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
