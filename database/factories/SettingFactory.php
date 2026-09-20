<?php

namespace Database\Factories;

use App\Enums\WebAppStatus;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'web_app_status' => WebAppStatus::Online,
            'domain_name' => fake()->domainName(),
            'copyright_by' => fake()->company(),
            'copyright_year' => (string) fake()->year(),
            'enquiry_email' => fake()->companyEmail(),
            'outgoing_mail_server' => 'smtp.'.fake()->domainName(),
            'smtp_port' => fake()->randomElement([25, 465, 587]),
            'reply_email' => fake()->companyEmail(),
            'reply_email_password' => fake()->password(),
        ];
    }
}
