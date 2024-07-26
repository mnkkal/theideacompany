<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Spatie\Permission\Models\Permission;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
        $subscriptions = Subscription::all();
    
        return view('subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $chat_group = Group::all();
        $permissions = Permission::all();
        return view('subscriptions.create', compact('permissions', 'chat_group'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
        ]);
      $subplan =   new Subscription();
      $subplan->name = $request->name;
      $subplan->description = $request->description;
      $subplan->price = $request->price;
    if(count($request->chat_group) > 0){
        $subplan->chat_group = json_encode($request->chat_group);
    }
    if(count($request->permissions) > 0){
        $subplan->permissions = json_encode($request->permissions);
    }
      $subplan->save();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        return view('subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        $chat_group = Group::all();
        $permissions = Permission::all();
        return view('subscriptions.edit', compact('subscription','permissions', 'chat_group'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
        ]);
        $subscription->name = $request->name;
        $subscription->description = $request->description;
        $subscription->price = $request->price;
        if(count($request->chat_group) > 0){
            $subscription->chat_group = json_encode($request->chat_group);
        }
        if(count($request->permissions) > 0){
           $subscription->permissions = json_encode($request->permissions);
        }
        $subscription->save();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
    }
}
