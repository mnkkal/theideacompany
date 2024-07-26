<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment Gateway</title>
</head>
<body>
    <form action="/payment" method="POST">
        @csrf
        <label for="amount">Plan Id:</label>
        <input type="text" name="subscription_plan_id" id="amount" required>
        <label for="amount">Email:</label>
        <input type="email" name="email" id="email" required>
        <label for="amount">Contact:</label>
        <input type="text" name="contact" id="contact" required>
        <label for="amount">Address:</label>
        <input type="text" name="address" id="address" required>
        <button type="submit">Pay</button>
    </form>

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
</body>
</html>
