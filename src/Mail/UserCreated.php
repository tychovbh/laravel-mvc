<?php

namespace Tychovbh\Mvc\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    public $email;

    /**
     * Create a new message instance.
     *
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $config = config('mvc-mail.templates.user.store');
        return $this
            ->to($config['to'])
            ->from($config['from'])
            ->subject($config['subject'])
            ->view($config['template']);
    }
}
