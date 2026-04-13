<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
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
        'provider',
        'provider_id',
        'role',
        'email_verified_at',
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
        ];
    }

    /**
     * Get the applicant profile for the user.
     */
    public function applicantProfile(): HasOne
    {
        return $this->hasOne(ApplicantProfile::class);
    }

    /**
     * Get the applications for the user.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get applications reviewed by this user.
     */
    public function reviewedApplications(): HasMany
    {
        return $this->hasMany(Application::class, 'reviewer_id');
    }

    /**
     * Check if user can access Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['reviewer', 'approver', 'super_admin']);
    }

    /**
     * Check if user is an applicant.
     */
    public function isApplicant(): bool
    {
        return $this->role === 'applicant';
    }

    /**
     * Check if user is a reviewer.
     */
    public function isReviewer(): bool
    {
        return in_array($this->role, ['reviewer', 'approver', 'super_admin']);
    }

    /**
     * Check if user is an approver.
     */
    public function isApprover(): bool
    {
        return in_array($this->role, ['approver', 'super_admin']);
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }
}
