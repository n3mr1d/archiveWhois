<?php

namespace App\Enum;

enum Role: string
{
    case Admin = 'admininstrator';
    case Member = 'member';
    case Anonymous = 'anonymous';
    case Premium = 'premium';
}
