<?php


namespace Tychovbh\Mvc\Services\VoucherValidation;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

class WinstUitJeWoning implements VoucherValidationInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * WinstUitJeWoning constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
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
            'label' => $voucher
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
        $config = config('mvc-voucher-validation.providers.WinstUitJeWoning');
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

        $response = $this->client->request($method, $config['url'], $options);
        return json_decode($response->getBody(), true) ?? [];
    }
}
