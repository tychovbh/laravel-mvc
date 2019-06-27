<?php

namespace Tychovbh\Tests\Mvc\App;

use Illuminate\Database\Eloquent\Model;

class TestUser extends Model
{
    protected $fillable = ['email', 'password', 'firstname', 'surname'];
    protected $hidden = ['password'];
}
