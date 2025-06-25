<?php

namespace App\Http\Controllers;

use App\Models\LoanType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoanTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loanTypes = LoanType::all();

        return Inertia::render('loans/LoanType', [
            'loanTypes' => $loanTypes
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
            'name'              => ['required', 'min:1', 'unique:loan_types,name', 'in:cash,lending,bank']
        ], [
            'name.required'     => 'Loan name is required'
        ]);

        $loanType = LoanType::create([
            'name'              => $request->name
        ]);

        return to_route('loan-types.index')
            ->with('success', "{$loanType?->name->value} created successfully");
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
        $loanType = LoanType::findOrFail($id);

        $request->validate([
            'name'              => ['required', 'min:1', "unique:loan_types,name,{$loanType->id}"]
        ], [
            'name.required'     => 'Loan name is required'
        ]);

        $loanType->update([
            'name'              => $request->name
        ]);

        return to_route('loan-types.index')->with('success', "{$loanType?->name->value} updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loanType = LoanType::findOrFail($id);

        $loanType->delete();

        return to_route('loan-types.index')->with('success', "{$loanType?->name->value} deleted successfully");
    }
}
