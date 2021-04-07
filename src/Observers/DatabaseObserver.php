<?php

namespace Tychovbh\Mvc\Observers;

use Tychovbh\Mvc\Jobs\DatabaseCrawl;
use Tychovbh\Mvc\Models\Database;

class DatabaseObserver
{
    /**
     * Creating event.
     * @param Database $database
     */
    public function creating(Database $database)
    {
        if (!$database->user_id) {
            $database->user_id = user()->id;
        }
    }

    /**
     * Created event.
     * @param Database $database
     */
    public function created(Database $database)
    {
        dispatch(new DatabaseCrawl($database));
    }
}
