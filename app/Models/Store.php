<?php

namespace App\Models;

use App\Enums\StoreRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'city',
        'type',
        'api_base_url',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function owners(): BelongsToMany
    {
        return $this->users()
            ->wherePivot('role', StoreRole::OWNER->value);
    }

    public function employees(): BelongsToMany
    {
        return $this->users()
            ->wherePivot('role', StoreRole::EMPLOYEE->value);
    }
}