<?php

namespace App\Http\Controllers;

use App\Models\BankType;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AccountWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $accountWallets = Wallet::with('bankType')
            ->latest()
            ->get();

        $bankTypes = BankType::all();

        return Inertia::render('wallets/AccountWallet', [
            'accountWallets' => $accountWallets,
            'bankTypes'      => $bankTypes
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
            'bank_type_id'              => ['required', 'exists:bank_types,id'],
            'amount'                    => ['required', 'numeric', 'min:1']
        ], [
            'bank_type_id.required'     => 'Bank type is required',
            'bank_type_id.exists'       => 'Selected bank type does not exist',
            'amount.required'           => 'Amount is required',
            'amount.numeric'            => 'Amount must be a number',
            'amount.min'                => 'Amount must be greater than 1'
        ]);

        $wallet = Wallet::create([
            'user_id'                   => Auth::id(),
            'bank_type_id'              => $request->bank_type_id,
            'amount'                    => $request->amount
        ]);

        return to_route('account-wallets.index')->with('success', "Your wallet balance {$wallet->amount} amount using {$wallet->bankType->name} added successfully");
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
        $wallet = Wallet::findOrFail($id);

        $request->validate([
            'bank_type_id'              => ['required', 'exists:bank_types,id'],
            'amount'                    => ['required', 'numeric', 'min:1']
        ], [
            'bank_type_id.required'     => 'Bank type is required',
            'bank_type_id.exists'       => 'Selected bank type does not exist',
            'amount.required'           => 'Amount is required',
            'amount.numeric'            => 'Amount must be a number',
            'amount.min'                => 'Amount must be greater than 1'
        ]);

        $wallet->update([
            'bank_type_id'              => $request->bank_type_id,
            'amount'                    => $request->amount
        ]);

        return to_route('account-wallets.index')->with('success', "Your wallet balance {$wallet->amount} amount using {$wallet->bankType->name} updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $wallet = Wallet::findOrFail($id);

        $wallet->delete();

        return to_route('account-wallets.index')->with('success', "Your wallet balance with {$wallet->amount} amount using {$wallet->bankType->name} deleted successfully");
    }
}
