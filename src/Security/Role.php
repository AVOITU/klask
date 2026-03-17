<?php
namespace App\Security;

enum Role: string
{
    case ADMIN        = 'ADMIN';
    case ACCOMPANYING = 'ACCOMPANYING';
    case STUDENT      = 'STUDENT';
}
