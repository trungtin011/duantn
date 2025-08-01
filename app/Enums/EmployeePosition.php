<?php

namespace App\Enums;

enum EmployeePosition: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case STAFF = 'staff';
} 