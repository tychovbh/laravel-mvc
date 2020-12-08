<?php


namespace Tychovbh\Mvc\Services\ShopService;


use Shopify\PrivateApi;
use Shopify\Service\CustomerService;
use Shopify\Service\DiscountCodeService;
use Shopify\Service\OrderService;
use Shopify\Service\ProductService;

class ShopifyService implements ShopServiceInterface
{
    private $client;

    /**
     * ShopifyService constructor.
     */
    public function __construct()
    {
        $this->client = new PrivateApi(array(
            'api_key' => '08d3e6fc68e815fc1f034fee77381564',
            'password' => 'uaPVkF3KAeiZQbfs',
            'shared_secret' => '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',
            'myshopify_domain' => 'woonwijzertrial.myshopify.com'
        ));
    }

    /**
     * @param array $params
     * @return array
     */
    public function products(array $params = []): array
    {
        $products = new ProductService($this->client);
        return $products->all($params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function orders(array $params = []): array
    {
        $orders = new OrderService($this->client);
        return $orders->all($params);
    }

    /**
     * @param $priceRuleId
     * @param array $params
     * @return array
     */
    public function discountCodes($priceRuleId, array $params = []): array
    {
        $discountCodes = new DiscountCodeService($this->client);
        return $discountCodes->all($priceRuleId, $params);
    }

    /**
     * @param array $params
     * @return array
     */
    public function customers(array $params = []): array
    {
        $customers = new CustomerService($this->client);
        return $customers->all();
    }
}
