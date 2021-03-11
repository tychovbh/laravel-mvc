<?php
declare(strict_types=1);

namespace Tychovbh\Mvc\Models;

use Database\Factories\FormFactory;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends Model
{
    use HasFactory;

    /**
     * Form constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('label', 'name', 'description', 'table', 'fields');
        $this->associations([
            [
                'relation' => 'fields',
                'model' => Field::class,
                'post_field' => 'fields',
                'type' => HasMany::class
            ]
        ]);
        parent::__construct($attributes);
    }

    /**
     * The Fields
     * @return HasMany
     */
    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    /**
     * The route
     * @return string
     */
    public function getRouteAttribute() : string
    {
        try {
            return route($this->name . '.store');
        } catch (Exception $exception) {
            return '';
        }
    }

    /**
     * Change factory
     * @return FormFactory
     */
    protected static function newFactory()
    {
        return FormFactory::new();
    }
}
