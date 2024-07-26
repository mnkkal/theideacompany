<!DOCTYPE html>
<html>
<head>
    <title>Payment Page</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div>{{ session('error') }}</div>
    @endif

    <form action="{{ route('payment.make') }}" method="POST">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="contact">Contact:</label>
        <input type="text" name="contact" id="contact" required>

        <label for="address">Address:</label>
        <textarea name="address" id="address" required></textarea>

        <button type="submit">Pay</button>
    </form>

    @if (isset($data))
        <script>
            var options = {
                "key": "{{ $data['key'] }}",
                "amount": "{{ $data['amount'] }}",
                "name": "{{ $data['name'] }}",
                "description": "{{ $data['description'] }}",
                "order_id": "{{ $data['order_id'] }}",
                "prefill": {
                    "name": "{{ $data['prefill']['name'] }}",
                    "email": "{{ $data['prefill']['email'] }}",
                    "contact": "{{ $data['prefill']['contact'] }}"
                },
                "notes": {
                    "address": "{{ $data['notes']['address'] }}",
                    "merchant_order_id": "{{ $data['notes']['merchant_order_id'] }}"
                },
                "theme": {
                    "color": "{{ $data['theme']['color'] }}"
                },
                "handler": function (response){
                    document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                    document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
                    document.getElementById('razorpay_signature').value = response.razorpay_signature;
                    document.getElementById('payment-status-form').submit();
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        </script>

        <form id="payment-status-form" action="{{ route('payment.status') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        </form>
    @endif
</body>
</html>
