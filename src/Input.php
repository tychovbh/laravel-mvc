<?php
declare(strict_types=1);

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Input extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['label', 'name', 'description'];

    /**
     * The fields
     * @return HasMany
     */
    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }
}