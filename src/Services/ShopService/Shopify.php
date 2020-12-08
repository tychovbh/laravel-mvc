<?php


namespace Tychovbh\Mvc\Services\ShopService;


use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class Shopify implements ShopServiceInterface
{

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieves all products
     * @param array $params
     * @return array
     */
    public function products(array $params = []): array
    {
        $response = $this->request('get', 'products', '2020-10', $params);

        return array_map(function ($product) {
            return [
                'id' => Arr::get($product, 'id'),
                'title' => Arr::get($product, 'title'),
                'price' => Arr::get($product, 'variants.0.price')
            ];
        }, Arr::get($response, 'products'));
    }

    /**
     * Retrieves product by id
     * @param int $id
     * @return array
     */
    public function productById($id): array
    {
        $response = $this->request('get', 'products/' . $id, '2020-10');

        return [
            'id' => Arr::get($response, 'product.id'),
            'title' => Arr::get($response, 'product.title'),
            'price' => Arr::get($response, 'product.variants.0.price'),
        ];
    }

    /**
     * Retrieves all orders
     * @param array $params
     * @return array
     */
    public function orders(array $params = []): array
    {
        $response = $this->request('get', 'orders', '2020-10', $params);

        return array_map(function ($order) {
            return [
                'id' => Arr::get($order, 'id'),
                'email' => Arr::get($order, 'email'),
                'discount_codes' => array_map(function ($discount_code) {
                    return Arr::get($discount_code, 'code');
                }, Arr::get($order, 'discount_codes')),
                'closed_at' => Arr::get($order, 'closed_at')
            ];
        }, Arr::get($response, 'orders'));
    }

    /**
     * Retrieves order by id
     * @param int $id
     * @return array
     */
    public function orderById($id): array
    {
        $response = $this->request('get', 'orders/' . $id, '2020-10');

        return [
            'id' => Arr::get($response, 'order.id'),
            'email' => Arr::get($response, 'order.email'),
            'discount_codes' => array_map(function ($discount_code) {
                return Arr::get($discount_code, 'code');
            }, Arr::get($response, 'order.discount_codes')),
            'closed_at' => Arr::get($response, 'order.closed_at')
        ];
    }

    /**
     * @param array $params
     * @return array
     */
    public function customers(array $params = []): array
    {
        $response = $this->request('get', 'customers', '2020-10', $params);

        return array_map(function ($customer) {
            return [
                'id' => Arr::get($customer, 'id'),
                'email' => Arr::get($customer, 'email'),
                'first_name' => Arr::get($customer, 'first_name'),
                'last_name' => Arr::get($customer, 'last_name'),
                'address' => [
                    'company' => Arr::get($customer, 'addresses.0.company'),
                    'address1' => Arr::get($customer, 'addresses.0.address1'),
                    'address2' => Arr::get($customer, 'addresses.0.address2'),
                    'city' => Arr::get($customer, 'addresses.0.city'),
                    'zip' => Arr::get($customer, 'addresses.0.zip'),
                    'phone' => Arr::get($customer, 'addresses.0.phone'),
                    'country' => Arr::get($customer, 'addresses.0.country_code')
                ]
            ];
        }, Arr::get($response, 'customers'));
    }

    /**
     * Retrieves customer by id
     * @param int $id
     * @return array
     */
    public function customerById($id): array
    {
        $response = $this->request('get', 'customers/' . $id, '2020-10');

        return [
            'id' => Arr::get($response, 'customer.id'),
            'email' => Arr::get($response, 'customer.email'),
            'first_name' => Arr::get($response, 'customer.first_name'),
            'last_name' => Arr::get($response, 'customer.last_name'),
            'address' => [
                'company' => Arr::get($response, 'customer.addresses.0.company'),
                'address1' => Arr::get($response, 'customer.addresses.0.address1'),
                'address2' => Arr::get($response, 'customer.addresses.0.address2'),
                'city' => Arr::get($response, 'customer.addresses.0.city'),
                'zip' => Arr::get($response, 'customer.addresses.0.zip'),
                'phone' => Arr::get($response, 'customer.addresses.0.phone'),
                'country' => Arr::get($response, 'customer.addresses.0.country_code')
            ]
        ];
    }

    /**
     * Does request to service
     * @param string $method
     * @param string $resource
     * @param string $version
     * @param array $params
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request(string $method, string $resource, string $version, array $params = []): array
    {
        $config = config('mvc-shop.providers.Shopify');
        $url = 'https://' . $config['api_key'] . ':' . $config['password'] . '@' . $config['domain'] . '/admin/api/' . $version . '/' . $resource . '.json';
        $options = [];
        if (!empty($params)) {
            $options[$method === 'post' ? 'json' : 'query'] = $params;
        }
        $response = $this->client->request($method, $url, $options);
        return json_decode($response->getBody(), true) ?? [];
    }
}
