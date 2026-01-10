<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'owner_id',
        'is_active',
        'letterhead_config',
        'job_letterhead_config',
        'subscription_start_date',
        'subscription_end_date',
        'subscription_status',
        'is_suspended',
        'suspended_at',
        'suspended_by',
        'suspension_reason'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_suspended' => 'boolean',
        'letterhead_config' => 'array',
        'job_letterhead_config' => 'array',
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date',
        'suspended_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function suspendedBy()
    {
        return $this->belongsTo(User::class, 'suspended_by');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'shop_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'shop_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'shop_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'shop_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'shop_id');
    }

    public function units()
    {
        return $this->hasMany(Unit::class, 'shop_id');
    }

    // Helper methods
    public function isOwnedBy(User $user)
    {
        return $this->owner_id === $user->id;
    }
}
