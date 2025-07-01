<?php

namespace App\Http\Controllers;

use App\Models\BankType;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\ExpenseDetail;
use App\Models\Loan;
use App\Models\LoanType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expenseWallets = Expense::with('bankType', 'expenseCategory', 'loanType', 'loan.bankType', 'loan.loanType', 'expenseDetail')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $bankTypes = BankType::withSum(
            [
                'wallets' => fn($wallet) => $wallet->where('user_id', Auth::id()),
                'expenses' => fn($expense) => $expense->where('user_id', Auth::id()),
                'loans' => fn($loan) => $loan->where('user_id', Auth::id())
            ],
            'amount'
        )
            ->whereHas('wallets', fn($wallet) => $wallet->where('user_id', Auth::id()))
            ->latest()
            ->get();

        $expenseCategories = ExpenseCategory::all();

        $loanTypes = LoanType::withSum([
            'loans' => fn($loan) => $loan->where('user_id', Auth::id()),
            'expenses' => fn($expense) => $expense->where('user_id', Auth::id())
        ], 'amount')
            ->whereHas('loans', fn($loan) => $loan->where('user_id', Auth::id()))
            ->latest()
            ->get()
            ->filter(fn($loanType) => $loanType->loans_sum_amount > $loanType->expenses_sum_amount);

        $loans = Loan::with('bankType', 'loanType')
            ->withSum(
                [
                    'expenses' => fn($expense) => $expense->where('user_id', Auth::id())
                ],
                'amount'
            )
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $expenseDetails = ExpenseDetail::where('user_id', Auth::id())
            ->get();

        return Inertia::render('expenses/ExpenseWallet', [
            'expenseWallets'        => $expenseWallets,
            'bankTypes'             => $bankTypes,
            'expenseCategories'     => $expenseCategories,
            'loanTypes'             => $loanTypes,
            'loans'                 => $loans,
            'expenseDetails'        => $expenseDetails,
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
        $rules = [
            'remaining_balance'                        => ['nullable'],
            'amount'                                   => ['required', 'numeric', 'min:1', 'lte:remaining_balance'],
            'loan_type_id'                             => ['nullable', 'required_if:expense_category_others,loans', 'required_if:expense_category_others,Loans'],
            'loan_id'                                  => ['nullable', 'required_if:expense_category_others,loans', 'required_if:expense_category_others,Loans'],
        ];

        $messages = [
            'amount.required'                          => 'Amount is required',
            'amount.lte'                               => 'Your bank type is insufficient balance or selecting loan to expense is insufficient balance',
            'amount.numeric'                           => 'Amount must be a number',
            'amount.min'                               => 'Amount must be greater than 1',
            'loan_type_id.required_if'                 => 'Loan Type is required if expense category others is loans',
            'loan_id.required_if'                      => 'Loan Availed is required if expense category others is loans',
        ];

        if ($request->bank_type_id === 'others') {
            $rules['bank_type_others'] = ['required'];
            $messages['bank_type_others.required'] = 'Please specify the other bank type.';
            $bankType = BankType::firstOrCreate(['name' => $request->bank_type_others]);
            $bankTypeId = $bankType->id;
        } else {
            $rules['bank_type_id'] = ['required', 'exists:bank_types,id'];
            $messages['bank_type_id.required'] = 'Bank type is required';
            $messages['bank_type_id.exists'] = 'Selected bank type does not exist';
            $bankTypeId = $request->bank_type_id;
        }

        if ($request->expense_category_id === 'others') {
            $rules['expense_category_others'] = ['required'];
            $messages['expense_category_others.required'] = 'Please specify the other expense category.';
            $expenseCategory = ExpenseCategory::firstOrCreate(['name' => $request->expense_category_others]);
            $expenseCategoryId = $expenseCategory->id;
        } else {
            $rules['expense_category_id'] = ['required', 'exists:expense_categories,id'];
            $messages['expense_category_id.required'] = 'Expense category is required';
            $messages['expense_category_id.exists'] = 'Selected expense category does not exist';
            $expenseCategoryId = $request->expense_category_id;
        }

        if ($request->expense_detail_id === 'others') {
            $rules['expense_detail_others'] = ['required'];
            $messages['expense_detail_others.required'] = 'Please specify the other expense detail.';
            $expensedetail = ExpenseDetail::firstOrCreate(['user_id' => Auth::id(), 'name' => $request->expense_detail_others]);
            $expenseDetailId = $expensedetail->id;
        } else {
            $rules['expense_detail_id'] = ['required', 'exists:expense_details,id'];
            $messages['expense_detail_id.required'] = 'Expense detail is required';
            $messages['expense_detail_id.exists'] = 'Selected expense detail does not exist';
            $expenseDetailId = $request->expense_detail_id;
        }

        $request->validate($rules, $messages);

        $wallet = Expense::create([
            'user_id'                                  => Auth::id(),
            'bank_type_id'                             => $bankTypeId,
            'expense_category_id'                      => $expenseCategoryId,
            'expense_detail_id'                        => $expenseDetailId,
            'loan_type_id'                             => $request->loan_type_id,
            'loan_id'                                  => $request->loan_id,
            'amount'                                   => $request->amount
        ]);

        return to_route('expenses.index')->with('success', "You expense an amount of {$wallet->amount} to {$wallet->expenseCategory->name} using {$wallet->bankType->name} successfully");
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
        $wallet = Expense::findOrFail($id);

        $rules = [
            'remaining_balance'                        => ['nullable'],
            'amount'                                   => ['required', 'numeric', 'min:1', 'lte:remaining_balance'],
            'loan_type_id'                             => ['nullable', 'required_if:expense_category_others,loans', 'required_if:expense_category_others,Loans'],
            'loan_id'                                  => ['nullable', 'required_if:expense_category_others,loans', 'required_if:expense_category_others,Loans'],
        ];

        $messages = [
            'amount.required'                          => 'Amount is required',
            'amount.lte'                               => 'Amount must be below or equal to remaining balance',
            'amount.numeric'                           => 'Amount must be a number',
            'amount.min'                               => 'Amount must be greater than 1',
            'loan_type_id.required_if'                 => 'Loan Type is required if expense category others is loans',
            'loan_id.required_if'                      => 'Loan Availed is required if expense category others is loans',
        ];

        if ($request->bank_type_id === 'others') {
            $rules['bank_type_others'] = ['required'];
            $messages['bank_type_others.required'] = 'Please specify the other bank type.';
            $bankType = BankType::firstOrCreate(['name' => $request->bank_type_others]);
            $bankTypeId = $bankType->id;
        } else {
            $rules['bank_type_id'] = ['required', 'exists:bank_types,id'];
            $messages['bank_type_id.required'] = 'Bank type is required';
            $messages['bank_type_id.exists'] = 'Selected bank type does not exist';
            $bankTypeId = $request->bank_type_id;
        }

        if ($request->expense_category_id === 'others') {
            $rules['expense_category_others'] = ['required'];
            $messages['expense_category_others.required'] = 'Please specify the other expense category.';
            $expenseCategory = ExpenseCategory::firstOrCreate(['name' => $request->expense_category_others]);
            $expenseCategoryId = $expenseCategory->id;
        } else {
            $rules['expense_category_id'] = ['required', 'exists:expense_categories,id'];
            $messages['expense_category_id.required'] = 'Expense category is required';
            $messages['expense_category_id.exists'] = 'Selected expense category does not exist';
            $expenseCategoryId = $request->expense_category_id;
        }

        if ($request->expense_detail_id === 'others') {
            $rules['expense_detail_others'] = ['required'];
            $messages['expense_detail_others.required'] = 'Please specify the other expense detail.';
            $expensedetail = ExpenseDetail::firstOrCreate(['user_id' => Auth::id(), 'name' => $request->expense_detail_others]);
            $expenseDetailId = $expensedetail->id;
        } else {
            $rules['expense_detail_id'] = ['required', 'exists:expense_details,id'];
            $messages['expense_detail_id.required'] = 'Expense detail is required';
            $messages['expense_detail_id.exists'] = 'Selected expense detail does not exist';
            $expenseDetailId = $request->expense_detail_id;
        }

        $request->validate($rules, $messages);

        $wallet->update([
            'bank_type_id'                             => $bankTypeId,
            'expense_category_id'                      => $expenseCategoryId,
            'expense_detail_id'                        => $expenseDetailId,
            'loan_type_id'                             => Str::lower($request->expense_category_others) === 'loans' ? $request->loan_type_id : null,
            'loan_id'                                  => Str::lower($request->expense_category_others) === 'loans' ? $request->loan_id : null,
            'amount'                                   => $request->amount
        ]);

        return to_route('expenses.index')->with('success', "You updated your expense with an amount of {$wallet->amount} to {$wallet->expenseCategory->name} using {$wallet->bankType->name} successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $wallet = Expense::findOrFail($id);

        $wallet->delete();

        return to_route('expenses.index')->with('success', "You deleted your expense with an amount of {$wallet->amount} to {$wallet->expenseCategory->name} using {$wallet->bankType->name} successfully");
    }
}
