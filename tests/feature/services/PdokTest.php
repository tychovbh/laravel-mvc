<?php


namespace Tychovbh\Tests\Mvc\feature\services;


use GuzzleHttp\Exception\GuzzleException;
use Tychovbh\Mvc\Services\AddressLookup\AddressLookupInterface;
use Tychovbh\Mvc\Services\AddressLookup\PdokService;
use Tychovbh\Tests\Mvc\TestCase;

class PdokTest extends TestCase
{

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    __

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
