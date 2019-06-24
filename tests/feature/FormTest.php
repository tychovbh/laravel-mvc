<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Mvc\Form;
use Tychovbh\Mvc\Http\Resources\FormResource;
use Tychovbh\Tests\Mvc\TestCase;

class FormTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $forms = factory(Form::class, 3)->create();
        $this->index('forms.index', (FormResource::collection($forms)));
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
        $this->store('forms.store', (new FormResource($form)));
    }

    /**
     * @test
     */
    public function itCanUpdate()
    {
        $form = factory(Form::class)->create();
        $this->update('forms.update', (new FormResource($form)));
    }

    /**
     * @test
     */
    public function itCanDelete()
    {
        $form = factory(Form::class)->create();
        $this->destroy('forms.destroy', (new FormResource($form)));
    }
}

