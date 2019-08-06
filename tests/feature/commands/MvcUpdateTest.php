<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Tychovbh\Tests\Mvc\TestCase;

class MvcUpdateTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdateMvc()
    {
        $this->artisan('mvc:update');

        foreach (config('mvc-forms') as $table => $items) {
            $forget = [];
            switch ($table) {
                case 'properties':
                    $forget = ['options'];
                    break;
                case 'elements':
                    $forget = ['properties'];
                    break;
                case 'forms':
                    $forget = ['fields'];
                    break;
            }

            $this->assertDatabaseHasCollection($table, array_map(function ($item) use ($forget) {
                Arr::forget($item, $forget);
                return $item;
            }, $items));
        }

        foreach (config('mvc-forms.elements') as $element) {
            $this->assertDatabaseHasCollection('element_properties', array_map(function ($property) use ($element) {
                return [
                    'element_id' => DB::table('elements')->where('name', $element['name'])->first()->id,
                    'property_id' => DB::table('properties')->where('name', $property)->first()->id,
                ];
            }, $element['properties']));
        }

        foreach (config('mvc-forms.forms') as $form) {
            $this->assertDatabaseHasCollection('fields', array_map(function ($field) use ($form) {
                return [
                    'form_id' => DB::table('forms')->where('name', $form['name'])->first()->id,
                    'element_id' => DB::table('elements')->where('name', $field['element'])->first()->id,
                    'properties' => json_encode($field['properties'])
                ];
            }, $form['fields']));
        }
    }
}
