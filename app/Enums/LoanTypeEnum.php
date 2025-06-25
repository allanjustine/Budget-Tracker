<?php

namespace App\Enums;

enum LoanTypeEnum: string
{
    case CASH = 'cash';
    case BANK = 'bank';
    case LENDING = 'lending';
}
