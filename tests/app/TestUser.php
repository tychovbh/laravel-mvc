<?php

namespace Tychovbh\Tests\Mvc\App;

use Tychovbh\Mvc\Models\Model;

class TestUser extends Model
{
    protected $cacheable = true;

    /**
     * TestUser constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillables('email', 'password', 'avatar', 'name');
        $this->files(['avatar' => 'public/avatars']);
        $this->hiddens('password');
        parent::__construct($attributes);
    }
}
