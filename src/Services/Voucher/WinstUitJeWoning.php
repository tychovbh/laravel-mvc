<?php


namespace Tychovbh\Mvc\Services\Voucher;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

class WinstUitJeWoning implements VoucherInterface
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
     * WinstUitJeWoning constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->config = config('mvc-voucher.providers.WinstUitJeWoning');
    }

    /**
     * Check if voucher is valid
     * @param string $voucher
     * @param array $data
     * @return array
     * @throws GuzzleException
     */
    public function validate(string $voucher, array $data): array
    {
        return $this->request('get', array_merge([
            'verify' => 1,
            'label' => $voucher
        ], $data));
    }

    /**
     * Uses the voucher
     * @param string $voucher
     * @param array $data
     * @return array
     * @throws GuzzleException
     */
    public function use(string $voucher, array $data = []): array
    {
        return $this->request('post', array_merge([
            'webshop' => 1,
            'label' => $voucher,
            'token' => $this->config['token'],
            'storeName' => $this->config['store']['name'],
            'storeId' => $this->config['store']['id'],
            'routeE' => 1,
            'optin' => 1,
            'agree' => 1,
        ], $data));
    }

    /**
     * Does request to service
     * @param string $method
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    private function request(string $method, array $params = []): array
    {
        $options = [];

        if ($method === 'post') {
            $multipart = [];
            foreach ($params as $key => $value) {
                $data = [
                    'name' => $key,
                    'contents' => $value
                ];
                $multipart[] = $data;
            }

            $options['multipart'] = $multipart;
        } else {
            $options['query'] = $params;
        }

        $response = $this->client->request($method, $this->config['url'], $options);
        return json_decode($response->getBody(), true) ?? [];
    }
}
