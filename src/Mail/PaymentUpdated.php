<?php

namespace Tychovbh\Mvc\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Tychovbh\Mvc\Payment;
use Tychovbh\Mvc\User;

class PaymentUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Payment
     */
    public $payment;

    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    public $template;

    /**
     * Create a new message instance.
     *
     * @param Payment $payment
     * @param string $template
     */
    public function __construct(Payment $payment, string $template = '')
    {
        $this->payment = $payment;
        $this->user = $payment->user ?? new User;
        $this->template = $template === '' ? $this->payment->status : $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $config = config('mvc-mail.messages.payment.' . $this->template);

        return $this
            ->to($this->user->email)
            ->view($config['template'])
            ->subject($config['subject'])
            ->from($config['from']);
    }
}
