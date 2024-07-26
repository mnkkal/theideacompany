<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Transaction;
use App\Models\User;
use App\Models\RazorpayTransaction;
use App\Models\Subscription;

class PaymentController extends Controller
{
    protected $razorpay;

    public function __construct(Api $razorpay)
    {
        $this->razorpay = $razorpay;
    }

    public function index()
    {
        return view('payment');
    }

    public function store(Request $request)
    {
        $orderData = [
            'receipt'         => 'order_rcptid_11',
            'amount'          => $request->amount * 100, 
            'currency'        => 'INR',
        ];

        $order = $this->razorpay->order->create($orderData);

        $orderId = $order['id'];
        $newOrder = Order::create([
            'subscription_plan_id' => $request->subscription_plan_id,
            'user_id' => $request->user_id,
            'razorpay_order_id' => $orderId,
            'status' => 'created',
        ]);
        $data = [
            "key"               => env('RAZORPAY_KEY_ID'),
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

        return view('razorpay-checkout', compact('data'));
    }


    public function success(Request $request)
    {
        
        try {
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            ];

            $this->razorpay->utility->verifyPaymentSignature($attributes);
            $user = User::where('email', $request->email)->first();
            
            RazorpayTransaction::create([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'address' => $request->address,
                'amount' => $request->amount,
            ]);
            Transaction::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $request->subscription_plan_id,
                'ref'=> $request->razorpay_payment_id,
                'status'=>'successful',
                'amount' => $request->amount,
            ]);
           
            return view('success');
        } catch (\Exception $e) {
            return redirect()->route('payment.failure');
        }
    }

    public function failure()
    {
        return view('failure');
    }
}
