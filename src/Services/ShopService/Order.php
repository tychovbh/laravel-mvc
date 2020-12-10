<?php

namespace Tychovbh\Mvc\Services\ShopService;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Services\ServiceModelInterface;

class Order implements ServiceModelInterface
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
     * @var Customer
     */
    public $customer;

    /**
     * Order constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->fill($data);
    }

    /**
     * Fills the model
     * @param array $data
     * @return array
     */
    public function fill(array $data = [])
    {
        $this->id = Arr::get($data, 'id');
        $this->email = Arr::get($data, 'email');
        $this->discount = Arr::get($data, 'discount');
        $this->closed_at = Arr::get($data, 'closed_at');
        $this->customer = Arr::get($data, 'customer', new Customer());
    }
}
