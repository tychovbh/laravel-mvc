<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Tychovbh\Mvc\Models\User;
use Tychovbh\Mvc\Models\Wildcard;

class WildcardRepository extends AbstractRepository implements Repository
{
    /**
     * Filter on database name
     * @param string $database
     */
    public function indexDatabaseParam(string $database)
    {
        if (request('connection') === config('database.connections.mysql.database')) {
            $this->join('databases', 'databases.id', 'database_id');
            $this->query->where('databases.name', $database);
        }
    }
}
