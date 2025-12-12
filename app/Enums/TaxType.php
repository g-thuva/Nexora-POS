<?php

namespace App\Enums;

enum TaxType: string
{
    case INCLUSIVE = 'inclusive';
    case EXCLUSIVE = 'exclusive';
    case EXEMPT = 'exempt';

    public function label(): string
    {
        return match($this) {
            self::INCLUSIVE => 'Inclusive',
            self::EXCLUSIVE => 'Exclusive',
            self::EXEMPT => 'Exempt',
        };
    }
}
