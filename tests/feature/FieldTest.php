<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Mvc\Field;
use Tychovbh\Mvc\Form;
use Tychovbh\Mvc\Http\Resources\FieldResource;
use Tychovbh\Tests\Mvc\TestCase;

class FieldTest extends TestCase
{
    /**
     * @test
     */
    public function itCanBelongToForm()
    {
        $form = factory(Form::class)->create([
            'name' => 'testname'
        ]);

        $field = factory(Field::class)->create([
            'form_id' => $form->id,
        ]);

        $this->update('fields.update', (new FieldResource($field)));

        $this->assertEquals($field->form->name, $form->name);
    }
}
