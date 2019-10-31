<?php

namespace Tychovbh\Mvc\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserVerify extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $mail;

    /**
     * Create a new message instance.
     *
     * @param array $mail
     */
    public function __construct(array $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $config = config('mvc-mail.messages.user.verify');
        return $this
            ->to($this->mail['email'])
            ->view($config['template'])
            ->subject($config['subject'])
            ->from($config['from']);
    }
}
