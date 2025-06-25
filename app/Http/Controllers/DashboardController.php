<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Loan;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBalance = Wallet::totalBalance();

        $totalExpense = Expense::totalBalance();

        $totalLoanExpenseBalance = Expense::totalLoanExpenseBalance();

        $totalLoan = Loan::totalBalance();

        $remainingBalance = $totalBalance - $totalExpense + $totalLoan;

        $remainingLoan = $totalLoan - $totalLoanExpenseBalance;

        $totalGross = $remainingBalance + $totalExpense;

        return Inertia::render('Dashboard', [
            'grossBalance'  => $totalGross,
            'totalBalance'  => $remainingBalance,
            'totalExpense'  => $totalExpense,
            'totalLoan'     => $remainingLoan,
        ]);
    }
}
