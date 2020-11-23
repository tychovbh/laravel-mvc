<?php

namespace Tychovbh\Mvc\Services\AddressLookup;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

class PdokService implements AddressLookupInterface
{
    /**
     * @param string $zipcode
     * @param int $house_number
     * @return array
     * @throws GuzzleException
     */
    public static function search(string $zipcode, int $house_number): array
    {
        $zipcode = str_replace(' ', '', $zipcode);

        $client = new Client();
        $res = $client->request('GET', config('mvc-address-lookup.providers.PdokService.base-url'), [
            'query' => ['fq' => $zipcode, 'q' => $house_number]
        ]);

        $fullAddress = json_decode($res->getBody()->getContents(), 1);

        return [
            'street' => Arr::get($fullAddress, 'response.docs.0.straatnaam'),
            'house_number' => Arr::get($fullAddress, 'response.docs.0.huisnummer'),
            'zipcode' => Arr::get($fullAddress, 'response.docs.0.postcode'),
            'city' => Arr::get($fullAddress, 'response.docs.0.woonplaatsnaam')
        ];
    }
}
