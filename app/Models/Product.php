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
                // Generate a shop-scoped SKU so each shop has its own sequence
                $product->code = static::generateSku($product->shop_id);
            }
        });
    }

    /**
     * Generate unique SKU in format APFPRD0001, APFPRD0002, etc.
     */
    public static function generateSku($shopId = null): string
    {
        $prefix = 'PRD';

        // Resolve shop id: prefer explicit value, then active shop from auth
        $shopId = $shopId ?? auth()->user()?->getActiveShop()?->id;

        // Find last code for this shop and increment the numeric tail
        $lastCode = static::where('shop_id', $shopId)
            ->orderByDesc('id')
            ->value('code');

        $nextNumber = 1;
        if ($lastCode && preg_match('/^PRD(\d+)/', $lastCode, $matches)) {
            $nextNumber = ((int)$matches[1]) + 1;
        }

        // Format: PRD00001 (5-digit, per shop)
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
