<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentGroup;

class ParentGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parentGroups = ParentGroup::all();
        return view('parent_groups.index', compact('parentGroups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('parent_groups.create');
    }

   /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        ParentGroup::create($request->all());

        return redirect()->route('parent-groups.index')->with('success', 'Parent Group created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

   /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ParentGroup  $parentGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(ParentGroup $parentGroup)
    {
        return view('parent_groups.edit', compact('parentGroup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ParentGroup  $parentGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ParentGroup $parentGroup)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $parentGroup->update($request->all());

        return redirect()->route('parent-groups.index')->with('success', 'Parent Group updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ParentGroup  $parentGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParentGroup $parentGroup)
    {
        $parentGroup->delete();
        return redirect()->route('parent-groups.index')->with('success', 'Parent Group deleted successfully.');
    }
}
