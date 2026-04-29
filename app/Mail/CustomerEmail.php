<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Generic email sent directly to a customer from the admin panel.
 * Used when an admin manually sends an order confirmation, update, or
 * any custom message from the Inbox → Send Email feature.
 */
class CustomerEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $emailSubject;
    public string $emailBody;
    public string $senderName;

    public function __construct(string $subject, string $body, string $senderName = '')
    {
        $this->emailSubject = $subject;
        $this->emailBody    = $body;
        $this->senderName   = $senderName ?: getOption('app_name', config('app.name'));
    }

    public function build(): static
    {
        return $this
            ->from(
                getOption('MAIL_FROM_ADDRESS', config('mail.from.address')),
                $this->senderName
            )
            ->subject($this->emailSubject)
            ->view('mail.customer-email')
            ->with([
                'body'       => $this->emailBody,
                'appName'    => $this->senderName,
                'appUrl'     => config('app.url'),
            ]);
    }
}
