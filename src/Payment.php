<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Mollie\Laravel\Facades\Mollie;
use Mollie\Api\Resources\Payment as External;
use Mollie\Api\Types\PaymentStatus;
use Tychovbh\Mvc\Mail\PaymentUpdated;
use Tychovbh\Mvc\Repositories\PaymentRepository;
use Tychovbh\Mvc\Repositories\ProductRepository;

class Payment extends Model
{
    const STATUS_OPEN = PaymentStatus::STATUS_OPEN;
    const STATUS_PENDING = PaymentStatus::STATUS_PENDING;
    const STATUS_PAID = PaymentStatus::STATUS_PAID;
    const STATUS_FAILED = PaymentStatus::STATUS_FAILED;
    const STATUS_CANCELLED = PaymentStatus::STATUS_CANCELED;
    const STATUS_EXPIRED = PaymentStatus::STATUS_EXPIRED;

    const STATUSES = [
        self::STATUS_OPEN,
        self::STATUS_PENDING,
        self::STATUS_PAID,
        self::STATUS_FAILED,
        self::STATUS_CANCELLED,
        self::STATUS_EXPIRED
    ];

    /**
     * Payment constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('amount', 'description', 'status', 'options', 'products', 'external_id');
        $this->casts(['products' => 'array']);
        parent::__construct($attributes);
    }

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
                'value' => (string)number_format($this->amount, 2)
            ],
            'description' => $this->description,
            'redirectUrl' => route('payments.success', ['id' => $this->id]),
//            'webhookUrl' => route('webhooks.mollie'), // TODO add webhook
            'metadata' => [
                'payment_id' => $this->id,
            ],
        ]);

        $this->update([
            'status' => Payment::STATUS_OPEN,
            'external_id' => $external->id
        ]);
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

        if (!$update->updated_at || !$update->updated_at->ne($this->updated_at)) {
            return $update;
        }

        if (config('mvc-payments.broadcasting.enabled')) {
            $event = config('mvc-payments.broadcasting.event');
            event(new $event($update));
        }

        $config = config('mvc-mail.messages.payment');

        if ($external->isPaid() && Arr::get($config, 'paid.enabled', false)) {
            Mail::send(new PaymentUpdated($update));
        }

        if ($external->isExpired() && Arr::get($config, 'expired.enabled', false)) {
            Mail::send(new PaymentUpdated($update));
        }

        if ($external->isCanceled() && Arr::get($config, 'cancelled.enabled', false)) {
            Mail::send(new PaymentUpdated($update));
        }

        if ($external->isFailed() && Arr::get($config, 'failed.enabled', false)) {
            Mail::send(new PaymentUpdated($update));
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
     * The Products
     * @return Collection
     * @throws \Exception
     */
    public function getProductsAttribute(): Collection
    {
        $products = $this->products_raw;

        if (!$products) {
            return new Collection;
        }

        $ids = collect($products)->map(function (array $product) {
            return $product['id'];
        })->toArray();

        $collection = ProductRepository::withParams(['id' => $ids])->get();

        foreach ($collection as $product) {
            $key = array_search($product->id, array_column($products, 'id'));
            $options = $products[$key];
            Arr::forget($options, 'id');
            $product->options = array_merge($product->options ?? [], $options);
        }

        return $collection;
    }

    /**
     * The Raw Products
     * @return array
     */
    public function getProductsRawAttribute(): array
    {
        return json_decode($this->attributes['products'], true) ?? [];
    }
}
