<?php

namespace Tychovbh\Tests\Mvc\App;

class TestUser extends \Illuminate\Database\Eloquent\Model
{
    protected $fillable = ['email', 'password', 'firstname', 'surname'];
    protected $hidden = ['password'];
}
