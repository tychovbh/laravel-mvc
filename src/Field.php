<?php
declare(strict_types=1);

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['label', 'name', 'description', 'placeholder', 'required', 'form_id', 'input_id'];

    /**
     * @var array
     */
    protected $associations = [
        [
            'model' => Input::class,
            'post_field' => 'input',
            'table_field' => 'name'
        ]
    ];

    /**
     * The Form
     * @return BelongsTo
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * The Input
     * @return BelongsTo
     */
    public function input(): BelongsTo
    {
        return $this->belongsTo(Input::class);
    }
}
