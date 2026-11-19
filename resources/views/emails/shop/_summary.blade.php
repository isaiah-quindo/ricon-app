<div class="card">
    <h3>Your Order</h3>
    <table class="table">
        @foreach($order->items as $item)
        <tr>
            <td>
                {{ $item->quantity }} &times; {{ $item->product_name }}<br>
                <span class="muted" style="font-size:13px;">{{ $item->options_label }}</span>
            </td>
            <td class="right">&#8369;{{ number_format($item->line_total, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td class="muted">Shipping ({{ $order->shipping_area_label ?? 'n/a' }})</td>
            <td class="right">&#8369;{{ number_format($order->shipping_fee, 2) }}</td>
        </tr>
        <tr class="total">
            <td>Total paid</td>
            <td class="right">&#8369;{{ number_format($order->total, 2) }}</td>
        </tr>
    </table>
</div>

<div class="card">
    <h3>{{ $order->isPickup() ? 'Pickup' : 'Shipping To' }}</h3>
    <p class="text" style="margin:0;">
        {{ $order->full_name }} &middot; {{ $order->mobile_number }}<br>
        @if($order->isPickup())
        Pickup in Baguio City. We'll message you when it's ready.
        @else
        {{ $order->shipping_address }}
        @endif
    </p>
</div>
