<?php

namespace Tychovbh\Mvc\Services\Shop;

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
    public $closed_at;

    /**
     * @var string
     */
    public $created_at;

    /**
     * @var string
     */
    public $updated_at;

    /**
     * @var Customer
     */
    public $customer;

    /**
     * @var array
     */
    public $vouchers;

    /**
     * @var string
     */
    public $ipaddress;

    /**
     * @var string
     */
    public $total;

    /**
     * @var string
     */
    public $total_vouchers;

    /**
     * @var string
     */
    public $total_tax;

    /**
     * @var string
     */
    public $subtotal;

    /**
     * @var array
     */
    public $shippings;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $invoice;

    /**
     * @var array
     */
    public $products;

    /**
     * @var Address
     */
    public $shipping;

    /**
     * @var Address
     */
    public $billing;

    /**
     * @var bool
     */
    public $optin;

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
     */
    public function fill(array $data = [])
    {
        $this->id = Arr::get($data, 'id');
        $this->vouchers = Arr::get($data, 'vouchers', []);
        $this->closed_at = Arr::get($data, 'closed_at', '');
        $this->created_at = Arr::get($data, 'created_at', '');
        $this->updated_at = Arr::get($data, 'created_at', '');
        $this->customer = Arr::get($data, 'customer', new Customer);
        $this->ipaddress = Arr::get($data, 'ipaddress', '');
        $this->total = Arr::get($data, 'total', '');
        $this->total_vouchers = Arr::get($data, 'total_vouchers', '');
        $this->total_tax = Arr::get($data, 'total_tax', '');
        $this->subtotal = Arr::get($data, 'subtotal', '');
        $this->shippings = Arr::get($data, 'shippings');
        $this->name = Arr::get($data, 'name', '');
        $this->invoice = Arr::get($data, 'invoice', '');
        $this->products = Arr::get($data, 'products', '');
        $this->shipping = Arr::get($data, 'shipping', new Address);
        $this->billing = Arr::get($data, 'billing', new Address);
        $this->optin = Arr::get($data, 'optin', false);
    }
}
