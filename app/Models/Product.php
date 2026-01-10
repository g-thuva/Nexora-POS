<?php

namespace App\Models;

use App\Traits\BelongsToShop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, BelongsToShop;

    public $fillable = [
        'shop_id',
        'created_by',
        'name',
        'slug',
        'code',
        'quantity',
        'quantity_alert',
        'buying_price',
        'selling_price',
        'notes',
        'product_image',
        'category_id',
        'unit_id',
        'warranty_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot method to auto-generate SKU/code when creating products
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->code)) {
                $product->code = static::generateSku();
            }
        });
    }

    /**
     * Generate unique code in format PRD00001, PRD00002, ...
     */
    public static function generateSku(): string
    {
        $prefix = 'PRD';

        // Find the latest PRD code and increment its numeric part
        $lastWithPrefix = static::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastWithPrefix && isset($lastWithPrefix->code)) {
            // Extract numeric part (last 5 digits)
            $number = (int) substr($lastWithPrefix->code, strlen($prefix));
            $nextNumber = $number + 1;
        }

        return $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function warranty(): BelongsTo
    {
        return $this->belongsTo(Warranty::class);
    }

    protected function buyingPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function sellingPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    /**
     * Enhanced search scope to search by name, code (SKU), and ID
     */
    public function scopeSearch($query, $value): void
    {
        $query->where('name', 'like', "%{$value}%")
            ->orWhere('code', 'like', "%{$value}%")
            ->orWhere('id', 'like', "%{$value}%");
    }
}
