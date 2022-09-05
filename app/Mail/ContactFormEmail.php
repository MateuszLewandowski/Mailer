<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        protected string $name,
        protected string $phone,
        protected string $email,
        protected string $text,
        protected array $approvals,
    ) {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact-form')
            ->with([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'text' => $this->text,
                'approvals' => $this->approvals,
            ]);
    }
}
