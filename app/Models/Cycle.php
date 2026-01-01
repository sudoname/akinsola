<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Cycle extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'tracks_json',
        'start_at',
        'deadline_at',
        'results_release_at',
        'budget_total',
        'status',
        'form_schema_json',
        'manual_published_at',
    ];

    protected function casts(): array
    {
        return [
            'tracks_json' => 'array',
            'start_at' => 'datetime',
            'deadline_at' => 'datetime',
            'results_release_at' => 'datetime',
            'manual_published_at' => 'datetime',
            'budget_total' => 'decimal:2',
            'form_schema_json' => 'array',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($cycle) {
            if (empty($cycle->slug)) {
                $cycle->slug = Str::slug($cycle->title);
            }
        });
    }

    /**
     * Get the applications for this cycle.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Check if results are visible to applicants.
     * Results are visible if now >= results_release_at OR manual_published_at is set.
     */
    public function resultsAreVisible(): bool
    {
        return $this->manual_published_at !== null ||
               ($this->results_release_at && now()->gte($this->results_release_at));
    }

    /**
     * Check if cycle is currently accepting applications.
     */
    public function isAcceptingApplications(): bool
    {
        return $this->status === 'published' &&
               now()->gte($this->start_at) &&
               now()->lte($this->deadline_at);
    }

    /**
     * Check if deadline has passed.
     */
    public function hasDeadlinePassed(): bool
    {
        return now()->gt($this->deadline_at);
    }

    /**
     * Manually publish results early.
     */
    public function publishResultsNow(): void
    {
        $this->update(['manual_published_at' => now()]);
    }

    /**
     * Get approved applications for this cycle.
     */
    public function approvedApplications(): HasMany
    {
        return $this->applications()->where('status', 'approved');
    }
}
