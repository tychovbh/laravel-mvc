<?php

namespace Tychovbh\Tests\Mvc\App;

use Tychovbh\Mvc\Repositories\AbstractRepository;
use Tychovbh\Mvc\Repositories\Repository;

class TestUserRepository extends AbstractRepository implements Repository
{
    /**
     * TestUserRepository constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->model = new TestUser();
        parent::__construct();
    }

    /**
     * Index param search.
     * @param string $search
     */
    public function indexSearchParam(string $search)
    {
        $this->query->where('email', $search)
            ->orWhere('firstname', $search)
            ->orWhere('surname', $search);
    }

    /**
     * Show param hidden.
     * @param int $hidden
     */
    public function showHiddenParam(int $hidden)
    {
        $this->query->where('hidden', $hidden);
    }
}
