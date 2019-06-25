<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    protected $fillable = ['label', 'name', 'description', 'placeholder', 'required', 'form_id', 'input_id'];

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
