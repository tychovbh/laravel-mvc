<?php

namespace Tychovbh\Mvc\Observers;

use GuzzleHttp\Exception\GuzzleException;
use Tychovbh\Mvc\Address;
use Tychovbh\Mvc\Services\AddressLookup\AddressLookupInterface;
use Tychovbh\Mvc\Services\AddressLookup\PdokService;

/**
 * @property AddressLookupInterface addressLookup
 */
class AddressObserver
{

    /**
     * AddressObserver constructor.
     * @param AddressLookupInterface $addressLookup
     */
    public function __construct(AddressLookupInterface $addressLookup)
    {
        $this->addressLookup = $addressLookup;
    }

    /**
     * @param Address $address
     * @throws GuzzleException
     */
    public function creating(Address $address)
    {
        if (!$address->street) {
            $address->fill($this->addressLookup::search($address->zipcode, $address->house_number));
        }
    }
}
