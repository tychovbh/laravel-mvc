<?php

namespace Tychovbh\Mvc\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tychovbh\Mvc\Models\User;

trait HasUser
{
    /**
     * The User
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
