<?php

namespace Tychovbh\Mvc\Observers;

use Tychovbh\Mvc\Contract;
use Tychovbh\Mvc\Services\DocumentSign\DocumentSignInterface;

/**
 * @property DocumentSignInterface documentSign
 */
class ContractObserver
{
    public function __construct(DocumentSignInterface $documentSign)
    {
        $this->documentSign = $documentSign;
    }

    /**
     * @param Contract $contract
     */
    public function creating(Contract $contract)
    {
        $contract->toPdf();
    }

    public function created(Contract $contract)
    {
        $contract->sign($this->documentSign);
    }
}
