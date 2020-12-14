<?php

namespace Tychovbh\Mvc\Services\ShopService;

interface ShopServiceInterface
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
}
