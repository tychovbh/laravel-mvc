<?php


namespace Tychovbh\Tests\Mvc\feature\services\ShopService;


use GuzzleHttp\Client;
use Illuminate\Support\Arr;
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
        $products = $this->shopifyService()->products();
        $this->assertTrue(Arr::has($products, '0.id'));
    }

    /**
     * @test
     */
    public function itCanShowProduct()
    {
        $id = 5979585118361;
        $product = $this->shopifyService()->productById($id);
        $this->assertTrue(Arr::get($product, 'id') === $id);
    }

    /**
     * @test
     */
    public function itCanIndexOrders()
    {
        $orders = $this->shopifyService()->orders(['status' => 'closed']);
        $this->assertTrue(Arr::has($orders, '0.id'));
    }

    /**
     * @test
     */
    public function itCanIndexOrdersWithParams()
    {
        $orders = $this->shopifyService()->orders(['status' => 'closed']);

        foreach ($orders as $order) {
            $this->assertNotNull($order['closed_at']);
        }
    }

    /**
     * @test
     */
    public function itCanShowOrder()
    {
        $id = 2978329854105;
        $order = $this->shopifyService()->orderById($id);
        $this->assertTrue(Arr::get($order, 'id') === $id);
    }

    /**
     * @test
     */
    public function itCanIndexCustomers()
    {
        $customers = $this->shopifyService()->customers();
        $this->assertTrue(Arr::has($customers, '0.id'));
    }

    /**
     * @test
     */
    public function itCanShowCustomer()
    {
        $id = 4446505730201;
        $customer = $this->shopifyService()->customerById($id);
        $this->assertTrue(Arr::get($customer, 'id') === $id);
    }
}
