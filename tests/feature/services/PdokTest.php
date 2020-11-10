<?php


namespace Tychovbh\Tests\Mvc\feature\services;


use GuzzleHttp\Exception\GuzzleException;
use Tychovbh\Mvc\Services\AddressLookup\PdokService;
use Tychovbh\Tests\Mvc\TestCase;

class PdokTest extends TestCase
{
    /**
     * @test
     * @throws GuzzleException
     */
    public function itCanSearch()
    {
        $search = PdokService::search('2352 CZ', '38');
        $this->assertEquals('Touwbaan', $search['street']);
        $this->assertEquals('Leiderdorp', $search['city']);
    }
}
