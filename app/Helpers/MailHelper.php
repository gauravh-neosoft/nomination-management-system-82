<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\TemplatedMail;

class MailHelper
{
    /**
     * Get active admin and super admin emails from database.
     * Fallback to config/env if none are found.
     *
     * @return array
     */
    public static function getAdminEmails(): array
    {
        try {
            $emails = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->whereIn('roles.name', ['admin', 'super_admin'])
                ->where('users.status', 1)
                ->pluck('users.email')
                ->toArray();

            if (empty($emails)) {
                $fallback = env('ADMIN_NOTIFICATION_EMAIL', 'admin@example.com');
                $emails = array_filter(array_map('trim', explode(',', $fallback)));
            }

            return $emails;
        } catch (\Exception $e) {
            Log::error("Error fetching admin emails: " . $e->getMessage());
            $fallback = env('ADMIN_NOTIFICATION_EMAIL', 'admin@example.com');
            return array_filter(array_map('trim', explode(',', $fallback)));
        }
    }

    /**
     * Send email based on template name and parameters.
     *
     * @param mixed $to Email address string, array of addresses, User model, or User collection
     * @param string $templateName Name of the blade view inside resources/views/emails/
     * @param array $parameters Arguments to pass to the blade template view
     * @return bool
     */
    public static function sendTemplateEmail($to, string $templateName, array $parameters = []): bool
    {
        try {
            $subject = $parameters['subject'] ?? ucwords(str_replace('_', ' ', $templateName));
            
            Mail::to($to)->send(new TemplatedMail($templateName, $parameters, $subject));
            
            Log::info("Templated email '{$templateName}' sent successfully to: " . json_encode($to));
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send email to " . json_encode($to) . " using template '{$templateName}'. Error: " . $e->getMessage());
            return false;
        }
    }
}
