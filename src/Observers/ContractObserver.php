<?php

namespace Tychovbh\Mvc\Observers;

use Tychovbh\Mvc\Contract;
use Tychovbh\Mvc\Services\DocumentSign\DocumentSignInterface;
use Tychovbh\Mvc\Services\HtmlConverter\HtmlConverterInterface;

/**
 * @property DocumentSignInterface documentSign
 * @property HtmlConverterInterface htmlConverter
 */
class ContractObserver
{
    public function __construct(DocumentSignInterface $documentSign, HtmlConverterInterface $htmlConverter)
    {
        $this->documentSign = $documentSign;
        $this->htmlConverter = $htmlConverter;
    }

    public function creating(Contract $contract)
    {
        $contract->toPdf($this->htmlConverter);
    }

    public function created(Contract $contract)
    {
        $contract->sign($this->documentSign);
    }
}
