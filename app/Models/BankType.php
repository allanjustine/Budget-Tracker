<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankType extends Model
{
    protected $guarded = [];

    public function wallets()
    {
        return $this->hasManys(Wallet::class);
    }

    public function loans()
    {
        return $this->hasManys(Loan::class);
    }

    public function expenses()
    {
        return $this->hasManys(Expense::class);
    }
}
