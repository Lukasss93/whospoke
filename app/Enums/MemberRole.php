<?php

namespace App\Enums;

enum MemberRole: string
{
    case DEFAULT = 'default';
    case OWNER = 'owner';
    case EDITOR = 'editor';
}
