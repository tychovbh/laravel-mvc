<?php

namespace Tychovbh\Mvc\Service\AddressLookup;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class PdokService
{
    /**
     * @param $address
     * @param $zipcode
     * @param $house_number
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function search($address, $zipcode, $house_number)
    {
        $zipcode = str_replace(' ', '', $zipcode);

        $client = new Client();
        $res = $client->request('GET', 'https://geodata.nationaalgeoregister.nl/locatieserver/v3/free', [
            'query' => ['fq' => $zipcode, 'q' => $house_number]
        ]);

        $fullAddress = json_decode($res->getBody()->getContents(), 1);

        $address->street = Arr::get($fullAddress, 'response.docs.0.straatnaam');
        $address->city = Arr::get($fullAddress, 'response.docs.0.woonplaatsnaam');
        $address->country = 'Nederland';

        return $address;
    }
}
