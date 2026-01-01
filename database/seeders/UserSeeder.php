<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::firstOrCreate(
            ['email' => 'admin@isan-ekiti.ng'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'email_verified_at' => now(),
            ]
        );

        // Create sample Reviewer
        User::firstOrCreate(
            ['email' => 'reviewer@isan-ekiti.ng'],
            [
                'name' => 'Reviewer User',
                'password' => Hash::make('password'),
                'role' => 'reviewer',
                'email_verified_at' => now(),
            ]
        );

        // Create sample Approver
        User::firstOrCreate(
            ['email' => 'approver@isan-ekiti.ng'],
            [
                'name' => 'Approver User',
                'password' => Hash::make('password'),
                'role' => 'approver',
                'email_verified_at' => now(),
            ]
        );

        // Create sample Applicant
        User::firstOrCreate(
            ['email' => 'applicant@example.com'],
            [
                'name' => 'Test Applicant',
                'password' => Hash::make('password'),
                'role' => 'applicant',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Default users created!');
        $this->command->info('Super Admin: admin@isan-ekiti.ng / password');
        $this->command->info('Reviewer: reviewer@isan-ekiti.ng / password');
        $this->command->info('Approver: approver@isan-ekiti.ng / password');
        $this->command->info('Applicant: applicant@example.com / password');
    }
}
