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
    public $phone;

    /**
     * @var Address
     */
    public $address;

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
        $this->phone = Arr::get($data, 'phone');
        $this->address = Arr::get($data, 'address', new Address);
    }
}
