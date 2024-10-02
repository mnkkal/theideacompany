@extends('layouts.app')

@section('title')
    {{ __('Razorpay Payment Gateway') }}
@endsection

@section('css')
    <style>
        .payment-form {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .payment-form label {
            font-weight: bold;
            margin-top: 10px;
        }
        .payment-form input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .payment-form button {
            background-color: #F37254;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .payment-form button:hover {
            background-color: #c35036;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="payment-form">
            <form action="/payment" method="POST">
                @csrf
                <label for="subscription_plan_id">Plan Name:</label>
                <input type="text" name="subscription_plan_name" value="{{$subplan->name}}" disabled>

                <input type="hidden" name="subscription_plan_id" id="subscription_plan_id" value="{{$subplan->id}}">
                <label for="email">Amount(Inr):</label>
                <input type="number" name="price" value="{{$subplan->price}}" disabled>
                <label for="email">Name:</label>
                <input type="text" name="name" id="name" required>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>

                <label for="contact">Contact:</label>
                <input type="text" name="contact" id="contact" required>

                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required>

                <button type="submit">Pay</button>
            </form>
        </div>
    </div>

    @if(isset($orderId))
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        var options = {
            "key": "{{ env('RAZORPAY_KEY_ID') }}",
            "amount": "{{ old('amount') * 100 }}",
            "currency": "INR",
            "name": "Your Company Name",
            "description": "Test Transaction",
            "order_id": "{{ $orderId }}",
            "handler": function (response){
                window.location.href = '/payment/success?razorpay_payment_id=' + response.razorpay_payment_id + '&razorpay_order_id=' + response.razorpay_order_id + '&razorpay_signature=' + response.razorpay_signature;
            },
            "prefill": {
                "name": "Your Name",
                "email": "Your Email"
            },
            "theme": {
                "color": "#F37254"
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
    </script>
    @endif
@endsection
