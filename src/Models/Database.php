<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Models;

use Database\Factories\DatabaseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int id
 * @property string label
 * @property string name
 * @property string host
 * @property string username
 * @property string password
 * @property int port
 * @property string driver
 * @property int user_id
 * @property User user
 * @property Collection $tables
 */
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
        $this->columns('name', 'user_id');
        parent::__construct($attributes);
    }

    /**
     * The tables.
     * @return HasMany
     */
    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }

    /**
     * The user.
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return DatabaseFactory
     */
    public static function newFactory(): DatabaseFactory
    {
        return DatabaseFactory::new();
    }
}
