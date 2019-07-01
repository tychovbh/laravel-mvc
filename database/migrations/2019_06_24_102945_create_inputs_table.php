<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inputs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        DB::table('inputs')->insert([
            [
                'label' => 'text',
                'name' => 'text',
                'description' => 'text'
            ],
            [
                'label' => 'number',
                'name' => 'number',
                'description' => 'number'
            ],
            [
                'label' => 'email',
                'name' => 'email',
                'description' => 'email'
            ],
            [
                'label' => 'checkbox',
                'name' => 'checkbox',
                'description' => 'checkbox'
            ],
            [
                'label' => 'radio',
                'name' => 'radio',
                'description' => 'radio'
            ],
            [
                'label' => 'drop_down',
                'name' => 'select',
                'description' => 'select'
            ],
            [
                'label' => 'search',
                'name' => 'search',
                'description' => 'search'
            ],
            [
                'label' => 'wysiwyg',
                'name' => 'wysiwyg',
                'description' => 'wysiwyg'
            ],
            [
                'label' => 'file',
                'name' => 'file',
                'description' => 'file'
            ],
            [
                'label' => 'Textarea',
                'name' => 'textarea',
                'description' => 'textarea'
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
        Schema::dropIfExists('inputs');
    }
}
