<?php

namespace App\Http\Controllers;

use App\Models\BankType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BankTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bankTypes = BankType::all();

        return Inertia::render('banks/BankType', [
            'bankTypes' => $bankTypes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => ['required', 'min:1', 'unique:bank_types,name']
        ], [
            'name.required'     => 'Bank name is required'
        ]);

        $bankType = BankType::create([
            'name'              => $request->name
        ]);

        return to_route('bank-types.index')
            ->with('success', "{$bankType->name} created successfully");
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
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $bankType = BankType::findOrFail($id);

        $request->validate([
            'name'              => ['required', 'min:1', "unique:bank_types,name,{$bankType->id}"]
        ], [
            'name.required'     => 'Bank name is required'
        ]);

        $bankType->update([
            'name'              => $request->name
        ]);

        return to_route('bank-types.index')->with('success', "{$bankType->name} updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bankType = BankType::findOrFail($id);

        $bankType->delete();

        return to_route('bank-types.index')->with('success', "{$bankType->name} deleted successfully");
    }
}
