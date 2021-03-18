<?php
declare(strict_types=1);

namespace Tests\Controllers;

use Illuminate\Support\Str;

use Tychovbh\Mvc\Models\Database;
use Tests\TestCase;
use Tychovbh\Mvc\Models\Table;
use Tychovbh\Mvc\Models\Wildcard;

class WildcardTest extends TestCase
{
    /**
     * Factor Database
     * @return Database
     */
    private function database(): Database
    {
        return $database = Database::factory()->create();
    }

    /**
     * Factor Table
     * @param Database $database
     * @return Table
     */
    private function table(Database $database): Table
    {
        return $database->tables->first();
    }

    /**
     * @test
     */
    public function itCanIndex()
    {
        $database = $this->database();
        $table = $this->table($database);
        $user = $database->user;

        $wildcards = Wildcard::factory($database, $table)
            ->count(2)
            ->create();

        $this
            ->actingAs($user)
            ->get(route('wildcards.index', [
            'connection' => $database->name,
            'table' => $table->name,
            'additionals' => ['index', 'meta']
        ]))
            ->assertStatus(200)
            ->assertJson([
                'data' => $wildcards->toArray(),
                'index' => $table->index_fields->toArray(),
                'meta' => [
                    'name' => $table->name,
                    'label' => $table->label,
                ]
            ]);
    }

    /**
     * @test
     */
    public function itCanStore()
    {
        $database = $this->database();
        $table = $this->table($database);
        $user = $database->user;

        $wildcard = Wildcard::factory($database, $table)->make();

        $response = $this
            ->actingAs($user)
            ->post(route('wildcards.store', [
            'connection' => $database->name,
            'table' => $table->name,
        ]), $wildcard->toArray())
            ->assertStatus(201)
            ->assertJson([
                'data' => $wildcard->toArray()
            ]);

        return json_decode($response->getContent(), true)['data'];
    }

    /**
     * @test
     */
    public function itCanShow()
    {
        $database = $this->database();
        $table = $this->table($database);
        $user = $database->user;

        $wildcard = Wildcard::factory($database, $table)->create();

        $this
            ->actingAs($user)
            ->get(route('wildcards.show', [
            'connection' => $database->name,
            'table' => $table->name,
            'id' => $wildcard->id,
            'additionals' => ['show', 'meta']
        ]))
            ->assertStatus(200)
            ->assertJson([
                'data' => $wildcard->toArray(),
                'show' => $table->show_fields->toArray(),
                'meta' => [
                    'name' => $table->name,
                    'label' => $table->label,
                ]
            ]);
    }

    /**
     * @test
     */
    public function itCanCreate()
    {
        $database = $this->database();
        $table = $this->table($database);
        $user = $database->user;

        $this
            ->actingAs($user)
            ->get(route('wildcards.create', [
            'connection' => $database->name,
            'table' => $table->name,
        ]))
            ->assertStatus(200)
            ->assertJson([
                'data' => $table->create_form
            ]);
    }

    /**
     * @test
     */
    public function itCanEdit()
    {
        $database = $this->database();
        $table = $this->table($database);
        $user = $database->user;

        $wildcard = Wildcard::factory($database, $table)->create();

        $form = $table->edit_form;
        $form['route'] = Str::replaceFirst('id', $wildcard->id, $form['route']);
        $form['defaults'] = $wildcard->toArray();

        $this
            ->actingAs($user)
            ->get(route('wildcards.edit', [
            'connection' => $database->name,
            'table' => $table->name,
            'id' => $wildcard->id,
        ]))
            ->assertStatus(200)
            ->assertJson([
                'data' => $form
            ]);
    }

    /**
     * @test
     */
    public function itCanUpdate()
    {
        $database = $this->database();
        $table = $this->table($database);
        $user = $database->user;

        $wildcard = Wildcard::factory($database, $table)->create();
        $update = Wildcard::factory($database, $table)->make();

        $this
            ->actingAs($user)
            ->put(route('wildcards.update', [
            'connection' => $database->name,
            'table' => $table->name,
            'id' => $wildcard->id,
        ]), $update->toArray())
            ->assertStatus(200)
            ->assertJson([
                'data' => array_merge([
                    'id' => $wildcard->id
                ], $update->toArray())
            ]);
    }

    /**
     * @test
     * TODO make seeInDatabase
     */
    public function itCanDestroy()
    {
        $database = $this->database();
        $table = $this->table($database);
        $user = $database->user;

        $wildcard = Wildcard::factory($database, $table)->create();

        $this
            ->actingAs($user)
            ->delete(route('wildcards.destroy', [
            'connection' => $database->name,
            'table' => $table->name,
            'id' => $wildcard->id,
        ]))
            ->assertStatus(200)
            ->assertJson([
                'deleted' => true
            ]);
    }
}

