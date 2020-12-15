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
        if (!$this->isEnabled()) {
            return [
                'Service is not enabled'
            ];
        }

        array_merge([
            'verify' => 1,
            'label' => $voucher
        ], $data);

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
        if (!$this->isEnabled()) {
            return [
                'Service is not enabled'
            ];
        }

        array_merge([
            'webshop' => 1,
            'label' => $voucher
        ], $data);

        return $this->request('post', $data);
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
        $multipart = [];

        if (!empty($params)) {
            if ($method === 'post') {
                $index = 0;
                foreach ($params as $key => $value) {
                    $data = [
                        'name' => $key,
                        'contents' => $value
                    ];
                    $multipart[] = $data;
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

    /**
     * Checks if service is enabled
     * @return bool
     */
    private function isEnabled(): bool
    {
        if (config('mvc-voucher-validation.enabled') === true) {
            return true;
        } else {
            return false;
        }
    }
}
