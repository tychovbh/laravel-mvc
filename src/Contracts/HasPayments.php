<?php
namespace Tychovbh\Mvc\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Tychovbh\Mvc\Models\Payment;

trait HasPayments
{
    /**
     * The payments.
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
