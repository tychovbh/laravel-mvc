<?php

namespace Tychovbh\Tests\Mvc\feature;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Address;
use Tychovbh\Mvc\Http\Resources\AddressResource;
use Tychovbh\Mvc\Service\AddressLookup\PdokService;
use Tychovbh\Tests\Mvc\TestCase;

class AddressTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $address = factory(Address::class, 2)->create();
        $this->index('addresses.index', AddressResource::collection($address));
    }

    /**
     * @test
     */
    public function itCanShow()
    {
        $address = factory(Address::class)->create();
        $this->show('addresses.show', AddressResource::make($address));
    }

    /**
     * @test
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function itCanStore()
    {
        $address = factory(Address::class)->make(['zipcode' => '2352 CZ', 'house_number' => '38', 'country' => 'Nederland']);

        $finalAddress = PdokService::search($address->zipcode, $address->house_number, $address->country);

        $this->store('addresses.store', AddressResource::make($address), $finalAddress);
    }

    /**
     * @test
     */
    public function itCannotStoreZipcodeMissing()
    {
        $address = factory(Address::class)->make(['house_number' => '38', 'country' => 'Nederland']);
        Arr::forget($address, 'zipcode');
        $this->store('addresses.store', AddressResource::make($address), $address->toArray(), 400);
    }

    /**
     * @test
     */
    public function itCannotStoreHouseNumberMissing()
    {
        $address = factory(Address::class)->make(['zipcode' => '2352 CZ', 'country' => 'Nederland']);
        Arr::forget($address, 'house_number');
        $this->store('addresses.store', AddressResource::make($address), $address->toArray(), 400);
    }

    /**
     * @test
     */
    public function itFindsExistingRecordInsteadOfStoring()
    {
        $address = factory(Address::class)->create();
        $this->store('addresses.store', AddressResource::make($address), $address->toArray(), 200);
    }
}
