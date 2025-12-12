<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Payment extends Model
{
    use HasFactory, BelongsToUser;

    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'status',
        'transaction_id',
        'notes',
        'processed_at',
        'shop_id',
        'created_by',
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
     * Override the user column name for UserScope
     */
    public function getUserColumnName()
    {
        return 'created_by';
    }

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that created this payment (alias for creator)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
