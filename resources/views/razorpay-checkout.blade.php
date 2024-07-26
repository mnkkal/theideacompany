<!DOCTYPE html>
<html>
<head>
    <title>Razorpay Payment</title>
</head>
<body>
    <form action="{{ url('/payment/success') }}" method="POST">
        @csrf
        <script src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="{{ $data['key'] }}"
                data-amount="{{ $data['amount'] }}"
                data-name="{{ $data['name'] }}"
                data-description="{{ $data['description'] }}"
                data-prefill.name="{{ $data['prefill']['name'] }}"
                data-prefill.email="{{ $data['prefill']['email'] }}"
                data-prefill.contact="{{ $data['prefill']['contact'] }}"
                data-notes.address="{{ $data['notes']['address'] }}"
                data-order_id="{{ $data['order_id'] }}"
                data-theme.color="{{ $data['theme']['color'] }}">
        </script>
    </form>
</body>
</html>
