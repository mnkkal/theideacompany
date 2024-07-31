<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investor;
use App\Models\PitchUserLikesDislike;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class InvestorController extends Controller
{
    public function index()
    {
        $investors = Investor::with('userLikesDislikes')->get();
        return view('investors.index', compact('investors'));
    }

    public function create()
    {
        return view('investors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
           
        ]);
        $data = $request->all();
        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $request->file('company_logo')->store('logos');
        }
        if ($request->hasFile('product_demo')) {
            $data['product_demo'] = $request->file('product_demo')->store('product_demos');
        }
        if ($request->hasFile('pitch_deck')) {
            $data['pitch_deck'] = $request->file('pitch_deck')->store('pitch_decks');
        }
        if ($request->hasFile('certificate_incorporation')) {
            $data['certificate_incorporation'] = $request->file('certificate_incorporation')->store('certificates');
        }
        $data['created_by'] = auth()->user()->id;
        $investor = Investor::create($data);
    
        foreach ($request->founders as $founder) {
            $investor->founders()->create($founder);
        }
    
        return redirect()->route('investors.index');
    }
    

    public function show($id)
    {
        $investor = Investor::find($id);
        return view('investors.show', compact('investor'));
    }

    public function edit($id)
    {
        if(Auth::user()->hasPermissionTo('manage_investors')){
            $investor = Investor::findOrFail($id);
            return view('investors.edit', compact('investor'));
        }else if (Investor::where('id', $id)->where('created_by', auth()->user()->id)->exists()){
            $investor = Investor::where('id', $id)->where('created_by', auth()->user()->id)->first();
            return view('investors.edit', compact('investor'));
        }else{
            return Redirect::route('home')->with('error', 'Sorry! You do not have permission to access this page!');
        }
        
    }

    public function update(Request $request, $id)
    {
        $investor = Investor::findOrFail($id);
        $data = $request->all();
        if ($request->hasFile('company_logo')) {
            $data['company_logo'] = $request->file('company_logo')->store('logos');
        }
        if ($request->hasFile('product_demo')) {
            $data['product_demo'] = $request->file('product_demo')->store('product_demos');
        }
        if ($request->hasFile('pitch_deck')) {
            $data['pitch_deck'] = $request->file('pitch_deck')->store('pitch_decks');
        }
        if ($request->hasFile('certificate_incorporation')) {
            $data['certificate_incorporation'] = $request->file('certificate_incorporation')->store('certificates');
        }
       
        $investor->update($data);

        // Delete old founders and add new ones
        $investor->founders()->delete();
        foreach ($request->founders as $founder) {
            $investor->founders()->create($founder);
        }

        return redirect()->route('investors.index');
    }

    public function destroy($id)
    {
        $investor = Investor::findOrFail($id);
        $investor->delete();
        return redirect()->route('investors.index');
    }
    public function likes($id)
    {
        $pitch = Investor::findOrFail($id); // Assuming Investor model is used for pitches
        $userId = Auth::id();

        // Check if the user has already liked or disliked the pitch
        $existing = PitchUserLikesDislike::where('user_id', $userId)->where('pitch_id', $id)->first();

        if ($existing) {
            if ($existing->liked) {
                return redirect()->route('dashboard.index')->with('error', 'You have already liked this pitch.');
            } elseif ($existing->disliked) {
                // If the user has disliked, remove the dislike and add a like
                $existing->disliked = null;
                $existing->liked = true;
                $pitch->dislikes--;
                $pitch->likes++;
                $existing->save();
            }
        } else {
            // Add a new like
            PitchUserLikesDislike::create(['user_id' => $userId, 'pitch_id' => $id, 'liked' => true]);
            $pitch->likes++;
        }

        $pitch->save();
        return redirect()->route('dashboard.index')->with('success', '1 like');
    }

    public function dislikes($id)
    {
        $pitch = Investor::findOrFail($id); // Assuming Investor model is used for pitches
        $userId = Auth::id();

        // Check if the user has already liked or disliked the pitch
        $existing = PitchUserLikesDislike::where('user_id', $userId)->where('pitch_id', $id)->first();

        if ($existing) {
            if ($existing->disliked) {
                return redirect()->route('dashboard.index')->with('error', 'You have already disliked this pitch.');
            } elseif ($existing->liked) {
                // If the user has liked, remove the like and add a dislike
                $existing->liked = null;
                $existing->disliked = true;
                $pitch->likes--;
                $pitch->dislikes++;
                $existing->save();
            }
        } else {
            // Add a new dislike
            PitchUserLikesDislike::create(['user_id' => $userId, 'pitch_id' => $id, 'disliked' => true]);
            $pitch->dislikes++;
        }

        $pitch->save();
        return redirect()->route('dashboard.index')->with('success', '1 dislike');
    }
    public function interest($id)
    {
        $investor = Investor::findOrFail($id);
        $investor->views = $investor->views + 1;
        $investor->save();
        return redirect()->route('investors.show', $id);
    }


  
}

