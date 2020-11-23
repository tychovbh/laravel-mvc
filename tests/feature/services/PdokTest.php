<?php


namespace Tychovbh\Tests\Mvc\feature\services;


use GuzzleHttp\Exception\GuzzleException;
use Tychovbh\Mvc\Services\AddressLookup\AddressLookupInterface;
use Tychovbh\Mvc\Services\AddressLookup\PdokService;
use Tychovbh\Tests\Mvc\TestCase;

class PdokTest extends TestCase
{
    /**
     * @test
     */
    public function itCanSearch()
    {
        $pdokService = new PdokService();
        $search = $pdokService->search('2352 CZ', '38');
        $this->assertEquals('Touwbaan', $search['street']);
        $this->assertEquals('Leiderdorp', $search['city']);
    }
}
