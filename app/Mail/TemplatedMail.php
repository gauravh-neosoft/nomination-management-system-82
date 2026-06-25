<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TemplatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $template;
    public $parameters;
    public $subjectStr;

    /**
     * Create a new message instance.
     *
     * @param string $template
     * @param array $parameters
     * @param string $subjectStr
     */
    public function __construct(string $template, array $parameters, string $subjectStr)
    {
        $this->template = $template;
        $this->parameters = $parameters;
        $this->subjectStr = $subjectStr;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectStr,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.' . $this->template,
            with: $this->parameters,
        );
    }
}
