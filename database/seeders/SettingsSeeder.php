<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            'scoring_weights' => [
                'academic' => 0.40,
                'need' => 0.30,
                'service' => 0.15,
                'leadership' => 0.15,
            ],
            'file_upload_limits' => [
                'max_size_mb' => 10,
                'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
            ],
            'recaptcha' => [
                'enabled' => false,
                'site_key' => '',
                'secret_key' => '',
            ],
            'site_info' => [
                'organization_name' => 'Isan-Ekiti Indigene Scholarship Program',
                'contact_email' => 'info@isan-ekiti.ng',
                'contact_phone' => '+234-XXX-XXX-XXXX',
                'address' => 'Isan-Ekiti, Ekiti State, Nigeria',
            ],
            'decision_reason_codes' => [
                'high_academic_performance',
                'demonstrated_financial_need',
                'strong_community_service',
                'leadership_qualities',
                'incomplete_documentation',
                'did_not_meet_criteria',
                'budget_constraints',
                'other',
            ],
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value_json' => $value]
            );
        }

        $this->command->info('✅ Default settings created!');
    }
}
