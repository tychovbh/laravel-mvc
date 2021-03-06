<?php

namespace Tychovbh\Mvc\Services\Shop;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Services\ServiceModelInterface;

class Product implements ServiceModelInterface
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
     * @var int
     */
    public $quantity;

    /**
     * Product constructor.
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
        $this->title = Arr::get($data, 'title');
        $this->price = Arr::get($data, 'price');
        $this->quantity = Arr::get($data, 'quantity');
    }
}
