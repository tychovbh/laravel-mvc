<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Mvc\Models\Field;
use Tychovbh\Mvc\Models\Form;
use Tychovbh\Mvc\Http\Resources\FieldResource;
use Tychovbh\Mvc\Models\Element;
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

        $element = factory(Element::class)->create([
            'name' => 'testname'
        ]);

        $field = factory(Field::class)->create([
            'form_id' => $form->id,
            'element_id' => $element->id
        ]);

        $field->element = $element;
        $this->update('fields.update', (new FieldResource($field)), [
            'form_id' => $form->id,
            'input' => $element->name
        ]);

        $this->assertEquals($field->form->name, $form->name);
    }
}
