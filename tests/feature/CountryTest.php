<?php


namespace Tychovbh\Tests\Mvc\feature;


use Tychovbh\Mvc\Address;
use Tychovbh\Mvc\Country;
use Tychovbh\Mvc\Http\Resources\AddressResource;
use Tychovbh\Mvc\Http\Resources\CountryResource;
use Tychovbh\Mvc\Repositories\CountryRepository;
use Tychovbh\Tests\Mvc\TestCase;

class CountryTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $countries = Country::all();
        $this->index('countries.index', CountryResource::collection($countries));
    }

    /**
     * @test
     */
    public function itCanShow()
    {
        $country = factory(Country::class)->create();
        $this->show('countries.show', CountryResource::make($country));
    }

    /**
     * @test
     */
    public function itCanStore()
    {
        $country = factory(Country::class)->make();
        $this->store('countries.store', CountryResource::make($country), $country->toArray());
    }

    /**
     * @test
     */
    public function itCanUpdate()
    {
        $country = factory(Country::class)->create();
        $update = factory(Country::class)->make();
        $update->id = $country->id;
        $this->update('countries.update', CountryResource::make($update), $update->toArray());
    }

    /**
     * @test
     */
    public function itCanDestroy()
    {
        $this->destroy('countries.destroy',  factory(Country::class)->create());
    }
}
