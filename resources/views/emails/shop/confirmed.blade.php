@extends('emails.shop._layout')
@section('title', 'Order Confirmed')

@section('badge')
<div class="badge" style="background-color:#22c55e;">&#10003; Payment Confirmed</div>
@endsection

@section('content')
<div class="greeting">Hi {{ $order->full_name }}!</div>
<p class="text">
    Thanks for your order. We've verified your payment and your TGC100 merch order is <strong>confirmed</strong>.
    @if($order->isPickup())
    We'll get in touch by email or text once it's ready for pickup in Baguio City.
    @else
    We'll ship it via LBC Express and email you the tracking number as soon as it's on its way.
    @endif
</p>

<div class="highlight">
    <div class="highlight-label">Order Reference</div>
    <div class="highlight-value">{{ $order->reference }}</div>
</div>

@include('emails.shop._summary')

<p class="text">Running in constant. See you on the trail!</p>
@endsection
