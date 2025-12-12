<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Role constants
    const ROLE_ADMIN = 'admin'; // Main admin role
    const ROLE_SHOP_OWNER = 'shop_owner';
    const ROLE_MANAGER = 'manager';
    const ROLE_EMPLOYEE = 'employee';

    protected $fillable = [
        'photo',
        'name',
        'username',
        'email',
        'password',
        'role',
        'shop_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function scopeSearch($query, $value): void
    {
        $query->where('name', 'like', "%{$value}%")
            ->orWhere('email', 'like', "%{$value}%");
    }

    public function getRouteKeyName(): string
    {
        return 'username';
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        // For backward compatibility - admin is the highest role
        return $this->isAdmin();
    }

    public function isManager(): bool
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    // New role-based helper methods
    public function isShopOwner(): bool
    {
        return $this->role === self::ROLE_SHOP_OWNER;
    }

    public function isManagerRole(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isEmployee(): bool
    {
        return $this->role === self::ROLE_EMPLOYEE;
    }

    public function hasInventoryAccess(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SHOP_OWNER, self::ROLE_MANAGER, self::ROLE_EMPLOYEE]);
    }

    public function hasFullAccess(): bool
    {
        return $this->isAdmin();
    }

    public function canAccessReports(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SHOP_OWNER, self::ROLE_MANAGER]);
    }

    public function canManageUsers(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SHOP_OWNER]);
    }

    public function canManageAllUsers(): bool
    {
        return $this->isAdmin();
    }

    public function canCreateShops(): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_SHOP_OWNER]);
    }

    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_SHOP_OWNER => 'Shop Owner',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_EMPLOYEE => 'Employee',
            default => 'Unknown'
        };
    }

    public static function getRoles(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrator',
            self::ROLE_SHOP_OWNER => 'Shop Owner',
            self::ROLE_MANAGER => 'Manager',
            self::ROLE_EMPLOYEE => 'Employee',
        ];
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // Multi-user relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function creditSales()
    {
        return $this->hasMany(CreditSale::class);
    }

    public function creditPayments()
    {
        return $this->hasMany(CreditPayment::class);
    }

    // Shop relationship
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function ownedShop()
    {
        return $this->hasOne(Shop::class, 'owner_id');
    }

    // Helper methods for shop management
    public function hasShop()
    {
        // Admin can work independently without a shop
        if ($this->isAdmin()) {
            return true;
        }
        return $this->shop_id !== null || $this->role === self::ROLE_SHOP_OWNER;
    }

    public function getActiveShop()
    {
        // Admin can access any shop, return the first available shop only when needed
        if ($this->isAdmin()) {
            // Return the first available shop, but don't create one automatically
            return Shop::where('is_active', true)->first();
        }

        if ($this->role === self::ROLE_SHOP_OWNER) {
            return $this->ownedShop && $this->ownedShop->is_active ? $this->ownedShop : null;
        }
        return $this->shop && $this->shop->is_active ? $this->shop : null;
    }



    public function isInShop($shopId)
    {
        if ($this->role === self::ROLE_SHOP_OWNER) {
            return $this->ownedShop && $this->ownedShop->id == $shopId;
        }
        return $this->shop_id == $shopId;
    }

    public function canAccessShop($shopId)
    {
        // Admin can access any shop
        if ($this->isAdmin()) {
            return true;
        }

        // Shop owners can access their own shop
        if ($this->role === self::ROLE_SHOP_OWNER) {
            return $this->ownedShop && $this->ownedShop->id == $shopId;
        }

        // Employees and managers can access their assigned shop
        if (in_array($this->role, [self::ROLE_EMPLOYEE, self::ROLE_MANAGER])) {
            return $this->shop_id == $shopId;
        }

        return false;
    }
}
