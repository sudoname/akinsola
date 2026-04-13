<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'cycle_id',
        'track',
        'status',
        'submission_at',
        'reviewer_id',
        'score_academic',
        'score_need',
        'score_service',
        'score_leadership',
        'score_total',
        'decision_reason_code',
        'decision_note',
        'decision_set_at',
        'scholarship_amount',
        'awardee_photo',
        'awardee_profile',
        'bank_account_name',
        'bank_name',
        'bank_account_number',
        'bank_account_type',
        'payment_status',
        'payment_pending_at',
        'payment_verified_at',
        'payment_sent_at',
        'payment_received_at',
        'payment_note',
    ];

    protected function casts(): array
    {
        return [
            'submission_at' => 'datetime',
            'decision_set_at' => 'datetime',
            'score_academic' => 'decimal:2',
            'score_need' => 'decimal:2',
            'score_service' => 'decimal:2',
            'score_leadership' => 'decimal:2',
            'score_total' => 'decimal:2',
            'scholarship_amount' => 'decimal:2',
            'payment_pending_at' => 'datetime',
            'payment_verified_at' => 'datetime',
            'payment_sent_at' => 'datetime',
            'payment_received_at' => 'datetime',
        ];
    }

    /**
     * Get the user (applicant) for this application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the cycle for this application.
     */
    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    /**
     * Get the reviewer for this application.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the education record for this application.
     */
    public function educationRecord(): HasOne
    {
        return $this->hasOne(EducationRecord::class);
    }

    /**
     * Get the documents for this application.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Check if application is in draft status.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if application is submitted.
     */
    public function isSubmitted(): bool
    {
        return in_array($this->status, [
            'submitted',
            'under_review',
            'decision_pending_release',
            'approved',
            'rejected',
            'waitlisted'
        ]);
    }

    /**
     * Check if application is editable.
     */
    public function isEditable(): bool
    {
        return $this->status === 'draft' && !$this->cycle->hasDeadlinePassed();
    }

    /**
     * Check if decision is visible to applicant.
     */
    public function isDecisionVisible(): bool
    {
        return in_array($this->status, ['approved', 'rejected', 'waitlisted']) &&
               $this->cycle->resultsAreVisible();
    }

    /**
     * Get the visible status for applicant.
     */
    public function getVisibleStatus(): string
    {
        if ($this->status === 'decision_pending_release' && !$this->cycle->resultsAreVisible()) {
            return 'Decision pending release';
        }

        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'under_review' => 'Under Review',
            'decision_pending_release' => 'Decision Pending',
            'approved' => 'Approved',
            'rejected' => 'Not Selected',
            'waitlisted' => 'Waitlisted',
            default => 'Unknown',
        };
    }

    /**
     * Submit the application.
     */
    public function submit(): bool
    {
        if (!$this->isDraft() || $this->cycle->hasDeadlinePassed()) {
            return false;
        }

        return $this->update([
            'status' => 'submitted',
            'submission_at' => now(),
        ]);
    }

    /**
     * Calculate total score based on weighted components.
     */
    public function calculateTotalScore(array $weights = null): float
    {
        $weights = $weights ?? [
            'academic' => 0.40,
            'need' => 0.30,
            'service' => 0.15,
            'leadership' => 0.15,
        ];

        $total = ($this->score_academic * $weights['academic']) +
                 ($this->score_need * $weights['need']) +
                 ($this->score_service * $weights['service']) +
                 ($this->score_leadership * $weights['leadership']);

        return round($total, 2);
    }

    /**
     * Set decision for this application.
     */
    public function setDecision(string $decision, string $reasonCode = null, string $note = null): bool
    {
        if (!in_array($decision, ['approved', 'rejected', 'waitlisted'])) {
            return false;
        }

        return $this->update([
            'status' => 'decision_pending_release',
            'decision_reason_code' => $reasonCode,
            'decision_note' => $note,
            'decision_set_at' => now(),
        ]);
    }

    /**
     * Get the final decision (only if visible).
     */
    public function getFinalDecision(): ?string
    {
        if (!$this->isDecisionVisible()) {
            return null;
        }

        return $this->status;
    }
}
