<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantProfile extends Model
{
    protected $fillable = [
        'user_id',
        'dob',
        'phone',
        'address',
        'state',
        'lga',
        'ward',
        'next_of_kin_name',
        'next_of_kin_phone',
        'indigene_proof_url',
        'indigene_issuer',
        'indigene_issue_date',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'indigene_issue_date' => 'date',
        ];
    }

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if profile is complete.
     */
    public function isComplete(): bool
    {
        return !empty($this->dob) &&
               !empty($this->phone) &&
               !empty($this->address) &&
               !empty($this->state) &&
               !empty($this->lga) &&
               !empty($this->indigene_proof_url);
    }
}
