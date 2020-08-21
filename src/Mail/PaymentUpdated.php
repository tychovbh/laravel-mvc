<?php

namespace Tychovbh\Mvc\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Tychovbh\Mvc\Payment;

class PaymentUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Payment
     */
    public $payment;

    /**
     * Create a new message instance.
     *
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $config = config('mvc-mail.messages.payment' . $this->payment->status);
        return $this
            ->to($this->payment->user->email)
            ->view($config['template'])
            ->subject($config['subject'])
            ->from($config['from']);
    }
}
