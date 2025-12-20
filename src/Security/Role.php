<?php
namespace Security;

enum Role: string
{
    case ADMIN        = 'ADMIN';
    case ACCOMPANYING = 'ACCOMPANYING';
    case STUDENT      = 'STUDENT';
}
