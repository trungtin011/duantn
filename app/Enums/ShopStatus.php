<?php

namespace App\Enums;

enum ShopStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case BANNED = 'banned';
    case SUSPENDED = 'suspended';
} 