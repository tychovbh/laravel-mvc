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
    public function mapProduct(array $product): Product
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
    public function mapOrder(array $order): Order
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
            'customer' => $this->mapCustomer(Arr::get($order, 'customer')),
            'shipping' => $this->mapAddress(Arr::get($order, 'shipping_address')),
            'billing' => $this->mapAddress(Arr::get($order, 'billing_address')),
            'total' => Arr::get($order, 'total_price'),
            'total_vouchers' => Arr::get($order, 'total_discounts'),
            'total_tax' => Arr::get($order, 'total_tax'),
            'subtotal' => Arr::get($order, 'total_line_items_price'),
            'shippings' => array_map(function ($shipping) {
                return $this->mapShipping($shipping);
            }, Arr::get($order, 'shipping_lines')),
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
     * Maps the Address
     * @param array $address
     * @return Address
     */
    public function mapAddress(array $address): Address
    {
        return new Address([
            'email' => Arr::get($address, 'email'),
            'first_name' => Arr::get($address, 'first_name'),
            'last_name' => Arr::get($address, 'last_name'),
            'company' => Arr::get($address, 'company'),
            'address1' => Arr::get($address, 'address1'),
            'address2' => Arr::get($address, 'address2'),
            'city' => Arr::get($address, 'city'),
            'zip' => Arr::get($address, 'zip'),
            'phone' => Arr::get($address, 'phone'),
            'country' => Arr::get($address, 'country_code')
        ]);
    }

    /**
     * Maps the customer
     * @param array $customer
     * @return Customer
     */
    public function mapCustomer(array $customer): Customer
    {
        return new Customer([
            'id' => Arr::get($customer, 'id'),
            'email' => Arr::get($customer, 'email'),
            'first_name' => Arr::get($customer, 'first_name'),
            'last_name' => Arr::get($customer, 'last_name'),
            'company' => Arr::get($customer, 'company'),
            'phone' => Arr::get($customer, 'phone'),
            'address' => $this->mapAddress(Arr::get($customer, 'default_address', []))
        ]);
    }

    /**
     * Maps shipping
     * @param $shipping
     * @return Shipping
     */
    public function mapShipping($shipping): Shipping
    {
        return new Shipping([
            'title' => Arr::get($shipping, 'title'),
            'price' => Arr::get($shipping, 'price')
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
        return $this->mapCustomer($customer);
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
