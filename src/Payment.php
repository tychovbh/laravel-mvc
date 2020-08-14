<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Mollie\Laravel\Facades\Mollie;
use Mollie\Api\Resources\Payment as External;
use Tychovbh\Mvc\Events\PaymentUpdated;
use Tychovbh\Mvc\Repositories\PaymentRepository;

class Payment extends Model
{
    const STATUS_OPEN = 'open';
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_PENDING,
        self::STATUS_PAID,
        self::STATUS_FAILED,
        self::STATUS_CANCELLED,
        self::STATUS_EXPIRED
    ];

    /**
     * @var array
     */
    protected $fillable = ['amount', 'description', 'status', 'options', 'user_id'];

    /**
     * @var array
     */
    protected $casts = [
        'options' => 'array'
    ];

    /**
     * The User
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The Payment Url
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return $this->external->getCheckoutUrl() ?? '';
    }

    /**
     * Prepare Payment
     */
    public function prepare()
    {
        $external = Mollie::api()->payments->create([
            'amount' => [
                'currency' => 'EUR',
                'value' => (string)$this->amount
            ],
            'description' => $this->description,
            'redirectUrl' => route('payments.success', ['id' => $this->id]),
//            'webhookUrl' => route('webhooks.mollie'), // TODO add webhook
            'metadata' => [
                'payment_id' => $this->id,
            ],
        ]);

        $this->external_id = $external->id;
        $this->status = Payment::STATUS_OPEN;
        $this->save();
    }

    /**
     * Check Payment Status
     * @return Payment
     */
    public function check(): Payment
    {
        $payments = new PaymentRepository;
        $external = $this->external;
        /* @var External $external */

        if (!in_array($external->status, self::STATUSES)) {
            error('Unknown Payment Status', [
                'id' => $this->id,
                'status' => $external->status
            ]);

            return $this;
        }

        $update = $payments->update([
            'status' => $external->status
        ], $this->id);

        if (
            config('mvc-payments.broadcasting.enabled') &&
            $update->updated_at && $update->updated_at->ne($this->updated_at)
        ) {
            $event = config('mvc-payments.broadcasting.event');
            event(new $event($update));
        }

        if ($external->isPaid()) {
            // TODO send notification
        }

        if ($external->isExpired()) {
            // TODO send notification
        }

        if ($external->isCanceled()) {
            // TODO send notification
        }

        if ($external->isFailed()) {
            // TODO send notification
        }

        return $update;
    }

    /**
     * The External Payment
     * @return External
     */
    public function getExternalAttribute(): External
    {
        return Mollie::api()->payments->get($this->external_id);
    }

    /**
     * Get value from Options
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function option(string $key, $default = null)
    {
        return Arr::get($this->options, $key, $default);
    }
}
