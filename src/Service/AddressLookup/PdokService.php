<?php

namespace Tychovbh\Mvc\Service\AddressLookup;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

class PdokService
{
    /**
     * @param $zipcode
     * @param $house_number
     * @return mixed
     * @throws GuzzleException
     */
    public static function search(string $zipcode, int $house_number): array
    {
        $zipcode = str_replace(' ', '', $zipcode);

        $client = new Client();
        $res = $client->request('GET', 'https://geodata.nationaalgeoregister.nl/locatieserver/v3/free', [
            'query' => ['fq' => $zipcode, 'q' => $house_number]
        ]);

        $fullAddress = json_decode($res->getBody()->getContents(), 1);

        return [
            'street' => Arr::get($fullAddress, 'response.docs.0.straatnaam'),
            'house_number' => Arr::get($fullAddress, 'response.docs.0.huisnummer'),
            'zipcode' => Arr::get($fullAddress, 'response.docs.0.postcode'),
            'city' => Arr::get($fullAddress, 'response.docs.0.woonplaatsnaam'),
            'country_id' => 1
        ];
    }
}
