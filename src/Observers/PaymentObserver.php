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
        if (!$payment->user_id) {
            $payment->user_id = user()->id;
        }
    }

    /**
     * @param Payment $payment
     */
    public function created(Payment $payment)
    {
        if (!$payment->external_id) {
            $payment->prepare();
        }
    }
}
