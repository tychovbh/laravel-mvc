<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Repositories;

class TableRepository extends AbstractRepository implements Repository
{
    /**
     * Filter tables on database names
     * @param array|string $database
     */
    public function indexDatabaseParam($database)
    {
        $databases = is_array($database) ? $database : [$database];
        $this->join('databases', 'tables.database_id', 'databases.id');
        $this->query->whereIn('databases.name', $databases);
    }
}
