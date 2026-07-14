<?php

namespace App\Enums;

enum StoreRole: string
{
    case OWNER = 'owner';
    case EMPLOYEE = 'employee';
}