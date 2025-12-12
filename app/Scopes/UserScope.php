<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class UserScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check()) {
            // Check if the model has a getUserColumnName method to override the column
            $userColumn = method_exists($model, 'getUserColumnName') ? $model->getUserColumnName() : 'user_id';
            $builder->where($model->getTable() . '.' . $userColumn, Auth::id());
        }
    }
}