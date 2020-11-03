<?php


namespace Tychovbh\Tests\Mvc\feature;


use Tychovbh\Mvc\Country;
use Tychovbh\Mvc\Http\Resources\CountryResource;
use Tychovbh\Tests\Mvc\TestCase;

class CountryTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $country = factory(Country::class, 1)->make(['name' => 'nl', 'label' => 'Netherlands']);
        $this->index('countries.index', CountryResource::make($country));
    }
}
