<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSubscription;

class UserSubscriptionController extends Controller
{
   /**
     * Display a listing of the subscriptions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $UserSubscription = UserSubscription::all();
        return response()->json($UserSubscription);
    }

    /**
     * Store a newly created subscription in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_plan_id' => 'nullable|exists:subscription_plans,id',
            'transaction_id' => 'nullable|exists:transactions,id',
            'plan_amount' => 'required|numeric',
            'plan_frequency' => 'required|integer',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
            'trial_ends_at' => 'nullable|date',
            'sms_limit' => 'required|integer',
            'status' => 'required|integer',
        ]);

        $UserSubscription = UserSubscription::create($validatedData);

        return response()->json($UserSubscription, 201);
    }

    /**
     * Display the specified subscription.
     *
     * @param  \App\Models\UserSubscription  $subscription
     * @return \Illuminate\Http\Response
     */
    public function show(UserSubscription $UserSubscription)
    {
        return response()->json($UserSubscription);
    }

    /**
     * Update the specified subscription in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserSubscription  $UserSubscription
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserSubscription $UserSubscription)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_plan_id' => 'nullable|exists:subscription_plans,id',
            'transaction_id' => 'nullable|exists:transactions,id',
            'plan_amount' => 'required|numeric',
            'plan_frequency' => 'required|integer',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
            'trial_ends_at' => 'nullable|date',
            'sms_limit' => 'required|integer',
            'status' => 'required|integer',
        ]);

        $UserSubscription->update($validatedData);

        return response()->json($UserSubscription);
    }

    /**
     * Remove the specified subscription from storage.
     *
     * @param  \App\Models\UserSubscription  $UserSubscription
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserSubscription $UserSubscription)
    {
        $UserSubscription->delete();

        return response()->json(null, 204);
    }
}
