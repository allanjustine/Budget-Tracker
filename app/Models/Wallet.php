<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Wallet extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bankType()
    {
        return $this->belongsTo(BankType::class);
    }

    public static function totalBalance()
    {
        return self::where('user_id', Auth::id())
            ->sum('amount');
    }
}
