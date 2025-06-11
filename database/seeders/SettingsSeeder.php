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
        //
        $settings = [
            [
                'configKey' => 'app_name',
                'configValue' => 'Procurement System',
                'name' => 'Application Name',
                'path' => 'general',
            ],
            [
                'configKey' => 'company_name',
                'configValue' => 'Your Company Name',
                'name' => 'Company Name',
                'path' => 'general',
            ],
            [
                'configKey' => 'company_address',
                'configValue' => 'Your Company Address',
                'name' => 'Company Address',
                'path' => 'general',
            ],
            [
                'configKey' => 'company_logo',
                'configValue' => 'default-logo.png',
                'name' => 'Company Logo',
                'path' => 'general',
            ],
            [
                'configKey' => 'company_email',
                'configValue' => 'info@yourcompany.com',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['configKey' => $setting['configKey']],
                $setting
            );
        }
    }
}
