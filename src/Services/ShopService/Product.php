<?php

namespace Tychovbh\Mvc\Services\ShopService;

use Illuminate\Support\Arr;

class Product
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var float
     */
    public $price;

    /**
     * Product constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->id = Arr::get($data, 'id');
        $this->title = Arr::get($data, 'title');
        $this->price = Arr::get($data, 'price');
    }
}
