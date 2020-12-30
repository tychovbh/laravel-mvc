<?php


namespace Tychovbh\Mvc\Services\Shop;


use Illuminate\Support\Arr;

class Shipping
{
    /**
     * @var string
     */
    public $title;


    /**
     * @var string
     */
    public $price;

    /**
     * Shipping constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->title = Arr::get($data, 'title');
        $this->price = Arr::get($data, 'price');
    }
}
