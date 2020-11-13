<?php

namespace Tychovbh\Mvc\Observers;

use GuzzleHttp\Exception\GuzzleException;
use Tychovbh\Mvc\Address;
use Tychovbh\Mvc\Services\AddressLookup\PdokService;

class AddressObserver
{
    /**
     * @param Address $address
     * @throws GuzzleException
     */
    public function creating(Address $address)
    {
        if (!$address->street) {
            $address->fill(PdokService::search($address->zipcode, $address->house_number));
        }
    }
}
