<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnSaleItem extends Model
{
    protected $table = 'return_sale_items';

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function returnSale(): BelongsTo
    {
        return $this->belongsTo(ReturnSale::class, 'return_sale_id');
    }
}
