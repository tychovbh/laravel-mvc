<?php


namespace Tychovbh\Mvc\Services\Shop;


use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class Shopify implements ShopInterface
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
            'vouchers' => array_map(function ($discount_code) {
                return new Voucher($discount_code);
            }, Arr::get($order, 'discount_codes', [])),
            'closed_at' => Arr::get($order, 'closed_at'),
            'created_at' => Arr::get($order, 'created_at'),
            'updated_at' => Arr::get($order, 'updated_at'),
            'ipaddress' => Arr::get($order, 'browser_ip'),
            'customer' => $this->mapCustomer(array_merge(
                array_filter(Arr::get($order, 'customer')),
                array_filter(Arr::get($order, 'shipping_address'))
            )),
            'total' => Arr::get($order, 'total_price'),
            'total_vouchers' => Arr::get($order, 'total_discounts'),
            'total_tax' => Arr::get($order, 'total_tax'),
            'subtotal' => Arr::get($order, 'total_line_items_price'),
            'name' => Arr::get($order, 'name'),
            'invoice' => Arr::get($order, 'order_number'),
            'products' => array_map(function ($product) {
                return new Product([
                    'id' => Arr::get($product, 'product_id'),
                    'title' => Arr::get($product, 'title'),
                    'price' => Arr::get($product, 'price'),
                    'quantity' => Arr::get($product, 'quantity')
                ]);
            }, Arr::get($order, 'line_items'))
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
            'company' => Arr::get($customer, 'company'),
            'address1' => Arr::get($customer, 'address1'),
            'address2' => Arr::get($customer, 'address2'),
            'city' => Arr::get($customer, 'city'),
            'zip' => Arr::get($customer, 'zip'),
            'phone' => Arr::get($customer, 'phone'),
            'country' => Arr::get($customer, 'country_code')
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
        $customer = Arr::get($response, 'customer');
        return $this->mapCustomer(array_merge(Arr::get($customer, 'default_address', []), $customer));
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
