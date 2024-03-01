<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use App\Models\EmailLog;

class LogSentEmail
{
    public function handle(MessageSent $event)
    {
        $message = $event->message;

        // Initialize variables to store the recipient and body content
        $recipient = '';
        $bodyContent = '';

        // Getting the recipient(s)
        $recipients = [];
        foreach ($message->getTo() as $addressObject) {
            // Ensure $addressObject is converted to a string correctly
            $recipients[] = $addressObject->toString();
        }
        $recipient = implode(', ', $recipients);

        // Checking for HTML content as an example
        if ($htmlPart = $message->getHtmlBody()) {
            $bodyContent = $htmlPart;
        } elseif ($textPart = $message->getTextBody()) {
            $bodyContent = $textPart;
        }

        EmailLog::create([
            'recipient' => $recipient,
            'subject' => $message->getSubject(),
            'body' => $bodyContent,
        ]);
    }
}
