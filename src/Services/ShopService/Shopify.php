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

    /**
     * Shopify constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Maps the product
     * @param array $product
     * @return Product
     */
    private function mapProduct(array $product): Product
    {
        return new Product([
            'id' => Arr::get($product, 'id'),
            'title' => Arr::get($product, 'title'),
            'price' => Arr::get($product, 'variants.0.price')
        ]);
    }

    /**
     * Maps the order
     * @param array $order
     * @return Order
     */
    private function mapOrder(array $order): Order
    {
        return new Order([
            'id' => Arr::get($order, 'id'),
            'discount' => array_map(function ($discount_code) {
                return Arr::get($discount_code, 'code', '');
            }, Arr::get($order, 'discount_codes', [])),
            'closed_at' => Arr::get($order, 'closed_at'),
            'customer' => $this->mapCustomer(Arr::get($order, 'customer'))
        ]);
    }

    /**
     * Maps the customer
     * @param array $customer
     * @return Customer
     */
    private function mapCustomer(array $customer): Customer
    {
        return new Customer([
            'id' => Arr::get($customer, 'id'),
            'email' => Arr::get($customer, 'email'),
            'first_name' => Arr::get($customer, 'first_name'),
            'last_name' => Arr::get($customer, 'last_name'),
            'company' => Arr::get($customer, 'default_address.company'),
            'address1' => Arr::get($customer, 'default_address.address1'),
            'address2' => Arr::get($customer, 'addresses.address2'),
            'city' => Arr::get($customer, 'default_address.city'),
            'zip' => Arr::get($customer, 'default_address.zip'),
            'phone' => Arr::get($customer, 'default_address.phone'),
            'country' => Arr::get($customer, 'default_address.country_code')
        ]);
    }

    /**
     * Retrieves all products
     * @param array $params
     * @return array
     */
    public function products(array $params = []): array
    {
        $response = $this->request('get', 'products', $params);

        return array_map(function ($product) {
            return $this->mapProduct($product);
        }, Arr::get($response, 'products'));
    }

    /**
     * Retrieves product by id
     * @param $id
     * @return Product
     */
    public function product($id): Product
    {
        $response = $this->request('get', 'products/' . $id);

        return $this->mapProduct(Arr::get($response, 'product'));
    }

    /**
     * Retrieves all orders
     * @param array $params
     * @return array
     */
    public function orders(array $params = []): array
    {
        $response = $this->request('get', 'orders', $params);

        return array_map(function ($order) {
            return $this->mapOrder($order);
        }, Arr::get($response, 'orders'));
    }

    /**
     * Retrieves order by id
     * @param $id
     * @return Order
     */
    public function order($id): Order
    {
        $response = $this->request('get', 'orders/' . $id);

        return $this->mapOrder(Arr::get($response, 'order'));
    }

    /**
     * Retrieves all customers
     * @param array $params
     * @return array
     */
    public function customers(array $params = []): array
    {
        $response = $this->request('get', 'customers', $params);

        return array_map(function ($customer) {
            return $this->mapCustomer($customer);
        }, Arr::get($response, 'customers'));
    }

    /**
     * Retrieves customer by id
     * @param $id
     * @return Customer
     */
    public function customer($id): Customer
    {
        $response = $this->request('get', 'customers/' . $id);

        return $this->mapCustomer(Arr::get($response, 'customer'));
    }

    /**
     * Does request to service
     * @param string $method
     * @param string $resource
     * @param array $params
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function request(string $method, string $resource, array $params = []): array
    {
        $config = config('mvc-shop.providers.Shopify');
        $url = 'https://' . $config['api_key'] . ':' . $config['password'] . '@' . $config['domain'] . '/admin/api/' . $config['version'] . '/' . $resource . '.json';
        $options = [];
        if (!empty($params)) {
            $options[$method === 'post' ? 'json' : 'query'] = $params;
        }
        $response = $this->client->request($method, $url, $options);
        return json_decode($response->getBody(), true) ?? [];
    }
}
