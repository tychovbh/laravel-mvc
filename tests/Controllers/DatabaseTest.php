<?php
declare(strict_types=1);

namespace Tests\Controllers;

use Tychovbh\Mvc\Http\Resources\DatabaseResource;
use Tychovbh\Mvc\Models\Database;
use Tests\TestCase;

class DatabaseTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $databases = Database::factory()->count(2)->create();
        $this->index('databases.index', DatabaseResource::collection($databases));
    }

    /**
     * @test
     */
    public function itCanStore()
    {
        $database = Database::factory()->make();
        $this->store('databases.store', new DatabaseResource($database), $database->toArray());
    }
}

