<?php

namespace App\Http\Controllers;

use App\Models\BankType;
use App\Models\Expense;
use App\Models\Loan;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{

    private function getWalletChart()
    {
        $walletChart = Wallet::where('user_id', Auth::id())
            ->oldest()
            ->get()
            ->groupBy(['created_at' => fn($wallet) => $wallet->created_at->format('F')])
            ->map(fn($wallet) => $wallet->sum('amount'));

        $data = [];
        $labels = [];
        $walletChart;

        foreach ($walletChart as $month => $amount) {
            $labels[] = $month;
            $data[] = $amount;
        }

        $walletChart = [
            'labels' => $labels,
            'data'   => $data
        ];

        return $walletChart;
    }

    private function getExpenseChart()
    {
        $expenseChart = Expense::where('user_id', Auth::id())
            ->oldest()
            ->get()
            ->groupBy(['created_at' => fn($expense) => $expense->created_at->format('F')])
            ->map(fn($expense) => $expense->sum('amount'));

        $data = [];
        $labels = [];
        $expenseChart;

        foreach ($expenseChart as $month => $amount) {
            $labels[] = $month;
            $data[] = $amount;
        }

        $expenseChart = [
            'labels' => $labels,
            'data'   => $data
        ];

        return $expenseChart;
    }

    private function getLoanChart()
    {
        $loanChart = Loan::where('user_id', Auth::id())
            ->oldest()
            ->get()
            ->groupBy(['created_at' => fn($loan) => $loan->created_at->format('F')])
            ->map(fn($loan) => $loan->sum('amount'));

        $data = [];
        $labels = [];
        $loanChart;

        foreach ($loanChart as $month => $amount) {
            $labels[] = $month;
            $data[] = $amount;
        }

        $loanChart = [
            'labels' => $labels,
            'data'   => $data
        ];

        return $loanChart;
    }

    private function recentTransactions()
    {
        $recentWallets = Wallet::with(
            'bankType'
        )
            ->where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        $recentExpenses = Expense::with(
            'bankType',
            'expenseCategory',
            'expenseDetail',
            'loan.loanType',
            'loan.bankType',
            'loanType'
        )
            ->where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        $recentLoans = Loan::with(
            'loanType',
            'bankType'
        )
            ->where('user_id', Auth::id())
            ->latest()
            ->take(5)
            ->get();

        return [
            'recentWallets'     => $recentWallets,
            'recentExpenses'    => $recentExpenses,
            'recentLoans'       => $recentLoans
        ];
    }

    public function index()
    {
        $totalBalance = Wallet::totalBalance();

        $totalExpense = Expense::totalBalance();

        $totalLoanExpenseBalance = Expense::totalLoanExpenseBalance();

        $totalLoan = Loan::totalBalance();

        $remainingBalance = $totalBalance - $totalExpense + $totalLoan;

        $remainingLoan = $totalLoan - $totalLoanExpenseBalance;

        $totalGross = $remainingBalance + $totalExpense;

        $walletDetails = BankType::with([
            'wallets' => fn($wallet) => $wallet->where('user_id', Auth::id())->latest()
        ])
            ->whereHas('wallets', fn($wallet) => $wallet->where('user_id', Auth::id()))
            ->withCount([
                'wallets' => fn($wallet) => $wallet->where('user_id', Auth::id()),
            ])
            ->withSum(
                [
                    'wallets' => fn($wallet) => $wallet->where('user_id', Auth::id()),
                    'expenses' => fn($wallet) => $wallet->where('user_id', Auth::id()),
                    'loans' => fn($wallet) => $wallet->where('user_id', Auth::id())
                ],
                'amount',
            )
            ->get();

        $expenseDetails = BankType::with([
            'expenses' => fn($expense) => $expense->where('user_id', Auth::id())->latest(),
            'expenses.expenseCategory',
            'expenses.loanType',
            'expenses.loan.loanType',
            'expenses.loan.bankType',
            'expenses.expenseDetail'
        ])
            ->withCount([
                'expenses' => fn($expense) => $expense->where('user_id', Auth::id())
            ])
            ->whereHas('expenses', fn($expense) => $expense->where('user_id', Auth::id()))
            ->withSum(
                ['expenses' => fn($expense) => $expense->where('user_id', Auth::id())],
                'amount'
            )
            ->get();

        $loanDetails = BankType::with([
            'loans' => fn($loan) => $loan->where('user_id', Auth::id())->latest(),
            'loans.loanType',
        ])
            ->whereHas('loans', fn($loan) => $loan->where('user_id', Auth::id()))
            ->withCount([
                'loans' => fn($wallet) => $wallet->where('user_id', Auth::id())->latest()
            ])
            ->withSum(
                [
                    'loans' => fn($loan) => $loan->where('user_id', Auth::id()),
                    'loanExpenses' => fn($q) => $q->where('loans.user_id', Auth::id())
                ],
                'amount'
            )
            ->get();

        $walletChart = $this->getWalletChart();

        $expenseChart = $this->getExpenseChart();

        $loanChart = $this->getLoanChart();

        $recentTransactions = $this->recentTransactions();

        return Inertia::render('dashboard/Dashboard', [
            'grossBalance'          => $totalGross,
            'totalBalance'          => $remainingBalance,
            'totalExpense'          => $totalExpense,
            'totalLoan'             => $remainingLoan,
            'walletDetails'         => $walletDetails,
            'expenseDetails'        => $expenseDetails,
            'loanDetails'           => $loanDetails,
            'walletChart'           => $walletChart,
            'expenseChart'          => $expenseChart,
            'loanChart'             => $loanChart,
            'recentTransactions'    => $recentTransactions
        ]);
    }
}
