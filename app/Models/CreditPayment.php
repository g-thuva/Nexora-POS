<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToUser;

class CreditPayment extends Model
{
    use HasFactory, BelongsToUser;

    protected $fillable = [
        'user_id',
        'credit_sale_id',
        'payment_amount',
        'payment_date',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'payment_amount' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creditSale()
    {
        return $this->belongsTo(CreditSale::class);
    }

    // Accessors
    public function getPaymentAmountFormattedAttribute()
    {
        return number_format($this->payment_amount / 100, 2);
    }

    public function getPaymentMethodLabelAttribute()
    {
        return match($this->payment_method) {
            'cash' => 'Cash',
            'card' => 'Card',
            'bank_transfer' => 'Bank Transfer',
            'cheque' => 'Cheque',
            default => ucfirst($this->payment_method)
        };
    }
}