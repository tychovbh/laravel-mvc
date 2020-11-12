<?php

namespace Tychovbh\Mvc\Observers;

use Tychovbh\Mvc\Contract;
use Tychovbh\Mvc\Services\HtmlConverter\HtmlConverter;

class ContractObserver
{
    /**
     * @param Contract $contract
     */
    public function creating(Contract $contract)
    {
        $htmlConverter = new HtmlConverter();
        $path = 'contracts/file.pdf';
        $htmlConverter->page('<html lang="en"><h1>Contract</h1></html>')->save($path);
        $contract->file = $path;
    }
}
