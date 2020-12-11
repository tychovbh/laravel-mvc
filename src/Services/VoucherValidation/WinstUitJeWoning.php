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
        $data['verify'] = 1;
        $data['label'] = $voucher;

        return $this->request('get', $data);
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
        $data['webshop'] = 1;
        $data['label'] = $voucher;

        return $this->request('post', $data);
    }

    /**
     * Does request to service
     * @param string $method
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    private function request(string $method, array $params): array
    {
        $config = config('mvc-voucher-validation.providers.WinstUitJeWoning');
        $options = [];
        $multipart = [];

        if (!empty($params)) {
            if ($method === 'post') {
                $index = 0;
                foreach ($params as $key => $value) {
                    $data = [
                        'name' => $key,
                        'contents' => $key === 'receiptFile' ? fopen($value, 'r') : $value
                    ];
                    Arr::set($multipart, $index, $data);
                    $index++;
                }

                $options['multipart'] = $multipart;
            } else {
                $options['query'] = $params;
            }

        }

        $response = $this->client->request($method, $config['url'], $options);
        return json_decode($response->getBody(), true) ?? [];
    }
}

dus:

