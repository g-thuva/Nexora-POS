<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'expense_date' => 'date',
        'details' => 'array',
    ];

    // Amount is stored in cents (integer)
    public function getAmountAttribute($value)
    {
        return $value / 100;
    }
}
