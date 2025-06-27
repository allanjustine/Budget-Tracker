<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseDetail extends Model
{
    protected $guarded = [];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
