@extends('emails.shop._layout')
@section('title', 'Order Shipped')

@section('badge')
<div class="badge" style="background-color:#ea580c;">Shipped via LBC Express</div>
@endsection

@section('content')
<div class="greeting">Hi {{ $order->full_name }}!</div>
<p class="text">
    Good news: your TGC100 merch order has been handed over to <strong>LBC Express</strong>. Use the tracking number below to follow your package.
</p>

<div class="highlight">
    <div class="highlight-label">LBC Tracking Number</div>
    <div class="highlight-value">{{ $order->tracking_number }}</div>
</div>

<div class="cta">
    <a href="{{ $order->tracking_url }}">Track My Package</a>
</div>

<p class="text" style="font-size:13px;">
    If the button doesn't work, go to lbcexpress.com, choose Track, and enter the tracking number above.
    Tracking can take up to a day to show the first update.
</p>

@include('emails.shop._summary')
@endsection
