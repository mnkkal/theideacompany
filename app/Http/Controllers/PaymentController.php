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
        $subplan = Subscription::where('id', $request->subscription_plan_id)->first();
        $orderData = [
            'receipt'         => 'order_rcptid_11',
            'amount'          =>  $subplan->price * 100, 
            'currency'        => 'INR',
            'notes'           => [
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'address' => $request->address,
                'subscription_plan_id' => $request->subscription_plan_id,
            ]
        ];

        $order = $this->razorpay->order->create($orderData);

        $orderId = $order['id'];
        $user = User::where('email', $request->email)->first();
        $newOrder = Order::create([
            'subscription_plan_id' => $subplan->id,
            'user_id' => $user->id,
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
            $orderData = $this->razorpay->order->fetch($request->razorpay_order_id);
            // Update order status to 'paid'
            $order = Order::where('razorpay_order_id', $request->razorpay_order_id)->first();
            if ($order) {
                $order->update(['status' => 'paid']);
            }
            $user = User::where('email', $orderData['notes']['email'])->first();
            RazorpayTransaction::create([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'name' => $orderData['notes']['name'] ?? null,
                'email' => $orderData['notes']['email'] ?? null,
                'contact' => $orderData['notes']['contact'] ?? null,
                'address' => $orderData['notes']['address'] ?? null,
                'amount' => $orderData['amount']/100,
            ]);
            Transaction::create([
                'user_id' => $user->id ?? null,
                'subscription_plan_id' => $orderData['notes']['subscription_plan_id'] ?? null,
                'ref'=> $request->razorpay_payment_id ?? null,
                'status'=>'successful',
                'amount' => $orderData['amount']/100 ?? null,
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
