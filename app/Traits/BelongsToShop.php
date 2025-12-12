<?php

namespace App\Traits;

use App\Scopes\ShopScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait BelongsToShop
{
    /**
     * Boot the trait
     */
    protected static function bootBelongsToShop(): void
    {
        // Apply the shop scope to all queries
        static::addGlobalScope(new ShopScope);

        // Automatically assign shop_id and created_by when creating records
        static::creating(function (Model $model) {
            if (Auth::check()) {
                $user = Auth::user();
                
                // Set created_by to current user
                if (!$model->created_by) {
                    $model->created_by = $user->id;
                }
                
                // Set shop_id based on user's active shop
                if (!$model->shop_id && $user->getActiveShop()) {
                    $model->shop_id = $user->getActiveShop()->id;
                }
            }
        });
    }

    /**
     * Get the shop that owns this model
     */
    public function shop()
    {
        return $this->belongsTo(\App\Models\Shop::class);
    }

    /**
     * Get the user who created this model
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * Scope to get records without shop filtering (admin use)
     */
    public function scopeWithoutShopScope($query)
    {
        return $query->withoutGlobalScope(ShopScope::class);
    }
}