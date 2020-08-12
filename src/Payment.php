<?php

namespace Tychovbh\Mvc;

use Mollie\Laravel\Facades\Mollie;

class Payment extends Model
{
    const STATUS_OPEN = 'open';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    const STATUSES = [self::STATUS_OPEN, self::STATUS_PAID, self::STATUS_FAILED, self::STATUS_CANCELLED, self::STATUS_EXPIRED];


    /**
     * @var array
     */
    protected $fillable = ['amount', 'description', 'status', 'user_id'];

    /**
     * The Payment Url
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return $this->external->getCheckoutUrl();
    }

    /**
     * Prepare Payment
     */
    public function prepare()
    {
        $this->attributes['status'] = Payment::STATUS_OPEN;
        $price = (string) $this->amount;

        $external = Mollie::api()->payments->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => $price
            ],
            'description' => $this->description,
            'redirectUrl' => 'http://local.eyecons.com/payment/success',
//            'webhookUrl' => route('webhooks.mollie'),
//            'metadata' => [
//                'order_id' => '12345',
//            ],
        ]);

        $this->attributes['external_id'] = $external->id;
    }

    public function check()
    {
        $external = $this->external;

        return $external->status;
    }

    public function getExternalAttribute()
    {
        return Mollie::api()->payments->get($this->external_id);
    }
}
