<?php

namespace Tychovbh\Mvc\Services\AddressLookup;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

class PdokService implements AddressLookupInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $config;

    /**
     * PdokService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->config = config('mvc-address-lookup.providers.PdokService');
    }

    /**
     * @param string $zipcode
     * @param int $house_number
     * @return array
     * @throws GuzzleException
     */
    public function search(string $zipcode, int $house_number): array
    {
        if (!config('mvc-address-lookup.enabled')) {
            return [];
        }

        $zipcode = str_replace(' ', '', $zipcode);

        $client = new Client();
        $res = $client->request('GET', Arr::get($this->config, 'base_url'), [
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

    /**
     * Checks if service is enabled
     * @return bool
     */
    private function isEnabled(): bool
    {
        if (config('mvc-voucher-validation.enabled')) {
            return true;
        } else {
            return false;
        }
    }
}
