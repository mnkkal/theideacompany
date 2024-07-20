<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function paymentPage()
    {
        return view('payment');
    }

    public function createOrder(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $orderData = [
            'receipt'         => 'rcptid_' . time(),
            'amount'          => 50000, 
            'currency'        => 'INR',
            'payment_capture' => 1 
        ];

        $razorpayOrder = $api->order->create($orderData);

        $orderId = $razorpayOrder['id'];

        Session::put('razorpay_order_id', $orderId);

        $data = [
            "key"               => env('RAZORPAY_KEY'),
            "amount"            => $orderData['amount'],
            "name"              => "Thinker Club",
            "description"       => "Thinker Club Payment",
            "prefill"           => [
                "name"              => $request->name,
                "email"             => $request->email,
                "contact"           => $request->contact,
            ],
            "notes"             => [
                "address"           => $request->address,
                "merchant_order_id" => $orderId,
            ],
            "theme"             => [
                "color"             => "#F37254"
            ],
            "order_id"          => $orderId,
        ];

        return view('payment-page', compact('data'));
    }

    public function paymentCallback(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $attributes  = array(
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature
        );

        try {
            $api->utility->verifyPaymentSignature($attributes);

            

            return redirect()->route('payment.page')->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            // Payment verification failed
            return redirect()->route('payment.page')->with('error', 'Payment failed!');
        }
    }
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        // Verify the webhook signature
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $webhookSecret = env('RAZORPAY_WEBHOOK_SECRET');

        $signature = $request->header('X-Razorpay-Signature');
        $expectedSignature = hash_hmac('sha256', json_encode($payload), $webhookSecret);

        if (hash_equals($expectedSignature, $signature)) {
            // Handle the webhook payload
            // Example: $event = $payload['event'];

            return response()->json(['status' => 'success'], 200);
        } else {
            return response()->json(['status' => 'invalid signature'], 400);
        }
    }

}
