<?php

if (!function_exists('send_templated_email')) {
    /**
     * Send email based on template name and parameters.
     * Can be used globally in the project.
     *
     * @param mixed $to Email address string, array of addresses, User model, or User collection
     * @param string $templateName Name of the blade view inside resources/views/emails/
     * @param array $parameters Arguments to pass to the blade template view
     * @return bool
     */
    function send_templated_email($to, string $templateName, array $parameters = []): bool
    {
        return \App\Helpers\MailHelper::sendTemplateEmail($to, $templateName, $parameters);
    }
}
