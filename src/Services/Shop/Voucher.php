<?php


namespace Tychovbh\Mvc\Services\Shop;


use Illuminate\Support\Arr;

class Voucher
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $amount;

    /**
     * @var string
     */
    public $type;


    /**
     * Voucher constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->code = Arr::get($data, 'code');
        $this->amount = Arr::get($data, 'amount');
        $this->type = Arr::get($data, 'type');
    }
}
