<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;
    
    // Units are universal - no shop scoping needed

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'name',
        'slug',
        'short_code',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeSearch($query, $value): void
    {
        $query->where('name', 'like', "%{$value}%")
            ->orWhere('slug', 'like', "%{$value}%")
            ->orWhere('short_code', 'like', "%{$value}%");
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
