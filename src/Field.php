<?php
declare(strict_types=1);

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['properties', 'form_id', 'element'];

    /**
     * @var array
     */
    protected $associations = [
        [
            'model' => Element::class,
            'post_field' => 'element',
            'table_field' => 'name',
            'type' => BelongsTo::class
        ]
    ];

    /**
     * @var array
     */
    protected $casts = ['properties' => 'array'];

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
