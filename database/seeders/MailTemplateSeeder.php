<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'welcome_email',
                'subject' => 'Welcome to TravelApp!',
                'content' => '<h1>Welcome {{first_name}}!</h1><p>We are glad to have you with us.</p>',
                'type' => 'System',
                'status' => 'active',
            ],
            [
                'name' => 'password_reset',
                'subject' => 'Reset Your Password',
                'content' => '<p>Hello,</p><p>You are receiving this email because we received a password reset request for your account.</p>',
                'type' => 'System',
                'status' => 'active',
            ],
            [
                'name' => 'booking_confirmation',
                'subject' => 'Booking Confirmation - {{booking_reference}}',
                'content' => '<h1>Your booking is confirmed!</h1><p>Thank you for choosing TravelApp.</p>',
                'type' => 'Booking',
                'status' => 'active',
            ],
        ];

        foreach ($templates as $template) {
            \App\Models\MailTemplate::updateOrCreate(
                ['name' => $template['name']],
                $template
            );
        }
    }
}
