<?php

namespace Tychovbh\Mvc\Services\ShopService;

interface ShopServiceInterface
{
    /**
     * @param array $params
     * @return array
     */
    public function products(array $params = []): array;

    /**
     * @param array $params
     * @return array
     */
    public function orders(array $params = []): array;

    /**
     * @param $priceRuleId
     * @param array $params
     * @return array
     */
    public function discountCodes($priceRuleId, array $params = []): array;

    /**
     * @param array $params
     * @return array
     */
    public function customers(array $params = []): array;

}
