<?php

namespace Tychovbh\Mvc\Services\ShopService;

use Illuminate\Support\Arr;

class Order
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $discount;

    /**
     * @var string
     */
    public $closed_at;

    /**
     * Order constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->id = Arr::get($data, 'id');
        $this->email = Arr::get($data, 'email');
        $this->discount = Arr::get($data, 'discount');
        $this->closed_at = Arr::get($data, 'closed_at');
    }
}
