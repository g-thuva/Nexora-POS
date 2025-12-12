<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class ShopScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Skip scope for admin users
            if ($user && $user->isAdmin()) {
                return;
            }
            
            // Get the active shop for filtering
            $activeShop = $user->getActiveShop();
            if ($activeShop) {
                $builder->where($model->getTable() . '.shop_id', $activeShop->id);
            }
        }
    }
}