<?php

namespace Tychovbh\Mvc;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['role_id', 'channel_id', 'user_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
