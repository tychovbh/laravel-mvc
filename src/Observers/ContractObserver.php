<?php

namespace Tychovbh\Mvc\Observers;

use Illuminate\Support\Arr;
use Tychovbh\Mvc\Contract;
use Tychovbh\Mvc\Services\HtmlConverter\HtmlConverter;

class ContractObserver
{
    /**
     * @param Contract $contract
     */
    public function creating(Contract $contract)
    {
        $page = view($contract->template);
        $html = $page->render();
        $htmlConverter = new HtmlConverter();
        $path = 'contracts/file.pdf';
        $htmlConverter->page($html)->save($path);
        $contract->file = $path;
        $contract->unsetAttribute('template');
        $test = '';
    }
}
