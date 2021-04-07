<?php

namespace Database\Factories;

use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Tychovbh\Mvc\Models\Database;
use Tychovbh\Mvc\Models\Table;
use Tychovbh\Mvc\Models\Wildcard;

class WildcardFactory
{
    /**
     * @var int
     */
    protected $count;

    /**
     * @var Database
     */
    protected $database;

    /**
     * @var Table
     */
    protected $table;

    /**
     * WildcardFactory constructor.
     * @param Database $database
     * @param Table $table
     */
    public function __construct(Database $database, Table $table)
    {
        $this->database = $database;
        $this->table = $table;
        $this->count = 1;
    }

    /**
     * Amount of Wildcards to factor
     * @param int $count
     * @return $this
     */
    public function count(int $count): self
    {
        $this->count = $count;
        return $this;
    }

    /**
     * The Wildcard Data
     * @return array
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @return array
     */
    private function data(): array
    {
        $data = [];
        $faker = Container::getInstance()->make(Generator::class);

        foreach ($this->table->fields as $field) {
            if (!$field['fillable']) {
                continue;
            }

            $value = '';

            if ($field['element'] === 'input') {
                switch ($field['properties']['type']) {
                    case 'text':
                        $value = $faker->name;
                        break;
                    case 'number':
                        $value = $faker->randomNumber();
                        break;
                    case 'date':
                        $value = $faker->date();
                        break;
                }
            }

            if ($field['element'] === 'select') {
                $options = $field['properties']['options'];
                $value = count($options) > 0 ? $options[rand(0, count($options) - 1)]['value'] : $value;
            }


            $data[$field['name']] = $value;
        }

        return $data;
    }

    /**
     * Create Wildcard
     * @return Collection|Wildcard
     */
    public function create()
    {
        return $this->makeOrCreate(true);
    }

    /**
     * Make Wildcard
     * @return Collection|Wildcard
     */
    public function make()
    {
        return $this->makeOrCreate(false);
    }

    /**
     * Make or create Wildcard
     * @param bool $create
     * @return Collection|Wildcard
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function makeOrCreate(bool $create)
    {
        $wildcards = collect([]);
        for ($i = 0; $i < $this->count; $i++) {
            $wildcard = new Wildcard();
            $wildcard->setUp($this->database, $this->table);
            $wildcard->fill($this->data());
            if ($create) {
                $wildcard->save();
            }

            $wildcards->push($wildcard);
        }

        return $this->count > 1 ? $wildcards : $wildcards->first();
    }
}
