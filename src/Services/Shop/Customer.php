<?php

namespace Tychovbh\Mvc\Services\Shop;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Services\ServiceModelInterface;

class Customer implements ServiceModelInterface
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
    public $first_name;

    /**
     * @var string
     */
    public $last_name;

    /**
     * @var string
     */
    public $company;

    /**
     * @var string
     */
    public $address1;

    /**
     * @var string
     */
    public $address2;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $zip;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $country;

    /**
     * Customer constructor.
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
        $this->email = Arr::get($data, 'email');
        $this->first_name = Arr::get($data, 'first_name');
        $this->last_name = Arr::get($data, 'last_name');
        $this->company = Arr::get($data, 'company');
        $this->address1 = Arr::get($data, 'address1');
        $this->address2 = Arr::get($data, 'address2');
        $this->city = Arr::get($data, 'city');
        $this->zip = Arr::get($data, 'zip');
        $this->phone = Arr::get($data, 'phone');
        $this->country = Arr::get($data, 'country');
    }
}
