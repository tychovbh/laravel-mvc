<?php


namespace Tychovbh\Tests\Mvc\feature\services\ShopService;


use Tychovbh\Mvc\Services\ShopService\ShopifyService;
use Tychovbh\Tests\Mvc\TestCase;

class ShopifyTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndexProducts()
    {
        $shopService = new ShopifyService();
        $products = $shopService->products();
    }

    /**
     * @test
     */
    public function itCanIndexOrders()
    {
        $shopService = new ShopifyService();
        $orders = $shopService->orders();
    }

    /**
     * @test
     */
    public function itCanIndexDiscountCodes()
    {
        $shopService = new ShopifyService();
        $discountCodes = $shopService->discountCodes(1);
    }

    /**
     * @test
     */
    public function itCanIndexCustomers()
    {
        $shopService = new ShopifyService();
        $customers = $shopService->customers();
    }
}
