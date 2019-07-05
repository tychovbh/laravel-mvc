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
                'label' => 'Text Input',
                'name' => 'text',
                'description' => 'Accepts letters, numbers and special characters.'
            ],
            [
                'label' => 'Number Input',
                'name' => 'number',
                'description' => 'Accepts only numbers.'
            ],
            [
                'label' => 'Email Input',
                'name' => 'email',
                'description' => 'Accepts only a valid e-mail address'
            ],
            [
                'label' => 'Checkbox Input',
                'name' => 'checkbox',
                'description' => 'Ask a no or yes question.'
            ],
            [
                'label' => 'Radio Input',
                'name' => 'radio',
                'description' => 'Ask a group of questions where do user can pick from.'
            ],
            [
                'label' => 'Dropdown Input',
                'name' => 'select',
                'description' => 'Ask a list of questions where do user can pick from.'
            ],
            [
                'label' => 'Search Input',
                'name' => 'search',
                'description' => 'Searchable text input, lists results from a data collection.'
            ],
            [
                'label' => 'Richt Text Editor',
                'name' => 'wysiwyg',
                'description' => 'Accepts text, photo\'s, video\'s and enables font customisation.'
            ],
            [
                'label' => 'File Input',
                'name' => 'file',
                'description' => 'Accepts a file'
            ],
            [
                'label' => 'TextArea Input',
                'name' => 'textarea',
                'description' => 'Accepts the same as a Text Input, but with a larger typing area'
            ],
            [
                'label' => 'Dropzone Upload',
                'name' => 'dropzone',
                'description' => 'File upload with drag and drop'
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
