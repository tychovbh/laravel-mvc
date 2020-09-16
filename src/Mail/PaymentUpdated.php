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
     * Create a new message instance.
     *
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->user = $payment->user ?? new User;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $config = config('mvc-mail.messages.payment.' . $this->payment->status);

        return $this
            ->to($this->user->email)
            ->view($config['template'])
            ->subject($config['subject'])
            ->from($config['from']);
    }
}
