<?php

namespace Tychovbh\Tests\Mvc\feature\services\ShopService;

use GuzzleHttp\Client;
use Tychovbh\Mvc\Services\ShopService\Shopify;
use Tychovbh\Tests\Mvc\TestCase;

class ShopifyTest extends TestCase
{
    private function shopifyService()
    {
        $guzzle = new Client();
        return new Shopify($guzzle);
    }

    /**
     * @test
     */
    public function itCanIndexProducts()
    {
        $amount = 2;
        $products = $this->shopifyService()->products(['limit' => $amount]);

        $this->assertTrue(count($products) === $amount);

        return $products;
    }

    /**
     * @test
     * @depends itCanIndexProducts
     * @param array $products
     */
    public function itCanShowProduct(array $products)
    {
        $id = $products[0]->id;
        $product = $this->shopifyService()->product($id);
        $this->assertTrue($product->id === $id);
    }

    /**
     * @test
     */
    public function itCanIndexOrders()
    {
        $amount = 2;
        $orders = $this->shopifyService()->orders(['limit' => $amount]);
        $this->assertTrue(count($orders) === $amount);

        return $orders;
    }

    /**
     * @test
     */
    public function itCanIndexOrdersWithParams()
    {
        $amount = 2;
        $orders = $this->shopifyService()->orders(['limit' => $amount, 'status' => 'closed']);

        $this->assertTrue(count($orders) === $amount);

        foreach ($orders as $order) {
            $this->assertNotNull($order->closed_at);
        }
    }

    /**
     * @test
     * @depends itCanIndexOrders
     * @param array $orders
     */
    public function itCanShowOrder(array $orders)
    {
        $id = $orders[0]->id;
        $order = $this->shopifyService()->order($id);
        $this->assertTrue($order->id === $id);
    }

    /**
     * @test
     */
    public function itCanIndexCustomers()
    {
        $amount = 2;
        $customers = $this->shopifyService()->customers(['limit' => $amount]);
        $this->assertTrue(count($customers) === $amount);

        return $customers;
    }

    /**
     * @test
     * @depends itCanIndexCustomers
     * @param array $customers
     */
    public function itCanShowCustomer(array $customers)
    {
        $id = $customers[0]->id;
        $customer = $this->shopifyService()->customer($id);
        $this->assertTrue($customer->id === $id);
    }
}
