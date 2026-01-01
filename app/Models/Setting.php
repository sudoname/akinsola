<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'value_json',
    ];

    protected function casts(): array
    {
        return [
            'value_json' => 'array',
        ];
    }

    /**
     * Get a setting value.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = self::find($key);
            return $setting ? $setting->value_json : $default;
        });
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, mixed $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value_json' => $value]
        );

        Cache::forget("setting.{$key}");
    }

    /**
     * Get scoring weights.
     */
    public static function getScoringWeights(): array
    {
        return self::get('scoring_weights', [
            'academic' => 0.40,
            'need' => 0.30,
            'service' => 0.15,
            'leadership' => 0.15,
        ]);
    }

    /**
     * Get file upload limits.
     */
    public static function getFileUploadLimits(): array
    {
        return self::get('file_upload_limits', [
            'max_size_mb' => 10,
            'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
        ]);
    }

    /**
     * Get reCAPTCHA settings.
     */
    public static function getRecaptchaSettings(): array
    {
        return self::get('recaptcha', [
            'enabled' => false,
            'site_key' => '',
            'secret_key' => '',
        ]);
    }

    /**
     * Get decision reason codes.
     */
    public static function getDecisionReasonCodes(): array
    {
        return self::get('decision_reason_codes', [
            'strong_academic_performance',
            'exceptional_financial_need',
            'outstanding_community_service',
            'demonstrated_leadership',
            'well_rounded_candidate',
            'insufficient_academic_performance',
            'incomplete_application',
            'limited_financial_need',
            'budget_constraints',
            'better_qualified_candidates',
            'missing_required_documents',
            'did_not_meet_eligibility_criteria',
        ]);
    }
}
