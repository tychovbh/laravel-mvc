<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Models\Payment;

class PaymentRepository extends AbstractRepository implements Repository
{
    /**
     * Overwrite save to check for existing payments
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    public function save(array $data): mixed
    {
        $existing = $this::withParams([
            'products' => Arr::has($data, 'products') ? json_encode($data['products']) : null,
            'options' => Arr::has($data, 'options') ? json_encode($data['options']) : null,
            'status' => [Payment::STATUS_OPEN, Payment::STATUS_PENDING],
            'user_id' => user()->id
        ])->get()->first();

        if ($existing) {
            return $existing;
        }

        return parent::save($data);
    }
}
