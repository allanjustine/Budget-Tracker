<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Expense extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    public function bankType()
    {
        return $this->belongsTo(BankType::class);
    }

    public function loanType()
    {
        return $this->belongsTo(LoanType::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public static function totalBalance()
    {
        return self::where('user_id', Auth::id())
            ->sum('amount');
    }

    public static function totalLoanExpenseBalance()
    {
        return self::where('user_id', Auth::id())
            ->whereHas('loanType')
            ->whereHas('loan')
            ->sum('amount');
    }
}
