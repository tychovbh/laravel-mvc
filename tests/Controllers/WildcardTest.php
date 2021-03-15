<?php
declare(strict_types=1);

namespace Tests\Controllers;

use Tychovbh\Mvc\Http\Resources\DatabaseResource;
use Tychovbh\Mvc\Models\Database;
use Tests\TestCase;

class WildcardTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $database = Database::factory()->create();
        $table = $database->tables->first();

        $this->get(route('wildcards.index', [
            'connection' => $database->name,
            'table' => $table->name,
            'user_id' => $database->user_id,
        ]))
            ->assertJson([])
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function itCanStore()
    {
        $database = Database::factory()->create();
        $table = $database->tables->first();

        $this->post(route('wildcards.store', [
            'connection' => $database->name,
            'table' => $table->name,
            'user_id' => $database->user_id,
        ]), [
            'name' => 'test-category',
            'label' => 'Test Category'
        ])
            ->assertJson([])
            ->assertStatus(201);
    }
}

