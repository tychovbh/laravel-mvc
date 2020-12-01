<?php

namespace Tychovbh\Mvc\Observers;

use Tychovbh\Mvc\Models\Payment;

class PaymentObserver
{
    /**
     * @param Payment $payment
     */
    public function creating(Payment $payment)
    {
        $payment->user_id = user()->id;
    }

    /**
     * @param Payment $payment
     */
    public function created(Payment $payment)
    {
        $payment->prepare();
    }
}
