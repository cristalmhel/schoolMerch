<?php

namespace App\Enums;

enum UserRole: string
{
    case Student = 'student';
    case Admin = 'admin';
    case Super_Admin = 'super_admin';
}
