<?php

namespace Tychovbh\Mvc\Observers;

use Tychovbh\Mvc\Payment;

class PaymentObserver
{
    /**
     * @param Payment $payment
     */
    public function creating(Payment $payment)
    {
        $payment->prepare();
    }
}
