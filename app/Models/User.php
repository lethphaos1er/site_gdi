<?php

namespace App\Models;

use App\Enums\StoreRole;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'name',
    'first_name',
    'last_name',
    'email',
    'password',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }
    public function hasStoreRole(Store $store, StoreRole $role): bool
    {
        return $this->stores()
            ->whereKey($store->getKey())
            ->wherePivot('role', $role->value)
            ->exists();
    }

    public function isOwnerOf(Store $store): bool
    {
        return $this->hasStoreRole($store, StoreRole::OWNER);
    }

    public function isEmployeeOf(Store $store): bool
    {
        return $this->hasStoreRole($store, StoreRole::EMPLOYEE);
    }

    public function canManageStore(Store $store): bool
    {
        return $this->isAdmin()
            || $this->isOwnerOf($store)
            || $this->isEmployeeOf($store);
    }

    public function canManageStaffAt(Store $store): bool
    {
        return $this->isAdmin()
            || $this->isOwnerOf($store);
    }

    public function canAccessStore(Store $store): bool
    {
        return $this->isAdmin()
            || $this->stores()
            ->whereKey($store->getKey())
            ->exists();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }
}
