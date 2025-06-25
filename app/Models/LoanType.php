<?php

namespace App\Models;

use App\Enums\LoanTypeEnum;
use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'name'  => LoanTypeEnum::class,
        ];
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
