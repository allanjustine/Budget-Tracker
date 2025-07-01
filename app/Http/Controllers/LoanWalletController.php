<?php

namespace App\Http\Controllers;

use App\Models\BankType;
use App\Models\Loan;
use App\Models\LoanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LoanWalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loanWallets = Loan::with('loanType', 'bankType')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $loanTypes = LoanType::all();

        $bankTypes = BankType::whereHas('wallets', fn($wallet) => $wallet->where('user_id', Auth::id()))
            ->latest()
            ->get();

        return Inertia::render('loans/LoanWallet', [
            'loanWallets'         => $loanWallets,
            'loanTypes'           => $loanTypes,
            'bankTypes'           => $bankTypes
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
        if ($request->bank_type_id === 'others') {
            $request->validate([
                'bank_type_others'              => ['required'],
                'loan_type_id'                  => ['required', 'exists:loan_types,id'],
                'amount'                        => ['required', 'numeric', 'min:1']
            ], [
                'bank_type_others.required'     => 'Please specify the other bank type.',
                'loan_type_id.required'         => 'Loan type is required',
                'loan_type_id.exists'           => 'Selected loan type does not exist',
                'amount.required'               => 'Amount is required',
                'amount.numeric'                => 'Amount must be a number',
                'amount.min'                    => 'Amount must be greater than 1'
            ]);

            $bankType = BankType::firstOrCreate([
                'name'                          => $request->bank_type_others,
            ]);

            $bankTypeId = $bankType->id;
        } else {
            $request->validate([
                'bank_type_id'                  => ['required', 'exists:bank_types,id'],
                'loan_type_id'                  => ['required', 'exists:loan_types,id'],
                'amount'                        => ['required', 'numeric', 'min:1']
            ], [
                'bank_type_id.required'         => 'Bank type is required',
                'loan_type_id.required'         => 'Loan type is required',
                'bank_type_id.exists'           => 'Selected bank type does not exist',
                'loan_type_id.exists'           => 'Selected loan type does not exist',
                'amount.required'               => 'Amount is required',
                'amount.numeric'                => 'Amount must be a number',
                'amount.min'                    => 'Amount must be greater than 1'
            ]);

            $bankTypeId = $request->bank_type_id;
        }

        $loan = Loan::create([
            'user_id'                           => Auth::id(),
            'bank_type_id'                      => $bankTypeId,
            'loan_type_id'                      => $request->loan_type_id,
            'amount'                            => $request->amount
        ]);

        $item = Str::upper($loan->loanType?->name?->value);

        return to_route('loans.index')->with('success', "Your loan {$item} with the amount of {$loan->amount} using {$loan->bankType->name} added successfully");
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

        $loan = Loan::findOrFail($id);

        if ($request->bank_type_id === 'others') {
            $request->validate([
                'bank_type_others'              => ['required'],
                'loan_type_id'                  => ['required', 'exists:loan_types,id'],
                'amount'                        => ['required', 'numeric', 'min:1']
            ], [
                'bank_type_others.required'     => 'Please specify the other bank type.',
                'loan_type_id.required'         => 'Loan type is required',
                'loan_type_id.exists'           => 'Selected loan type does not exist',
                'amount.required'               => 'Amount is required',
                'amount.numeric'                => 'Amount must be a number',
                'amount.min'                    => 'Amount must be greater than 1'
            ]);

            $bankType = BankType::firstOrCreate([
                'name'                          => $request->bank_type_others,
            ]);

            $bankTypeId = $bankType->id;
        } else {
            $request->validate([
                'bank_type_id'                  => ['required', 'exists:bank_types,id'],
                'loan_type_id'                  => ['required', 'exists:loan_types,id'],
                'amount'                        => ['required', 'numeric', 'min:1']
            ], [
                'bank_type_id.required'         => 'Bank type is required',
                'loan_type_id.required'         => 'Loan type is required',
                'bank_type_id.exists'           => 'Selected bank type does not exist',
                'loan_type_id.exists'           => 'Selected loan type does not exist',
                'amount.required'               => 'Amount is required',
                'amount.numeric'                => 'Amount must be a number',
                'amount.min'                    => 'Amount must be greater than 1'
            ]);

            $bankTypeId = $request->bank_type_id;
        }

        $loan->update([
            'user_id'                           => Auth::id(),
            'bank_type_id'                      => $bankTypeId,
            'loan_type_id'                      => $request->loan_type_id,
            'amount'                            => $request->amount
        ]);

        $item = Str::upper($loan->loanType->name?->value);

        return to_route('loans.index')->with('success', "Your loan {$item} with the amount of {$loan->amount} using {$loan->bankType->name} updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loan = Loan::findOrFail($id);

        $loan->delete();

        $item = Str::upper($loan->loanType->name?->value);

        return to_route('loans.index')->with('success', "Your loan {$item} with the amount of {$loan->amount} using {$loan->bankType->name} deleted successfully");
    }
}
