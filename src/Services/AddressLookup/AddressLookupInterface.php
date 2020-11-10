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
    public static function search(string $zipcode, int $house_number): array;
}
