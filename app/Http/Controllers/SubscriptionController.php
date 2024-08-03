<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(Auth::user()->hasPermissionTo('manage-subcription')){
            $subscriptions = Subscription::all();
        
            return view('subscriptions.index', compact('subscriptions'));
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(Auth::user()->hasPermissionTo('manage_roles')){
            $chat_group = Group::all();
            $permissions = Permission::all();
            return view('subscriptions.create', compact('permissions', 'chat_group'));
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // validate image
        ]);
    
        $subplan = new Subscription();
        $subplan->name = $request->name;
        $subplan->description = $request->description;
        $subplan->price = $request->price;
    
        // Handle image upload
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $subplan->image = $imageName; 
        }
    
        if ($request->has('chat_group') && count($request->chat_group) > 0) {
            $subplan->chat_group = json_encode($request->chat_group);
        }
    
        if ($request->has('permissions') && count($request->permissions) > 0) {
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
        if(Auth::user()->hasPermissionTo('manage-subcription')){
            $chat_group = Group::all();
            $permissions = Permission::all();
            return view('subscriptions.edit', compact('subscription','permissions', 'chat_group'));
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // validate image
        ]);
    
        $subplan = Subscription::findOrFail($id);
        $subplan->name = $request->name;
        $subplan->description = $request->description;
        $subplan->price = $request->price;
    
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if exists
            if ($subplan->image && file_exists(public_path('images/' . $subplan->image))) {
                unlink(public_path('images/' . $subplan->image));
            }
    
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('images'), $imageName);
            $subplan->image = $imageName; // store the image name in the database
        }
    
        if ($request->has('chat_group') && count($request->chat_group) > 0) {
            $subplan->chat_group = json_encode($request->chat_group);
        } else {
            $subplan->chat_group = null;
        }
    
        if ($request->has('permissions') && count($request->permissions) > 0) {
            $subplan->permissions = json_encode($request->permissions);
        } else {
            $subplan->permissions = null;
        }
    
        $subplan->save();
    
        return redirect()->route('subscriptions.index')->with('success', 'Subscription updated successfully.');
    }
    

    public function destroy(Subscription $subscription)
    {
        if(Auth::user()->hasPermissionTo('manage-subcription')){
            $subscription->delete();

            return redirect()->route('subscriptions.index')->with('success', 'Subscription deleted successfully.');
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }
    }
}
