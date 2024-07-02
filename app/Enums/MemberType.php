<?php

namespace App\Enums;

enum MemberType: string
{
    case DEFAULT = 'default';
    case OFFLINE = 'offline';
    case GUEST = 'guest';
    case PENDING = 'pending';
}
