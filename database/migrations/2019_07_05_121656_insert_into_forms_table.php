<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Tychovbh\Mvc\Form;
use Tychovbh\Mvc\Input;

class InsertIntoFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('forms')->insert([
            [
                'label' => 'Store Form',
                'name' => 'forms',
                'description' => 'A form to store forms',
            ]
        ]);

        DB::table('fields')->insert([
            [
                'label' => 'Label',
                'name' => 'label',
                'description' => 'A field to get label',
                'placeholder' => 'Please enter label',
                'required' => 1,
                'form_id' => Form::where('name', 'forms')->first()->id,
                'input_id' => Input::where('name', 'text')->first()->id,
            ],
            [
                'label' => 'Name',
                'name' => 'name',
                'description' => 'A field to get name',
                'placeholder' => 'Please enter name',
                'required' => 1,
                'form_id' => Form::where('name', 'forms')->first()->id,
                'input_id' => Input::where('name', 'text')->first()->id,
            ],
            [
                'label' => 'Description',
                'name' => 'description',
                'description' => 'A field to get description',
                'placeholder' => 'Please enter description',
                'required' => 1,
                'form_id' => Form::where('name', 'forms')->first()->id,
                'input_id' => Input::where('name', 'text')->first()->id,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
