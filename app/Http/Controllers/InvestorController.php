<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investor;

class InvestorController extends Controller
{
    public function index()
    {
        $investors = Investor::all();
        return view('investors.index', compact('investors'));
    }

    public function create()
    {
        return view('investors.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        if ($request->hasFile('product_demo')) {
            $data['product_demo'] = $request->file('product_demo')->store('product_demos');
        }
        if ($request->hasFile('pitch_deck')) {
            $data['pitch_deck'] = $request->file('pitch_deck')->store('pitch_decks');
        }
        if ($request->hasFile('certificate_incorporation')) {
            $data['certificate_incorporation'] = $request->file('certificate_incorporation')->store('certificates');
        }
    
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
        $investor = Investor::findOrFail($id);
        return view('investors.edit', compact('investor'));
    }

    public function update(Request $request, $id)
    {
        $investor = Investor::findOrFail($id);
        $data = $request->all();

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


  
}

