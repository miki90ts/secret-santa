<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'year',
        'name',
        'slug',
        'description',
        'registration_start',
        'registration_end',
        'assignment_date',
        'exchange_date',
        'is_active',
        'assignments_made',
    ];

    protected $casts = [
        'year' => 'integer',
        'registration_start' => 'date',
        'registration_end' => 'date',
        'assignment_date' => 'date',
        'exchange_date' => 'datetime',
        'is_active' => 'boolean',
        'assignments_made' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $base = Str::slug($event->name ?? 'event-' . $event->year);
                $event->slug = $base . '-' . Str::random(6);
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

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
