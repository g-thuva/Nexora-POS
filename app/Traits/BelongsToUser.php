<?php

namespace App\Traits;

use App\Scopes\UserScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait BelongsToUser
{
    /**
     * Boot the trait
     */
    protected static function bootBelongsToUser(): void
    {
        // Apply the user scope to all queries
        static::addGlobalScope(new UserScope);

        // Automatically assign user_id when creating records
        static::creating(function (Model $model) {
            if (Auth::check() && !$model->user_id) {
                $model->user_id = Auth::id();
            }
        });
    }

    /**
     * Get the user that owns this model
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Scope to get records without user filtering (admin use)
     */
    public function scopeWithoutUserScope($query)
    {
        return $query->withoutGlobalScope(UserScope::class);
    }
}