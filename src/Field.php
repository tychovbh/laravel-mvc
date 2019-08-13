<?php
declare(strict_types=1);

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    /**
     * Field constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('properties', 'form_id', 'element');
        $this->casts(['properties' => 'array']);
        $this->associations([
            'element' => [
                'model' => Element::class,
                'table_field' => 'name',
                'post_field' => 'element',
                'type' => BelongsTo::class
            ]
        ]);

        parent::__construct($attributes);
    }

    /**
     * The Form
     * @return BelongsTo
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * The Element
     * @return BelongsTo
     */
    public function element(): BelongsTo
    {
        return $this->belongsTo(Element::class);
    }
}
