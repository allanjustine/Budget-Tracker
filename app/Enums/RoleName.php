<?php

namespace App\Enums;

enum RoleName: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case ADMIN_ACCESS = 'admin-access';
    case USER_ACCESS = 'user-access';
}
