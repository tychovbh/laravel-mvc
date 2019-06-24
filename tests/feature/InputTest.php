<?php
declare(strict_types=1);

namespace Tychovbh\Tests\Mvc\Feature;

use Tychovbh\Mvc\Http\Resources\InputResource;
use Tychovbh\Mvc\Input;
use Tychovbh\Tests\Mvc\TestCase;

class InputTest extends TestCase
{
    /**
     * @test
     */
    public function itCanIndex()
    {
        $forms = factory(Input::class, 3)->create();
        $this->index('inputs.index', (InputResource::collection($forms)));
    }

    /**
     * @test
     */
    public function itCanShow()
    {
        factory(Input::class, 2)->create();
        $form = factory(Input::class)->create();

        $this->show('inputs.show', (new InputResource($form)));
    }
}
