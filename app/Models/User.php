<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function wishes(): HasMany
    {
        return $this->hasMany(Wish::class);
    }

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_participants')
            ->withPivot('registered_at')
            ->withTimestamps();
    }

    public function assignmentsAsGiver(): HasMany
    {
        return $this->hasMany(Assignment::class, 'giver_id');
    }

    public function assignmentsAsReceiver(): HasMany
    {
        return $this->hasMany(Assignment::class, 'receiver_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function received_comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'receiver_id');
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function ownedOrganizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'owner_id');
    }

    public function isParticipantInEvent(Event $event): bool
    {
        return $this->events()->where('event_id', $event->id)->exists();
    }

    public function isMemberOfOrganization(Organization $organization): bool
    {
        return $this->organizations()->where('organization_id', $organization->id)->exists();
    }

    public function isAdminOfOrganization(Organization $organization): bool
    {
        return $organization->owner_id === $this->id ||
            $this->organizations()
            ->wherePivot('organization_id', $organization->id)
            ->wherePivot('role', 'admin')
            ->exists();
    }
}
