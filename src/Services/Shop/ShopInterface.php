<?php

namespace Tychovbh\Mvc\Services\Shop;

interface ShopInterface
{
    /**
     * Retrieves all products
     * @param array $params
     * @return array
     */
    public function products(array $params = []): array;

    /**
     * Retrieves product by id
     * @param int $id
     * @return Product
     */
    public function product($id): Product;

    /**
     * Retrieves all orders
     * @param array $params
     * @return array
     */
    public function orders(array $params = []): array;

    /**
     * Retrieves order by id
     * @param int $id
     * @return Order
     */
    public function order($id): Order;

    /**
     * @param array $params
     * @return array
     */
    public function customers(array $params = []): array;

    /**
     * Retrieves customer by id
     * @param int $id
     * @return Customer
     */
    public function customer($id): Customer;

    /**
     * Maps the product
     * @param array $product
     * @return Product
     */
    public function mapProduct(array $product): Product;

    /**
     * Maps the order
     * @param array $order
     * @return Order
     */
    public function mapOrder(array $order): Order;

    /**
     * Maps the customer
     * @param array $customer
     * @return Customer
     */
    public function mapCustomer(array $customer): Customer;

    /**
     * Maps shipping
     * @param $shipping
     * @return Shipping
     */
    public function mapShipping($shipping): Shipping;


    /**
     * Maps the Address
     * @param array $address
     * @return Address
     */
    public function mapAddress(array $address): Address;

    /**
     * @param array $data
     * @return array
     */
    public function storeDiscount(array $data): array;
}
