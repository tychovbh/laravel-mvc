<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Mvc\Models\Form;
use Tychovbh\Mvc\Http\Controllers\FormController;
use Tychovbh\Mvc\Http\Resources\FormResource;
use Tychovbh\Tests\Mvc\TestCase;

class FormTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $forms = Form::all();
        $this->index('forms.index', FormResource::collection($forms));
    }

    /**
     * @test
     */
    public function itCanShow()
    {
        factory(Form::class, 2)->create();
        $form = factory(Form::class)->create();

        $this->show('forms.show', (new FormResource($form)));
    }

    /**
     * @test
     */
    public function itCanStore()
    {
        $form = factory(Form::class)->make();
        $this->store('forms.store', (new FormResource($form)), $form->toArray());
    }

    /**
     * @test
     */
    public function itCanUpdate()
    {
        $form = factory(Form::class)->create();
        $this->update('forms.update', (new FormResource($form)), $form->toArray());
    }

    /**
     * @test
     */
    public function itCanDelete()
    {
        $this->destroy('forms.destroy', factory(Form::class)->create());
    }
}

