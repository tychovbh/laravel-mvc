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
        'element' => [
            'model' => Element::class,
            'table_field' => 'name',
            'post_field' => 'element',
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
