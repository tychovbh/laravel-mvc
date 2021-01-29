<?php


namespace Tychovbh\Tests\Mvc\feature\services\Shop;


use GuzzleHttp\Client;
use Tychovbh\Mvc\Services\Shop\Shopify;
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
     * @return array
     */
    public function itCanIndexProducts(): array
    {
        $products = $this->shopifyService()->products(['limit' => 2]);

        $this->assertTrue(count($products) > 0);

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
     * @return array
     */
    public function itCanIndexOrders(): array
    {
        $orders = $this->shopifyService()->orders(['limit' => 35]);
        $this->assertTrue(count($orders) > 0);

        return $orders;
    }

    /**
     * @test
     */
    public function itCanIndexOrdersWithParams()
    {
        $orders = $this->shopifyService()->orders(['limit' => 2, 'status' => 'closed']);

        $this->assertTrue(count($orders) > 0);

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
     * @return array
     */
    public function itCanIndexCustomers(): array
    {
        $customers = $this->shopifyService()->customers(['limit' => 2]);
        $this->assertTrue(count($customers) > 0);

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

    /**
     * @test
     */
    public function itCanStoreDiscount()
    {
        $data = $this->shopifyService()->storeDiscount([
            'price_rule' => [
                "title" => "SUMMERSALE10OFF",
                "target_type" => "line_item",
                "target_selection" => "all",
                "allocation_method" => "across",
                "value_type" => "fixed_amount",
                "value" => "-10.0",
                "customer_selection" => "all",
                "starts_at" => "2017-01-19T17:59:10Z"
            ],
            'discount_codes' => [
                ['code' => 'SUMMERSALE10OFF']
            ]
        ]);
    }
}
