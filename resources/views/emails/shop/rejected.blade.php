@extends('emails.shop._layout')
@section('title', 'Payment Not Verified')

@section('badge')
<div class="badge" style="background-color:#ef4444;">Payment Not Verified</div>
@endsection

@section('content')
<div class="greeting">Hi {{ $order->full_name }},</div>
<p class="text">
    We weren't able to verify the payment for your TGC100 merch order <strong>{{ $order->reference }}</strong>, so it has not been confirmed and the items have been released.
</p>

@if($order->rejection_reason)
<div class="card">
    <h3>Note From Our Team</h3>
    <p class="text" style="margin:0;">{!! nl2br(e($order->rejection_reason)) !!}</p>
</div>
@endif

<p class="text">
    If you believe this is a mistake, or you've already sent the transfer, reply to
    <a href="mailto:{{ config('shop.contact_email') }}">{{ config('shop.contact_email') }}</a>
    with your order reference and a clear photo of your receipt and we'll sort it out.
</p>

@include('emails.shop._summary')
@endsection
