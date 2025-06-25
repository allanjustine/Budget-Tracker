<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenseCategories = ExpenseCategory::all();

        return Inertia::render('expenses/ExpenseCategory', [
            'expenseCategories' => $expenseCategories
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
            'name'              => ['required', 'min:1', 'unique:loan_types,name']
        ], [
            'name.required'     => 'Loan name is required'
        ]);

        $expenseCategory = ExpenseCategory::create([
            'name'              => $request->name
        ]);

        return to_route('expense-categories.index')
            ->with('success', "{$expenseCategory?->name} created successfully");
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
        $expenseCategory = ExpenseCategory::findOrFail($id);

        $request->validate([
            'name'              => ['required', 'min:1', "unique:loan_types,name,{$expenseCategory->id}"]
        ], [
            'name.required'     => 'Loan name is required'
        ]);

        $expenseCategory->update([
            'name'              => $request->name
        ]);

        return to_route('expense-categories.index')->with('success', "{$expenseCategory?->name} updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expenseCategory = ExpenseCategory::findOrFail($id);

        $expenseCategory->delete();

        return to_route('expense-categories.index')->with('success', "{$expenseCategory?->name} deleted successfully");
    }
}
