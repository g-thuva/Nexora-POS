<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetails extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'serial_number',
        'warranty_years',
        'warranty_id',
        'warranty_name',
        'warranty_duration',
        'quantity',
        'unitcost',
        'total',
        'is_imported',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'warranty_years' => 'integer',
        'is_imported' => 'boolean',
    ];

    protected $with = ['product'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
