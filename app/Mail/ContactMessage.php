<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    /** @var array<string, string|null> */
    public $contact;

    /** @param array<string, string|null> $contact */
    public function __construct(array $contact)
    {
        $this->contact = $contact;
    }

    public function build(): self
    {
        return $this
            ->subject('【ナウポン】お問い合わせ')
            ->replyTo($this->contact['email'], $this->contact['name'])
            ->view('emails.contact-message');
    }
}
