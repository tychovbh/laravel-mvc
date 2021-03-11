<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Models;

use Database\Factories\DatabaseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Database extends Model
{
    use HasFactory;

    const DRIVER_MYSQL = 'mysql';

    /**
     * Element constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('label', 'name', 'host', 'username', 'password', 'port', 'driver', 'user_id');
        parent::__construct($attributes);
    }

    /**
     * @return DatabaseFactory
     */
    public static function newFactory(): DatabaseFactory
    {
        return DatabaseFactory::new();
    }
}
