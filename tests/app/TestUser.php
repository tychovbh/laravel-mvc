<?php

namespace Tychovbh\Tests\Mvc\App;

use Tychovbh\Mvc\Model;

class TestUser extends Model
{
    protected $fillable = ['email', 'password', 'avatar', 'name'];
    protected $hidden = ['password'];

    protected $files = [
        'avatar' => 'public/avatars'
    ];
}
