<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'owner_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($organization) {
            if (empty($organization->slug)) {
                $organization->slug = Str::slug($organization->name) . '-' . Str::random(6);
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function admins(): BelongsToMany
    {
        return $this->members()->wherePivot('role', 'admin');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function isMember(User $user): bool
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }

    public function isAdmin(User $user): bool
    {
        return $this->owner_id === $user->id ||
            $this->members()->wherePivot('user_id', $user->id)->wherePivot('role', 'admin')->exists();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
