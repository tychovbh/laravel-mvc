<?php

namespace Tychovbh\Mvc\Observers;

use Tychovbh\Mvc\Jobs\DatabaseCrawl;
use Tychovbh\Mvc\Models\Database;
use Tychovbh\Mvc\Models\Wildcard;

class WildcardObserver
{
    /**
     * Created event.
     * @param Wildcard $wildcard
     */
    public function created(Wildcard $wildcard)
    {

        if (
            $wildcard->database_table->name === 'databases' &&
            $wildcard->database->name === config('database.connections.mysql.database')
        ) {
            $database = new Database($wildcard->toArray());
            $database->id = $wildcard->id;
            dispatch(new DatabaseCrawl($database));
        }
    }
}
