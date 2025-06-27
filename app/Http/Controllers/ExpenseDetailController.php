<?php

namespace App\Http\Controllers;

use App\Models\ExpenseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ExpenseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenseDetails = ExpenseDetail::where('user_id', Auth::id())
            ->get();

        return Inertia::render('expenses/ExpenseDetail', [
            'expenseDetails' => $expenseDetails
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
            'name'              => ['required', 'min:1', 'unique:expense_details,name']
        ], [
            'name.required'     => 'Expense detail is required'
        ]);

        $expenseDetail = ExpenseDetail::create([
            'user_id'           => Auth::id(),
            'name'              => $request->name
        ]);

        return to_route('expense-details.index')
            ->with('success', "{$expenseDetail?->name} created successfully");
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
        $expenseDetail = ExpenseDetail::findOrFail($id);
        $request->validate([
            'name'              => ['required', 'min:1', 'unique:expense_details,name']
        ], [
            'name.required'     => 'Expense detail is required'
        ]);

        $expenseDetail->update([
            'name'              => $request->name
        ]);

        return to_route('expense-details.index')
            ->with('success', "{$expenseDetail?->name} updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $expenseDetail = ExpenseDetail::findOrFail($id);

        $expenseDetail->delete();

        return to_route('expense-details.index')
            ->with('success', "{$expenseDetail?->name} deleted successfully");
    }
}
