<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Mvc\Field;
use Tychovbh\Mvc\Form;
use Tychovbh\Mvc\Http\Resources\FieldResource;
use Tychovbh\Mvc\Input;
use Tychovbh\Tests\Mvc\TestCase;

class FieldTest extends TestCase
{
    /**
     * @test
     */
    public function itCanUpdate()
    {
        $form = factory(Form::class)->create([
            'name' => 'testname'
        ]);

        $input = factory(Input::class)->create([
            'name' => 'testname'
        ]);

        $field = factory(Field::class)->create([
            'form_id' => $form->id,
            'input_id' => $input->id
        ]);

        $field->input = $input;
        $this->update('fields.update', (new FieldResource($field)), [
            'form_id' => $form->id,
            'input' => $input->name
        ]);

        $this->assertEquals($field->form->name, $form->name);
    }
}
