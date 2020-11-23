<?php


namespace Tychovbh\Mvc\Services\AddressLookup;


use GuzzleHttp\Exception\GuzzleException;

interface AddressLookupInterface
{
    /**
     * @param $zipcode
     * @param $house_number
     * @return mixed
     * @throws GuzzleException
     */
    public function search(string $zipcode, int $house_number): array;
}
