<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'name',
        'description',
        'registration_start',
        'registration_end',
        'assignment_date',
        'is_active',
        'assignments_made',
    ];

    protected $casts = [
        'year' => 'integer',
        'registration_start' => 'date',
        'registration_end' => 'date',
        'assignment_date' => 'date',
        'is_active' => 'boolean',
        'assignments_made' => 'boolean',
    ];

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_participants')
            ->withPivot('registered_at')
            ->withTimestamps();
    }

    public function wishes(): HasMany
    {
        return $this->hasMany(Wish::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function isRegistrationOpen(): bool
    {
        if (!$this->registration_start || !$this->registration_end) {
            return false;
        }

        $now = now();
        return $now->between($this->registration_start, $this->registration_end);
    }

    public function canMakeAssignments(): bool
    {
        return !$this->assignments_made && $this->participants()->count() >= 2;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
