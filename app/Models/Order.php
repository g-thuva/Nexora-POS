<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory, BelongsToUser;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'customer_id',
        'order_date',
        'order_status',
        'total_products',
        'sub_total',
        'total',
        'invoice_no',
        'payment_type',
        'pay',
        'due',
        'shop_id',
        'created_by',
        'discount_amount',
        'service_charges',
    ];

    /**
     * Override the BelongsToUser trait to use created_by instead of user_id
     */
    protected static function bootBelongsToUser(): void
    {
        // Apply the user scope to all queries
        static::addGlobalScope(new \App\Scopes\UserScope);

        // Automatically assign created_by when creating records
        static::creating(function (Model $model) {
            if (Auth::check() && !$model->created_by) {
                $model->created_by = Auth::id();
            }
        });
    }

    /**
     * Get the user that created this order
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Override the user column name for UserScope
     */
    public function getUserColumnName()
    {
        return 'created_by';
    }

    protected $casts = [
        'order_date'    => 'date',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'order_status'  => OrderStatus::class
    ];

    // Accessors to convert integer cents back to decimal currency
    public function getSubTotalAttribute($value)
    {
        return $value / 100;
    }



    public function getTotalAttribute($value)
    {
        return $value / 100;
    }

    public function getPayAttribute($value)
    {
        return $value / 100;
    }

    public function getDueAttribute($value)
    {
        return $value / 100;
    }

    public function getDiscountAmountAttribute($value)
    {
        return $value / 100;
    }

    public function getServiceChargesAttribute($value)
    {
        return $value / 100;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function creditSale()
    {
        return $this->hasOne(CreditSale::class);
    }

    public function scopeSearch($query, $value): void
    {
        $query->where('invoice_no', 'like', "%{$value}%")
            ->orWhere('order_status', 'like', "%{$value}%")
            ->orWhere('payment_type', 'like', "%{$value}%");
    }
}
