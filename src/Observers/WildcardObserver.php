<?php

namespace Tychovbh\Mvc\Observers;

use Tychovbh\Mvc\Jobs\DatabaseCrawl;
use Tychovbh\Mvc\Models\Wildcard;

class WildcardObserver
{
    /**
     * Created event.
     * @param Wildcard $wildcard
     */
    public function created(Wildcard $wildcard)
    {

        if ($wildcard->database_table->name === 'databases' && $wildcard->database->name === 'managedat') {
            dispatch(new DatabaseCrawl($wildcard->database));
        }
    }
}
