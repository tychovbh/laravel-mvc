<?php

namespace Tychovbh\Mvc\Observers;

use Tychovbh\Mvc\Address;
use Tychovbh\Mvc\Service\AddressLookup\PdokService;

class AddressObserver
{
    public function creating(Address $address)
    {
        $address = PdokService::search($address->zipcode, $address->house_number);
    }
}
