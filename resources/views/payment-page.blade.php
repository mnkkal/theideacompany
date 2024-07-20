@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('payment.callback') }}" method="POST" id="razorpay-form">
        @csrf
        <script
            src="https://checkout.razorpay.com/v1/checkout.js"
            data-key="{{ $data['key'] }}"
            data-amount="{{ $data['amount'] }}"
            data-name="{{ $data['name'] }}"
            data-description="{{ $data['description'] }}"
            data-prefill.name="{{ $data['prefill']['name'] }}"
            data-prefill.email="{{ $data['prefill']['email'] }}"
            data-prefill.contact="{{ $data['prefill']['contact'] }}"
            data-notes.shopping_order_id="{{ $data['notes']['merchant_order_id'] }}"
            data-order_id="{{ $data['order_id'] }}"
            data-theme.color="{{ $data['theme']['color'] }}"
        ></script>
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature" id="razorpay_signature">
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var razorpayForm = document.getElementById('razorpay-form');
        razorpayForm.submit();
    });
</script>
@endsection
