<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankType extends Model
{
    protected $guarded = [];

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
