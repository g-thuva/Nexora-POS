<?php

namespace App\Enums;

enum CreditStatus: string
{
    case PENDING = 'pending';
    case PARTIAL = 'partial';
    case PAID = 'paid';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::PARTIAL => 'Partially Paid',
            self::PAID => 'Fully Paid',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'red',
            self::PARTIAL => 'yellow',
            self::PAID => 'green',
        };
    }
}