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
    /**
     * ContractObserver constructor.
     * @param DocumentSignInterface $documentSign
     * @param HtmlConverterInterface $htmlConverter
     */
    public function __construct(DocumentSignInterface $documentSign, HtmlConverterInterface $htmlConverter)
    {
        $this->documentSign = $documentSign;
        $this->htmlConverter = $htmlConverter;
    }

    /**
     * Created event
     * @param Contract $contract
     * @throws \Throwable
     */
    public function created(Contract $contract)
    {
        $contract->toPdf($this->htmlConverter);
        $contract->sign($this->documentSign);
    }
}
