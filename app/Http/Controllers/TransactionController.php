<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;

class TransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      
        $transactions = Transaction::all();
        return view('transactions.index', compact('transactions'));
        
    }

    // /**
    //  * Store a newly created transaction in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'subscription_plan_id' => 'nullable|exists:subscription_plans,id',
    //         'amount' => 'required|numeric',
    //         'status' => 'required|string',
    //         'processed' => 'boolean',
    //     ]);

    //     $transaction = Transaction::create($validatedData);

    //     return response()->json($transaction, 201);
    // }

    /**
     * Display the specified transaction.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return response()->json($transaction);
    }



    /**
     * Remove the specified transaction from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return response()->json(null, 204);
    }
    public function create()
    {
        $users = User::all();
        $subscriptionPlans = Subscription::all();
        return view('transactions.create', compact('users', 'subscriptionPlans'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_plan_id' => 'nullable|exists:subscriptions,id',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'processed' => 'boolean',
        ]);

        $transaction = Transaction::create($validatedData);

        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }
    public function edit(Transaction $transaction)
    {
        $users = User::all();
        $subscriptionPlans = Subscription::all();
        return view('transactions.edit', compact('transaction', 'users', 'subscriptionPlans'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subscription_plan_id' => 'nullable|exists:subscriptions,id',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'processed' => 'boolean',
        ]);

        $transaction->update($validatedData);

        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }
}
